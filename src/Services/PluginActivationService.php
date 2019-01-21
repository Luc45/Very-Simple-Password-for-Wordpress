<?php

namespace VSPW\Services;

/**
 * Class PluginActivationService
 *
 * Provides functionalities at plugin activation
 *
 * @package DI\Services
 */
class PluginActivationService
{
    /**
     * This method runs when the plugin is activated.
     */
    public function trigger()
    {
        update_option('vspw_password', 'javali');
    }
}
