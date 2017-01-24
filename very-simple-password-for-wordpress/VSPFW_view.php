<div id="vspfw">
	<div id="vspfw-logo">
		<?php
			// Sets logo
			if (get_option('vspfw_logo_url') != "") {
				echo '<img src="'.esc_url(get_option('vspfw_logo_url')).'" style="margin:20px 0 30px 0;"><br>';
			}
		?>
	</div>

	<div id="vspfw-enter-password-string"><?php echo esc_attr(get_option('vspfw_enter_password_string')); ?></div>
	<form method="POST">
		<input type="password" autofocus name="vspfw_password"><br><br>
		<input type="submit" value="<?php echo esc_attr(get_option('vspfw_submit')); ?>">
	</form>
	<?php if (get_option('vspfw_show_contact_info') == 'true') { ?>
		<div id="vspfw-request-password">
			<?php echo esc_html(get_option('vspfw_need_the_password_string'));?><br>
			<?php echo esc_attr(get_option('vspfw_contact_email'));?>
		</div>
	<?php } ?>
</div>