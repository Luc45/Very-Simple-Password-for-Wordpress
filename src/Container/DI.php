<?php

namespace VSPW\Container;

use tad_DI52_Container;
use VSPW\Admin;
use VSPW\Interceptor;
use VSPW\Password;
use VSPW\Protectors\FeedProtector;
use VSPW\Protectors\RESTProtector;
use VSPW\Protectors\TemplateProtector;
use VSPW\Services\PluginActivationService;
use VSPW\Services\PluginDeactivationService;
use VSPW\REST\V1\WebsitesRESTController;
use VSPW\VSPW;

class DI
{

    /**
     * The DI container instance, granted to be unique.
     *
     * @var \tad_DI52_Container
     */
    protected static $diContainer;

    /**
     * A proxy method to return the built and unique container instance or build it if not built yet.
     *
     * @return \tad_DI52_Container
     */
    protected static function container()
    {
        if (static::$diContainer === null) {
            static::$diContainer = new tad_DI52_Container();
        }

        return static::$diContainer;
    }

    /**
     * Registers an implementation as singleton in the app.
     *
     * @param string $class The class name or slug; using a slug will prevent reflection-based
     *                      dependency resolution from working: use a class preferably.
     * @param callable|string|object $implementation A callable, closure, class name or object.
     */
    public static function singleton($class, $implementation)
    {
        static::container()->singleton(...func_get_args());
    }

    /**
     * Registers an implementation in the app.
     *
     * @param string $class The class name or slug; using a slug will prevent reflection-based
     *                      dependency resolution from working: use a class preferably.
     * @param callable|string|object $implementation A callable, closure, class name or object.
     */
    public static function bind($class, $implementation)
    {
        static::container()->bind(...func_get_args());
    }

    /**
     * Builds and returns the implementation bound to a class.
     *
     * @param string $class The class or slug to return the implementation of.
     *
     * @return mixed|null The implementation bound to the class or slug.
     */
    public static function make($class)
    {
        return static::container()->make($class);
    }

    /**
     * Proxy method to redirect any call made on a method not explicitly implemented by this
     * class to the container.

     *
     * @param string $name The method name.
     * @param array $args An array of arguments for the method call.
     *
     * @return mixed The result of the call as returned from the container.
     */
    public static function __callStatic($name, array $args = [])
    {
        return call_user_func_array([ static::container(), $name ], $args);
    }

    /**
     * Registers the bindings for the DI container
     */
    public static function registerBindings()
    {
        // Core
        DI::singleton(VSPW::class, VSPW::class);
        DI::singleton(Interceptor::class, Interceptor::class);
        DI::singleton(Password::class, Password::class);
        DI::singleton(Admin::class, Admin::class);

        // Services
        DI::singleton(PluginActivationService::class, PluginActivationService::class);
        DI::singleton(PluginDeactivationService::class, PluginDeactivationService::class);

        // Protectors
        DI::singleton(TemplateProtector::class, TemplateProtector::class);
        DI::singleton(RESTProtector::class, RESTProtector::class);
        DI::singleton(FeedProtector::class, FeedProtector::class);

        // Rest - V1
        DI::singleton(WebsitesRESTController::class, WebsitesRESTController::class);
    }
}
