<?php

namespace VSPW\REST\V1;

use DI;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use VSPW\Exceptions\Abstracts\PublicException;
use VSPW\Repositories\WebsiteRepository;
use VSPW\Payloads\WebsitePayload;

/**
 * Class WebsitesRESTController
 * @package DI\REST\V1
 */
class WebsitesRESTController extends WP_REST_Controller
{
    /**
     * Registers this REST Controller
     */
    public function register()
    {
        $this->namespace = 'trial/v1';
        $this->rest_base = 'websites';

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Registers this REST Routes
     *
     * @todo check with someone if we shouldn't be verifying a nonce at CREATABLE?
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'args'                => $this->get_collection_params(),
            ],
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create_item'],
                'permission_callback' => [$this, 'create_item_permissions_check'],
                'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
            ],
            'schema' => [$this, 'get_public_item_schema'],
        ]);
    }

    /**
     * Generates the Schema for this REST Controller
     *
     * Sanitization and validation is made in WebsitePayload
     *
     * @see WebsitePayload
     * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/glossary/#schema
     *
     * @return array
     */
    public function get_item_schema()
    {
        return [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'website',
            'type'       => 'object',
            'properties' => [
                'name'        => [
                    'description'  => __('Person name.'),
                    'type'         => 'string',
                    'context'      => ['edit'],
                    'required'     => true,
                ],
                'url'        => [
                    'description'  => __('Requested website URL.'),
                    'type'         => 'string',
                    'format'       => 'uri',
                    'context'      => ['edit'],
                    'required'     => true,
                ],
            ],
        ];
    }

    /**
     * Returns "websites" with pagination
     *
     * @see WP_REST_Controller::get_collection_params()
     *
     * @param WP_REST_Request $request

     * @return mixed|WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        try {
            $response = [];

            /** Paginated array of WP_Posts of type "website" */
            $websites = DI::make(WebsiteRepository::class)->get($request['per_page'], $request['page'], $request['search']);

            /** Prepare REST response */
            foreach ($websites as $website) {
                // This will go through wp_json_encode, so no need to escape HERE only.
                $source_code = get_post_meta($website->ID, 'website_source_code', true);

                $response[] = [
                    'id'        => $website->ID,
                    'title'         => $website->post_title,
                    'source_code' => $source_code,
                ];
            }

            do_action('success_trial_rest_v1_get_items', $response);
            return new WP_REST_Response($response);
        } catch (PublicException $e) {
            do_action('fail_trial_rest_v1_get_items', $e, $request);
            return new WP_REST_Response($e->getMessage(), 400);
        }
    }

    /**
     * Determines whether the request has permission to get items
     *
     * @param WP_REST_Request $request
     *
     * @return bool|WP_Error True if the request has read access, otherwise false or WP_Error object.
     */
    public function get_items_permissions_check($request)
    {
        // Defaults to public
        return apply_filters('trial_rest_v1_website_can_get_items', true);
    }

    /**
     * Determines whether the request has permission to create items
     *
     * @param WP_REST_Request $request
     *
     * @return bool|WP_Error True if the request has create access, otherwise false or WP_Error object.
     */
    public function create_item_permissions_check($request)
    {
        // Defaults to public
        return apply_filters('trial_rest_v1_website_can_create', true);
    }

    /**
     * Create a new "website" post
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        try {
            $data = [
                'name' => $request['name'],
                'url'  => $request['url'],
            ];
            $payload  = WebsitePayload::makeFrom($data);

            $response = DI::make(WebsiteRepository::class)->add($payload);
            do_action('success_trial_rest_v1_create_item', $response);
            return new WP_REST_Response(true);
        } catch (PublicException $e) {
            do_action('fail_trial_rest_v1_create_item', $e, $request);
            return new WP_REST_Response($e->getMessage(), 400);
        }
    }
}
