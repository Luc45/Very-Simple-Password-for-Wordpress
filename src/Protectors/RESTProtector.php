<?php

namespace VSPW\Protectors;

use VSPW\Protectors\Interfaces\ProtectorInterface;

class RESTProtector implements ProtectorInterface
{
    /**
     * Protects REST API from unauthenticated users.
     */
    public function protect()
    {
        if (apply_filters('vspw_protect_rest', true)) {
            add_action('rest_api_init', function () {
                wp_die(__('Unauthenticated REST Requests are blocked by Very Simple Password for WordPress plugin.', 'very-simple-password'));
            }, 1);
        }
    }
}