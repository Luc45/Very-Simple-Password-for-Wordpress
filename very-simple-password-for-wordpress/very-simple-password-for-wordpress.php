<?php
/*
Plugin Name: Very Simple Password for Wordpress
Plugin URI:  https://developer.wordpress.org/plugins/very-simple-password-for-wordpress/
Description: This adds a simple password protection for wordpress.
Version:     1.4
Author:      Lucas Bustamante
Author URI:  https://www.lucasbustamante.com.br
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if (is_admin()){

	// Create database
	global $VSPFW_db_version;
	$VSPFW_db_version = '1.4';

	function VSPFW_install() {
		global $wpdb;
		global $VSPFW_db_version;

		$table_name = $wpdb->prefix . 'vspfw';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			unique_key varchar(32) DEFAULT '' NOT NULL,
			ip_address varchar(45) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'VSPFW_db_version', $VSPFW_db_version );

		// In case we need to change database structure on future versions
		$installed_ver = get_option( "VSPFW_db_version" );

		if ( $installed_ver != $VSPFW_db_version ) {

			$table_name = $wpdb->prefix . 'vspfw';

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				unique_key varchar(32) DEFAULT '' NOT NULL,
				ip_address varchar(45) DEFAULT '' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			update_option( "VSPFW_db_version", $VSPFW_db_version );
		}

	}
	register_activation_hook( __FILE__, 'VSPFW_install' );


	// Add Options page
	require('VSPFW_options.php');

	// Add VSPW under Options menu on Admin panel
	function VSPFW_add_menu() {
		add_submenu_page('options-general.php','Very Simple Password for Wordpress','Very Simple Password for Wordpress','manage_options', 'very_simple_password_for_wordpress', 'very_simple_password_for_wordpress');
	}
	add_action('admin_menu', 'VSPFW_add_menu');

	// Add "Settings" link to plugins page
	function VSPFW_add_action_links ( $links ) {
	 $mylinks = array(
	 	'<a href="' . admin_url( 'options-general.php?page=very_simple_password_for_wordpress' ) . '">Settings</a>',
	 );
	return array_merge( $links, $mylinks );
	}
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'VSPFW_add_action_links' );

	// Register configurable options
	function VSPFW_register_settings() {
	  register_setting( 'VSPFW-option-group', 'vspfw_background_image', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_days', 'intval');
	  register_setting( 'VSPFW-option-group', 'vspfw_enter_password_string', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_logo_url', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_submit', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_password', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_enabled', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_wrong_password', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_default_logo_changed_once', 'boolval');
	  register_setting( 'VSPFW-option-group', 'vspfw_default_background_changed_once', 'boolval');
	  register_setting( 'VSPFW-option-group', 'vspfw_show_contact_info', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_contact_email_changed_once', 'boolval');
	  register_setting( 'VSPFW-option-group', 'vspfw_contact_email', 'strval');
	  register_setting( 'VSPFW-option-group', 'vspfw_need_the_password_string', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_allow_request_password', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_website_domain', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_force_reauth', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_force_reauth_message', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_brute_force_protection_message', 'strval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_brute_force_protection_tries', 'intval' );
	  register_setting( 'VSPFW-option-group', 'vspfw_brute_force_protection_interval', 'intval' );
	}
	add_action('admin_init', 'VSPFW_register_settings' );

	// Sets default values
	
	if (get_option('vspfw_background_image') == "" && get_option('vspfw_default_background_changed_once') == false) {
		update_option('vspfw_background_image', plugins_url('/images/bg-default.jpg', __FILE__));
		update_option('vspfw_default_background_changed_once', true);
	}
	if (get_option('vspfw_default_background_changed_once') == "") {
		update_option('vspfw_default_background_changed_once', false);
	}
	if (get_option('vspfw_default_logo_changed_once') == "") {
		update_option('vspfw_default_logo_changed_once', false);
	}
	if (get_option('vspfw_logo_url') == "" && get_option('vspfw_default_logo_changed_once') == false) {
		update_option('vspfw_logo_url', plugins_url('/images/lock.png', __FILE__));
		update_option('vspfw_default_logo_changed_once', true);
	}
	if (get_option('vspfw_days') == "") {
		update_option('vspfw_days', '3650');
	}
	if (get_option('vspfw_enter_password_string') == "") {
		update_option('vspfw_enter_password_string', 'Please enter the password:');
	}
	if (get_option('vspfw_submit') == "") {
		update_option('vspfw_submit', 'Enter');
	}
	if (get_option('vspfw_website_domain') == "") {
		update_option('vspfw_website_domain', vspfw_filter_domain_to_use_on_cookie(get_bloginfo('url')));
	}
	if (get_option('vspfw_wrong_password') == "") {
		update_option('vspfw_wrong_password', 'Wrong password...');
	}
	if (get_option('vspfw_show_contact_info') == "") {
		update_option('vspfw_show_contact_info', 'false');
	}
	if (get_option('vspfw_contact_email_changed_once') == "") {
		update_option('vspfw_contact_email_changed_once', false);
	}
	if (get_option('vspfw_contact_email') == "" && get_option('vspfw_contact_email_changed_once') == false) {
		update_option('vspfw_contact_email', get_bloginfo('admin_email'));
		get_option('vspfw_contact_email_changed_once') == true;
	}
	if (get_option('vspfw_need_the_password_string') == "" ) {
		update_option('vspfw_need_the_password_string', 'Need the password?');
	}
	// Brute force config
	if (get_option('vspfw_brute_force_protection_tries') == "" ) {
		update_option('vspfw_brute_force_protection_tries', '5');
	}
	if (get_option('vspfw_brute_force_protection_interval') == "" ) {
		update_option('vspfw_brute_force_protection_interval', '300');
	}
	if (get_option('vspfw_brute_force_protection_message') == "") {
		update_option('vspfw_brute_force_protection_message', 'You have failed the password too many times. Please try again a few minutes...');
	}
	// Reset the reauth checkbox, so it doesn't reset all the time
	if (get_option('vspfw_users_reauth') != "" ) {
		update_option('vspfw_users_reauth', '');
	}
	// Reset the reauth message, so it doesn't appear all the time
	if (get_option('vspfw_force_reauth_message') != "" ) {
		update_option('vspfw_force_reauth_message', '');
	}

	// Reauth all users
	if (get_option('vspfw_force_reauth') == 'force_reauth') {
		global $wpdb;
		$table_name = $wpdb->prefix . 'vspfw';
		$delete = $wpdb->query('TRUNCATE TABLE `'.$table_name.'`');
		if ($delete) {
			update_option('vspfw_force_reauth_message', 'Succesfully delleted all cookies from the '.$table_name.' table. Now all visitors have to enter the password again.');
		} else {
			update_option('vspfw_force_reauth_message', 'Couldn\'t delete cookies from '.$table_name.' - you might want to do it manually.');
		}
		update_option('vspfw_force_reauth', '');
	}

}

// Enable media management on this plugin
function enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action("admin_enqueue_scripts", "enqueue_media_uploader", 10);

// Custom function to determine if this is the login page http://stackoverflow.com/questions/5266945/wordpress-how-detect-if-current-page-is-the-login-page
function VSPFW_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php'));
}

// Check if a cookie is in database and is still valid
function VSPFW_check_cookie_on_database($cookie) {
	if (strlen($cookie) == 32) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'vspfw';

		$results = $wpdb->get_results( 'SELECT * FROM '.$table_name.' WHERE unique_key = "'.$cookie.'"', OBJECT );

		if ($results) {
			return true;
		} 
	}
	return false;
}

// Filter domain to use on cookie
// Only called at the first time the plugin runs
function vspfw_filter_domain_to_use_on_cookie($domain) {
	// Removes http/s:// from domain
	$domain = str_replace("https://", '', $domain);
	$domain = str_replace("http://", '', $domain);

	// Replaces www. with . wich will work as a wilcard for cookies under that domain
	$domain = str_replace('www.','.', $domain);

	// Check if domain starts with dot, if not, inserts one
	if (substr($domain, 0, 1) != '.') {
		$domain = '.'.$domain;
	}

	return $domain;
}

// Don't ask for password on login or admin panel
function VSPFW_should_ask_password() {
	// We have plugin enabled and password set
	if ((get_option('vspfw_enabled') == "enabled") && (get_option('vspfw_password') != "")) {
		// Disable password on admin panel, login page and for admin users
		if (!is_admin() && !VSPFW_is_login_page() && !current_user_can('manage_options')) {
			if (!VSPFW_check_cookie_on_database($_COOKIE['vspfw_password_entered'])) {
				return true;
			}
		}
	}
}

// Load CSS if needed
function vspfw_css() {
	if (VSPFW_should_ask_password()) { 
	?>
	        <style>
				body {
					background:<?php echo (get_option('vspfw_background_image')==''?'#f1f1f1':'url('.esc_html(get_option('vspfw_background_image')).');') ?>;
				}
	            #vspfw {
					top:25%;
					left: 50%;
					transform: translate3d(-50%,-25%, 0);
					position: absolute;
				    background: #FFF;
				    border: 1px solid #e3e3e3;
				    border-radius: 5px;
				    text-align: center;
				    padding: 1em 3em;

	            }
				div#vspfw-request-password {
				    color: #6b6b6b;
				    position: absolute;
				    bottom: -50px;
				}
				#vspfw input[type="submit"] {
				    background: #3079ff;
				    padding: 7px 15px;
				    color: #FFF;
				    border: 0;
				    font-size: 17px;
				    cursor: pointer;
				}
				#vspfw input[type="submit"]:hover {
					background:#6ca0ff;
				}
				#vspfw input[type="password"] {
					padding:5px 10px;
				}
				div#vspfw-enter-password-string {
				    margin-bottom: 10px;
				}
				div#vspfw-request-password {
				    color: #6b6b6b;
				    bottom: -30px;
				    position: relative;
				    height: 0;
				}
	        </style>
	 <?php
	}
}
add_action('init', 'vspfw_css', 5);

// Load admin CSS
function vspfw_admin_css() {
	if (is_admin()) { 
	?>
        <style>
			.vspfw-option label {
			    display: block;
			    margin: 10px 0 2px 0;
			}
			.vspfw-option .image-preview img {
			    max-width: 200px;
			    max-height: 200px;
			    margin: 10px 0;
			}
			div#vspfw-further-customization {
				display:none;
			}
			.vspfw-option-group {
			    margin: 20px 0;
			}
			.vspfw-option-group h2 {
			    margin: 30px 0 0 0;
			}
			.vspfw-instructions {
			    display: inline-block;
			    margin-left: 10px;
			    max-width: 50%;
			    vertical-align: middle;
			    background: #ffffff;
			    padding: 5px 10px;
			    color: #000;
			    border-radius: 5px;
			    border: 1px solid #e3e3e3;
			    border-left: 5px solid #ff9438;
			}
			div#vspfw_force_reauth_message {
			    background: #FFF;
			    font-weight: bold;
			    padding: 10px;
			}
        </style>
	 <?php
	}
}
add_action('admin_enqueue_scripts', 'vspfw_admin_css', 5);

// Load admin JS
function vspfw_admin_js() {
	if (is_admin()) { 
		wp_enqueue_script('jquery');
	?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
		    $('.upload_media_button').click(function(e) {
		    	var botao = $(this);
		        e.preventDefault();
		        var image = wp.media({ 
		            title: 'Upload Image',
		            // mutiple: true if you want to upload multiple files at once
		            multiple: false
		        }).open()
		        .on('select', function(e){
		            // This will return the selected image from the Media Uploader, the result is an object
		            var uploaded_image = image.state().get('selection').first();
		            // We convert uploaded_image to a JSON object to make accessing it easier
		            // Output to the console uploaded_image
		            console.log(uploaded_image);
		            var image_url = uploaded_image.toJSON().url;
		            // Let's assign the url value to the input field
		            console.log(botao);
		            $(botao).siblings('.image-preview').find('img').attr('src', image_url);
		            $(botao).siblings('.image_field').val(image_url);
		        });
		    });
		});
		</script>
	 <?php
	}
}
add_action('admin_footer', 'vspfw_admin_js', 20);

// Load Javascript if needed
function vspfw_js() {
	if (VSPFW_should_ask_password()) { 
	?>
	        <script type="text/javascript"></script>
	 <?php
	}
}
// No need for javascript on front-end now
//add_action('init', 'vspfw_js', 6);

// Add md5 cookie to database
function VSPFW_add_md5_cookie_do_database($unique_key, $ip_address) {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'vspfw';
	
	$wpdb->insert(
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'unique_key' => $unique_key, 
			'ip_address' => $ip_address, 
		) 
	);
	if (is_int($wpdb->insert_id)) {
		return true;
	} else {
		return false;
	}
}

// Add failed login attempt to database, to prevent brute force attacks
function VSPFW_prevent_brute_force_add_try($ip_address) {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'vspfw';
	
	$wpdb->insert(
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'unique_key' => 'failed', 
			'ip_address' => $ip_address, 
		) 
	);
}

// Check brute force attack
function VSPFW_prevent_brute_force_check($ip_address) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'vspfw';
		$results = $wpdb->get_results( 'SELECT * FROM '.$table_name.' WHERE ip_address = "'.$ip_address.'" AND unique_key = "failed"', OBJECT );

		$failed_login_attempts_between_time_frame = 0;

		foreach ($results as $result) {
			// Add to counter every try in the last 120 seconds (or what the user configured)
			if ((current_time(timestamp) - strtotime($result->time)) < get_option('vspfw_brute_force_protection_interval')) {
				$failed_login_attempts_between_time_frame++;
			}
		} 
		// If user failed the login more than 5 times in 120 seconds, it blocks him
		if ($failed_login_attempts_between_time_frame > get_option('vspfw_brute_force_protection_tries')) {
			return false;
		} else {
			return true;
		}
}
add_action('init', 'VSPFW_prevent_brute_force_check');

// Check if user submitted password on the front-end
function VSPFW_auth_frontend_user() {
	$nonce=$_REQUEST['vspfw_user_entered_password_wpnonce'];
	if (VSPFW_should_ask_password() && wp_verify_nonce($nonce, 'vspfw_user_entered_password_wpnonce')) {
		if (isset($_POST['vspfw_password']) && !empty($_POST['vspfw_password'])) {
			$ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
			if (VSPFW_prevent_brute_force_check($ip_address)) {
				$pw = sanitize_text_field($_POST['vspfw_password']);
				if ($pw == get_option('vspfw_password')) {
						// Generate unique md5 to store as cookie
						$unique_key = md5(current_time(timestamp)+(rand(0,100)));
						if (!VSPFW_add_md5_cookie_do_database($unique_key, $ip_address)) {
							error_log('Warning from Very Simple Password for Wordpress: There was an error inserting the unique id to the database. You should review the plugin code or disable it.', 1, get_bloginfo('admin_email'));
							wp_die("Very Simple Password for Wordpress couldn't insert the UniqueID to the database. Please contact website owner.");
							exit;
						}
						// Set the cookie with the unique id for the period specified by the admin
						setcookie('vspfw_password_entered', esc_html($unique_key), strtotime( '+'.get_option('vspfw_days').' days'), '/', esc_html(get_option('vspfw_debug_domain')));
						// Refresh after setting cookie, because $_COOKIE is set on page load - http://stackoverflow.com/questions/3230133/accessing-cookie-immediately-after-setcookie
						//echo '<script type="text/javascript">window.location.reload(true);</script>';
						header('Refresh:0');
				} else {
					// Add this failed login to database to prevent anti-brute force attacks
					VSPFW_prevent_brute_force_add_try($ip_address);
					// Display wrong password
					echo '<div style="text-align:center;color: #a94442;background-color: #f2dede;border-color: #ebccd1; padding:15px;margin:20px;">'.get_option('vspfw_wrong_password').'</div>';
				}
			} else {
				// Display brute force protection
				echo '<div style="text-align:center;color: #a94442;background-color: #f2dede;border-color: #ebccd1; padding:15px;margin:20px;">'.get_option('vspfw_brute_force_protection_message').'</div>';
			}
		}
	}
}
add_action('init', 'VSPFW_auth_frontend_user', 40);

// Check if $_COOKIE is set. I know this is simple and not safe, but the idea behind this plugin is to provide real-life solution to a site you need to hide while you develop it, not secure rocket science blueprints.
function VSPFW_CheckPassword() {
	// Check if password is enabled and set, and if we should ask for password
	if (VSPFW_should_ask_password()) {
			// If he doesn't, asks for password and stops Wordpress
			require('VSPFW_view.php');
			exit();
		}
}
add_action('init', 'VSPFW_CheckPassword', 50);