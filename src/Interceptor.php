<?php

namespace VSPW;

use VSPW\Container\DI;
use VSPW\Protectors\FeedProtector;
use VSPW\Protectors\RESTProtector;
use VSPW\Protectors\TemplateProtector;

/**
 * Class Interceptor
 *
 * Determines whether a request must be intercepted or not.
 *
 * @package VSPW
 */
class Interceptor {
	/**
	 * @var string $skipReason
	 *
	 * State used primarily for testing purposes.
	 */
	public $skipReason;

    /** @var Password $password */
    protected $password;

    /**
     * Interceptor constructor.
     *
     * @param Password $password
     */
    public function __construct(Password $password)
    {
        $this->password = $password;
    }

    /**
     * Intercepts a request by showing the password screen or not.
     */
    public function intercept()
    {
        if ($this->shouldIntercept()) {
            DI::make(TemplateProtector::class)->protect();
            DI::make(RESTProtector::class)->protect();
            DI::make(FeedProtector::class)->protect();
            return true;
        }
        return false;
    }

    /**
     * Determines whether the request must be intercepted or not.
     *
     * @return bool
     */
    protected function shouldIntercept()
    {
        // Do not intercept if filter says so.
        if ($this->shouldSkipInterceptionFilter()) {
        	$this->skipReason = 'filter';
            return false;
        }

	    // Do not intercept if a password is not set.
	    if ( ! $this->password->hasPassword()) {
		    $this->skipReason = 'password';
		    return false;
	    }

        // Do not intercept users with a specific capability.
        if ($this->currentUserCanSkipInterception()) {
	        $this->skipReason = 'capability';
            return false;
        }

        // Do not intercept in login screen.
        if ($this->isLoginPage()) {
	        $this->skipReason = 'loginPage';
            return false;
        }

        // Do not intercept WP CLI requests.
        if ($this->shouldSkipCliInterception()) {
	        $this->skipReason = 'cli';
        	return false;
        }

        return true; // Intercept.
    }

    /**
     *
     * Gives the user a chance to skip the interception using filters.
     *
     * @return bool
     */
	protected function shouldSkipInterceptionFilter()
    {
        return (bool) apply_filters('vspw_should_skip_interception', false);
    }

    /**
     * Do not intercept users with a specific capability.
     *
     * @return bool
     */
	protected function currentUserCanSkipInterception()
    {
        $cap = apply_filters('vspw_capability_skips_interception', 'manage_options');

        return current_user_can($cap);
    }

    /**
     * Determines whether current request is in wp-login page.
     *
     * @link https://wordpress.stackexchange.com/a/237285/27278
     *
     * @return bool
     */
	protected function isLoginPage()
    {
    	$is_login_page = false;

	    $ABSPATH_MY = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, ABSPATH);

    	// Was wp-login.php or wp-register.php included during this execution?
    	if (
    		in_array($ABSPATH_MY . 'wp-login.php', get_included_files()) ||
	        in_array($ABSPATH_MY . 'wp-register.php', get_included_files())
	    ) {
		    $is_login_page = true;
	    }

	    // $GLOBALS['pagenow'] is equal to "wp-login.php"?
	    if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') {
		    $is_login_page = true;
	    }

	    // $_SERVER['PHP_SELF'] is equal to "/wp-login.php"?
	    if ($_SERVER['PHP_SELF'] == '/wp-login.php') {
		    $is_login_page = true;
	    }

	    // Allow filters for third-party/testing
	    $is_login_page = apply_filters('vspw_is_login_page', $is_login_page);

    	return $is_login_page;
    }

	/**
	 * Determins whether we should skip an interception if the request
	 * is coming from WP CLI. We skip by default, but allow users to override
	 * this behavior with filters.
	 *
	 * @return bool
	 */
    protected function shouldSkipCliInterception()
    {
    	// Check if this is a WP_CLI request.
    	$is_wpcli = defined("WP_CLI") && WP_CLI === true;

    	// Let's filter this constant for testing purposes.
    	$is_wpcli = (bool) apply_filters('vspw_is_wpcli', $is_wpcli);

    	if ($is_wpcli === true) {
    		return (bool) apply_filters('vspw_should_skip_wpcli_interception', true);
	    }
    	return false;
    }
}