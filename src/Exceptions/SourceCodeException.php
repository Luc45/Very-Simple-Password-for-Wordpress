<?php

namespace VSPW\Exceptions;

use VSPW\Exceptions\Abstracts\VSPWException;

/**
 * Class SourceCodeException
 * @package DI\Exceptions
 */
class SourceCodeException extends VSPWException
{
    /**
     * Throws when tried to fetch a URL using wp_remote_get,
     * but got WP_Error as a response
     *
     * @param \WP_Error $response
     *
     * @return SourceCodeException
     */
    public static function unexpected_error_response(\WP_Error $response)
    {
        return new self(sprintf(
            __('Tried to fetch URL but got WP_Error instead. Error message: %1$s (Code: %2$s)', 'trial-lucas-bustamante'),
            $response->get_error_message(),
            $response->get_error_code()
        ));
    }

    /**
     * Throws when tried to fetch a URL using wp_remote_get,
     * but got a status code !== 200
     *
     * @param $status_code
     *
     * @return SourceCodeException
     */
    public static function bad_status_code($status_code)
    {
        return new self(sprintf(
            __('Tried to fetch URL but got a status code other than 200. Status code: %s', 'trial-lucas-bustamante'),
            $status_code
        ));
    }

    /**
     * Throws when tried to fetch a URL, but the response length is bigger
     * than the maximum we find acceptable
     *
     * @param $size
     * @param $max_size
     *
     * @return SourceCodeException
     */
    public static function exceeds_maximum_size($size, $max_size)
    {
        return new self(sprintf(
            __('The size of the source code (%1$s) is bigger than the maximum allowed (%2$s).', 'trial-lucas-bustamante'),
            $size,
            $max_size
        ));
    }
}
