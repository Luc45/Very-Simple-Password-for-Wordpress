<?php

namespace VSPW\Services;

/**
 * Class PluginDeactivationService
 *
 * Resets some settings at plugin deactivation
 *
 * @package DI\Services
 */
class PluginDeactivationService
{
    /**
     * This method runs when the plugin is DE-ACTIVATED.
     */
    public function trigger()
    {
        delete_option('vspw_password');
    }
}
