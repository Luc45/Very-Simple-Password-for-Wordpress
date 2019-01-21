<?php
/**
 * This is the password-form page. It must be completely isolated from the WordPress system, thus
 * everything we do here is manual.
 */

/** This file must only be called in the context of the VSPW plugin */
defined('VSPW_PATH') || die();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo __('Password Protected', 'very-simple-password') ?></title>
    <meta charset="UTF-8">
    <?php require_once( VSPW_PATH . '/assets/css/css.php'); ?>
</head>
<body>
    <div id="vspw">
        <div id="vspw-logo">
            <?php
            // Sets logo
            if (get_option('vspfw_logo_url') != "") {
                echo '<img src="'.esc_url(get_option('vspfw_logo_url')).'" style="margin:20px 0 30px 0;"><br>';
            }
            ?>
        </div>

        <div id="vspw-enter-password-string"><?php echo esc_attr(get_option('vspfw_enter_password_string')); ?></div>
        <form method="POST">
            <?php wp_nonce_field('vspfw_user_entered_password_wpnonce', 'vspfw_user_entered_password_wpnonce') ?>
            <input type="password" autofocus name="vspw_password"><br><br>
            <input type="submit" value="<?php echo __('Submit', 'very-simple-password'); ?>">
        </form>
        <?php if (get_option('vspfw_show_contact_info') == 'true') { ?>
            <div id="vspw-request-password">
                <?php echo sprintf(
                        __('Need the password? <br> %s', 'very-simple-password'),
                        get_bloginfo('admin_email')
                ); ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>