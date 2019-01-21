<?php

namespace VSPW;

class Admin
{
    /** @var string Slug of this admin page. */
    private $slug = 'very-simple-password';

    /**
     * @var \sapLibrary_2_1_1 $sap
     *
     * @see https://github.com/NateWr/simple-admin-pages
     */
    protected $sap;

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        require_once(VSPW_PATH . '/vendor/natewr/simple-admin-pages/simple-admin-pages.php');
    }

    /**
     * Runs on admin_init hook
     */
    public function register()
    {
        $this->sap = sap_initialize_library(
            [
                'version' => '2.1.2', // Version of the library
                'lib_url' => VSPW_URL . '/vendor/natewr/simple-admin-pages/', // URL path to sap library
            ]
        );

        $this->registerPage();
        $this->registerTabs();
        $this->registerGeneralSettingsSection();
        $this->registerCustomizationSection();
        $this->registerRequestPasswordSection();
        $this->registerBruteForceSection();
        $this->registerDomainConfigurationSection();
        $this->registerForceReauthSection();

        // Allow third-party addons to hook into your settings page
        $this->sap = apply_filters('vspw_page_setup', $this->sap);

        // Register all admin pages and settings with WordPress
        $this->sap->add_admin_menus();
    }

    /**
     * Registers the settings page for the plugin.
     */
    private function registerPage()
    {
        $this->sap->add_page(
            'options', // Admin menu which this page should be added to
            [
                'id'			=> $this->slug,
                'title'			=> __('Very Simple Password', 'very-simple-password'),
                'menu_title'	=> __('Very Simple Password', 'very-simple-password'),
                'description'	=> '',
                'capability'	=> 'manage_options',
                'default_tab'   => 'basic-settings',
            ]
        );
    }

    /**
     * Register the tabs for better organization and navigation.
     */
    private function registerTabs()
    {
        // Basic settings tab
        $this->sap->add_section(
            $this->slug,
            [
                'id'            => 'basic-settings',
                'title'         => __('Basic Settings', 'very-simple-password'),
                'description'   => '',
                'is_tab'		=> true,
            ]
        );

        // Advanced settings tab
        $this->sap->add_section(
            $this->slug,
            [
                'id'            => 'advanced-settings',
                'title'         => __('Advanced Settings', 'very-simple-password'),
                'description'   => '',
                'is_tab'		=> true,
            ]
        );
    }

    /**
     * Registers the "General Settings" section and it's fields.
     */
    private function registerGeneralSettingsSection()
    {
        $section = 'general-settings';

        // "General Settings" section
        $this->sap->add_section(
            $this->slug,
            [
                'id'			=> $section,
                'title'			=> __( 'General Settings', 'very-simple-password' ),
                'tab'           => 'basic-settings',
            ]
        );

        // Enable/Disable field
        $this->sap->add_setting(
            $this->slug,
            $section,
            'select',
            [
                'id'			=> 'vspw_enable',
                'title'			=> __( 'Enable/Disable', 'very-simple-password' ),
                'description'	=> __( 'Enables or disables this password protection.', 'very-simple-password' ),
                'options'		=> [
                    'enabled' 	=> __( 'Enabled', 'very-simple-password' ),
                    'disabled' 	=> __( 'Disabled', 'very-simple-password' ),
                ]
            ]
        );

        // Password field
        $this->sap->add_setting(
            $this->slug,
            $section,
            'text',
            [
                'id'			=> 'vspw_password',
                'title'			=> __( 'Define the password:', 'very-simple-password' ),
                'description'	=> __( 'This is the password that will be required to view your website.', 'very-simple-password' ),
            ]
        );

        // Time to store field
        $this->sap->add_setting(
            $this->slug,
            $section,
            'select',
            [
                'id'			=> 'vspw_time_to_store',
                'title'			=> __( 'Store the password for how long:', 'very-simple-password' ),
                'description'	=> __( 'After this period, user will have to insert the password again.', 'very-simple-password' ),
                'options'		=> [
                    'forever' 	=> __( 'Forever', 'very-simple-password' ),
                    '1' 	=> __( '1 Day', 'very-simple-password' ),
                    '7' 	=> __( '7 Days', 'very-simple-password' ),
                    '15' 	=> __( '15 Days', 'very-simple-password' ),
                    '30' 	=> __( '30 Days', 'very-simple-password' ),
                    '90' 	=> __( '90 Days', 'very-simple-password' ),
                    'session' 	=> __( 'Just for the session', 'very-simple-password' ),
                ]
            ]
        );
    }

    /**
     * Registers the "Customization" section and it's fields.
     */
    private function registerCustomizationSection()
    {
        $section = 'customization';

        // "Customization" section
        $this->sap->add_section(
            $this->slug,
            [
                'id'			=> $section,
                'title'			=> __( 'Customization', 'very-simple-password' ),
                'description'	=> '',
                'tab'           => 'basic-settings',
            ]
        );

        // Logo field
        $this->sap->add_setting(
            $this->slug,
            $section,
            'image',
            [
                'id'			=> 'vspw_logo',
                'title'			=> __( 'Logo:', 'very-simple-password' ),
                'description'	=> '',
                'strings'       => [
                    'add_image'     => __( 'Add Logo Image', 'very-simple-password' ),
                    'change_image'  => __( 'Change Logo Image', 'very-simple-password' ),
                    'remove_image'  => __( 'Remove Logo Image', 'very-simple-password' ),
                ]
            ]
        );

        // Background field
        $this->sap->add_setting(
            $this->slug,
            $section,
            'image',
            [
                'id'			=> 'vspw_background',
                'title'			=> __( 'Background:', 'very-simple-password' ),
                'description'	=> '',
                'strings'       => [
                    'add_image'     => __( 'Add Background Image', 'very-simple-password' ),
                    'change_image'  => __( 'Change Background Image', 'very-simple-password' ),
                    'remove_image'  => __( 'Remove Background Image', 'very-simple-password' ),
                ]
            ]
        );
    }

    /**
     * Registers the "Request Password" section and it's fields.
     */
    private function registerRequestPasswordSection()
    {
        $section = 'request-password';

        // "Request Password" section
        $this->sap->add_section(
            $this->slug,
            [
                'id'          => $section,
                'title'       => __('Request Password', 'very-simple-password'),
                'description' => '',
                'tab'         => 'advanced-settings',
            ]
        );

        // Enable/Disable Request Password feature
        $this->sap->add_setting(
            $this->slug,
            $section,
            'select',
            [
                'id'			=> 'vspw_request_password',
                'title'			=> __( 'Enable/Disable', 'very-simple-password' ),
                'description'	=> __( 'Show your email on the login page, in case a visitor needs the password.', 'very-simple-password' ),
                'options'		=> [
                    'enabled' 	=> __( 'Enabled', 'very-simple-password' ),
                    'disabled' 	=> __( 'Disabled', 'very-simple-password' ),
                ]
            ]
        );

        // Email to request password
        $this->sap->add_setting(
            $this->slug,
            $section,
            'text',
            [
                'id'			=> 'vspw_request_password_email',
                'title'			=> __( 'E-mail to request password:', 'very-simple-password' ),
                'description'	=> __( '(only visible if "Allow Visitor to Request Password" is enabled)', 'very-simple-password' ),
            ]
        );
    }

    /**
     * Registers the "Brute Force Protection" section and it's fields.
     */
    private function registerBruteForceSection()
    {
        $section = 'brute-force';

        // "Request Password" section
        $this->sap->add_section(
            $this->slug,
            [
                'id'          => $section,
                'title'       => __('Brute Force Protection', 'very-simple-password'),
                'description' => '',
                'tab'         => 'advanced-settings',
            ]
        );

        // Failed Login Tries
        $this->sap->add_setting(
            $this->slug,
            $section,
            'number',
            [
                'id'			=> 'vspw_brute_force_tries',
                'title'			=> __( 'Failed login tries:', 'very-simple-password' ),
                'description'	=> __( 'Default: 5', 'very-simple-password' ),
	            'sanitize_callback' => 'intval'
            ]
        );

        // Interval in seconds
        $this->sap->add_setting(
            $this->slug,
            $section,
            'number',
            [
                'id'			=> 'vspw_brute_force_interval',
                'title'			=> __( 'Interval in seconds:', 'very-simple-password' ),
                'description'	=> __( 'If the visitor have 5 tries and the interval is 300 seconds, he can get the password wrong 5 times every 5 minutes.', 'very-simple-password' ),
            ]
        );
    }

    /**
     * Registers the "Domain Configuration" section and it's fields.
     */
    private function registerDomainConfigurationSection()
    {
        $section = 'domain-configuration';

        // "Request Password" section
        $this->sap->add_section(
            $this->slug,
            [
                'id' => $section,
                'title' => __('Domain Configuration', 'very-simple-password'),
                'description' => '',
                'tab'           => 'advanced-settings',
            ]
        );

        // The domain
        $this->sap->add_setting(
            $this->slug,
            $section,
            'text',
            [
                'id'			=> 'vpsw_domain',
                'title'			=> __( 'Your domain. It must start with a dot:', 'very-simple-password' ),
                'description'	=> __( 'This is the domain we will set on the authentication cookie on the visitor browser. You don\'t have to change this field in most cases, but due to PHP limitations, websites with new TLDs such as .tech, or websites that run under subdomains such as blog.domain.com, might need to manually tweak this field. If your domain is www.domain.tech you should set this field to .domain.tech, with the dot at the beginning', 'very-simple-password' ),
            ]
        );
    }

    /**
     * Registers the "Domain Configuration" section and it's fields.
     */
    private function registerForceReauthSection()
    {
        $section = 'force-reauth';

        // "Request Password" section
        $this->sap->add_section(
            $this->slug,
            [
                'id'          => $section,
                'title'       => __('Force Reauth', 'very-simple-password'),
                'description' => '',
                'tab'         => 'advanced-settings',
            ]
        );

        // The domain
        $this->sap->add_setting(
            $this->slug,
            $section,
            'toggle',
            [
                'id'			=> 'vpsw_force_reauth',
                'title'			=> __( 'Force reauth?', 'very-simple-password' ),
                'description'	=> '',
                'label'         => __( 'Requests all users to enter the password again.', 'very-simple-password' ),
            ]
        );
    }
}