<?php
/*
Plugin Name: Very Simple Password for Wordpress
Plugin URI:  https://developer.wordpress.org/plugins/very-simple-password-for-wordpress/
Description: This adds a simple password protection for wordpress.
Version:     2.0
Author:      Lucas Bustamante
Author URI:  http://www.lucasbustamante.com.br
Requires PHP: 5.4
*/

use VSPW\Interceptor;
use VSPW\Admin;
use VSPW\Container\DI;
use VSPW\Services\PluginActivationService;
use VSPW\Services\PluginDeactivationService;

/** If this file is called directly, abort. */
defined('ABSPATH') || die();

/** Constants we will be using later on */
define('VSPW_PATH', plugin_dir_path(__FILE__));
define('VSPW_URL', plugin_dir_url(__FILE__));
define('VSPW_BASENAME', plugin_basename(__FILE__));

/** Composer Autoloader */
require_once(VSPW_PATH . '/vendor/autoload.php');

/** Initializes the Dependency Injection Container and registers bindings */
DI::registerBindings();

/** Runs on plugin activation/deactivation */
register_activation_hook(__FILE__, [DI::make(PluginActivationService::class), 'trigger']);
register_deactivation_hook(__FILE__, [DI::make(PluginDeactivationService::class), 'trigger']);

if (is_admin()) {
    /** Register the plugin's admin settings page */
    DI::make(Admin::class)->register();
} else {
    /** Outside the admin dashboard, assert if the plugin should intercept the request. */
    add_action('plugins_loaded', function() {
        DI::make(Interceptor::class)->intercept();
    });
}

