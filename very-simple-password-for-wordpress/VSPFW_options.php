<?php
function very_simple_password_for_wordpress() {
	if (current_user_can('manage_options')) {?>
	<div class="wrap">
		<h1>Very Simple Password for Wordpress</h1>

		<?php

		if (get_option('vspfw_force_reauth_message') != "") {
			echo '<div id="vspfw_force_reauth_message">'.get_option('vspfw_force_reauth_message').'</div>';
		}

		?>

		<form method="post" action="options.php">
			<?php
				// Get and display fields and nonce
				settings_fields( 'VSPFW-option-group' );
				?>
			    <table class="form-table">

			        <tr valign="top">
			        	<th scope="row">General Settings:</th>
			        	<td>
			        		<div class="vspfw-option-group">
			        			<h2>General Settings</h2>
			        			<div class="vspfw-option">
			        				<label for="vspfw_enabled">Enable/Disable this password protection:</label>
						        	<select name="vspfw_enabled">
						        		<option value="enabled" <?=(get_option('vspfw_enabled') == 'enabled')? 'selected' : ''?>>Enabled</option>
						        		<option value="disabled" <?=(get_option('vspfw_enabled') == 'disabled')? 'selected' : ''?>>Disabled</option>
						        	</select>
			        			</div>
			        			<div class="vspfw-option">
			        				<label for="vspfw_enabled">Define the Password:</label>
						        	<input type="text" name="vspfw_password" value="<?php echo esc_attr( get_option('vspfw_password') ); ?>" style="min-width:200px;" />
			        				<div class="vspfw-instructions">The password to view your website.</div>
			        			</div>
			        			<div class="vspfw-option">
			        				<label for="vspfw_enabled">Store the password for how long:</label>
						        	<select name="vspfw_days">
						        		<option value="3650" <?=(get_option('vspfw_days') == '3650')? 'selected' : ''?>>Forever</option>
						        		<option value="1" <?=(get_option('vspfw_days') == '1')? 'selected' : ''?>>1 day</option>
						        		<option value="7" <?=(get_option('vspfw_days') == '7')? 'selected' : ''?>>7 days</option>
						        		<option value="15" <?=(get_option('vspfw_days') == '15')? 'selected' : ''?>>15 days</option>
						        		<option value="30" <?=(get_option('vspfw_days') == '30')? 'selected' : ''?>>30 days</option>
						        		<option value="90" <?=(get_option('vspfw_days') == '90')? 'selected' : ''?>>90 days</option>
						        	</select>
			        				<div class="vspfw-instructions">After this period, user will have to insert the password again.</div>
			        			</div>
			        		</div>
			        		<div class="vspfw-option-group">
			        			<h2>Allow Request Password?</h2>
			        			<div class="vspfw-option">
			        				<label for="vspfw_show_contact_info">Allow visitor to request the password?</label>
						        	<select name="vspfw_show_contact_info">
						        		<option value="true" <?=(get_option('vspfw_show_contact_info') == 'true')? 'selected' : ''?>>Enabled</option>
						        		<option value="false" <?=(get_option('vspfw_show_contact_info') == 'false')? 'selected' : ''?>>Disabled</option>
						        	</select>
			        				<div class="vspfw-instructions">Show your email on the login page, in case a visitor needs the password.</div>
			        			</div>
			        			<div class="vspfw-option">
			        				<label for="vspfw_contact_email">Email to Request Password:</label>
						        	<input type="text" name="vspfw_contact_email" value="<?php echo esc_attr( get_option('vspfw_contact_email') ); ?>" style="min-width:200px;" />
			        				<div class="vspfw-instructions">(only visible if "Allow Visitor to Request Password" is enabled)</div>
			        			</div>
			        		</div>
			        		<div class="vspfw-option-group">
			        			<h2>Brute Force Protection</h2>
			        			<div class="vspfw-option">
			        				<label for="vspfw_brute_force_protection_tries">How many tries does the visitor have?</label>
						        	<input type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" name="vspfw_brute_force_protection_tries" value="<?php echo esc_attr( get_option('vspfw_brute_force_protection_tries') ); ?>" style="min-width:200px;" />
						        	<div class="vspfw-instructions">(Default: 5)</div>
			        			</div>
			        			<div class="vspfw-option">
			        				<label for="vspfw_brute_force_protection_interval">Interval in seconds to prevent Brute Force Attacks:</label>
						        	<input type="text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" name="vspfw_brute_force_protection_interval" value="<?php echo esc_attr( get_option('vspfw_brute_force_protection_interval') ); ?>" style="min-width:200px;" />
			        				<div class="vspfw-instructions">(If the visitor have 5 tries and the interval is 300 seconds, he can get the password wrong 5 times every 5 minutes.)</div>
			        			</div>
			        		</div>
				        	<div class="vspfw-option-group">
					        	<h2>Domain</h2>
					        	<div class="vspfw-option">
					        		<label for="vspfw_website_domain">Your domain. <strong>It must start with a dot:</strong></label>
					        		<input type="text" name="vspfw_website_domain" value="<?php echo esc_attr( get_option('vspfw_website_domain') ); ?>" style="min-width:200px;"/>
					        		<div class="vspfw-instructions">(This is the domain we will set on the authentication cookie on the visitor browser. You don't have to change this field in most cases, but due to PHP limitations, websites with new TLDs such as .tech, or websites that run under subdomains such as blog.domain.com, might need to manually tweak this field. If your domain is <span style="color:#FF0000">www.domain.tech</span> you should set this field to <span style="color:#FF0000">.domain.tech</span>, with the dot at the beginning)</div>
					        	</div>
				        	</div>
			        	</td>
			        </tr>
			        
			        <tr valign="top">
			        <th scope="row">Customizations:</th>
			        <td>
			        	<div class="vspfw-option-group">
				        	<h2>Branding</h2>
				        	<div class="vspfw-option">
				        		<label for="vspfw_logo_url">Logo:</label>
				        		<div class="image-preview"><img src="<?php echo esc_attr( get_option('vspfw_logo_url') ); ?>"></div>
				        		<input type="text" name="vspfw_logo_url" class="image_field" value="<?php echo esc_attr( get_option('vspfw_logo_url') ); ?>" style="min-width:400px;"/>
				        		 <input type="button" name="upload-btn" class="upload_media_button" class="button-secondary" value="Upload Image">
				        	</div>
				        	<div class="vspfw-option">
				        		<label for="vspfw_background_image">Background:</label>
				        		<div class="image-preview"><img src="<?php echo esc_attr( get_option('vspfw_background_image') ); ?>"></div>
				        		<input type="text" name="vspfw_background_image" class="image_field" value="<?php echo esc_attr( get_option('vspfw_background_image') ); ?>" style="min-width:400px;"/>
				        		<input type="button" name="upload-btn" class="upload_media_button" class="button-secondary" value="Upload Image">
				        	</div>
			        	</div>
			        	<div class="vspfw-option-group">
			        		<h2>Translations</h2>
				        	<div class="vspfw-option">
				        		<label for="vspfw_enter_password_string">Translation for "Please, enter the password":</label>
				        		<input type="text" name="vspfw_enter_password_string" value="<?php echo esc_attr( get_option('vspfw_enter_password_string') ); ?>" style="min-width:400px;" />
				        	</div>
				        	<div class="vspfw-option">
				        		<label for="vspfw_submit">Translation for "Submit":</label>
				        		<input type="text" name="vspfw_submit" value="<?php echo esc_attr( get_option('vspfw_submit') ); ?>" style="min-width:400px;"/>
				        	</div>
				        	<div class="vspfw-option">
				        		<label for="vspfw_wrong_password">Translation for "Wrong Password":</label>
				        		<input type="text" name="vspfw_wrong_password" value="<?php echo esc_attr( get_option('vspfw_wrong_password') ); ?>" style="min-width:400px;"/>
				        	</div>
				        	<div class="vspfw-option">
				        		<label for="vspfw_need_the_password_string">Translation for "Need the password?":</label>
				        		<input type="text" name="vspfw_need_the_password_string" value="<?php echo esc_attr( get_option('vspfw_need_the_password_string') ); ?>" style="min-width:400px;"/>
				        		<div class="vspfw-instructions">(only visible if "Allow Visitor to Request Password" is enabled)</div>
				        	</div>
				        	<div class="vspfw-option">
				        		<label for="vspfw_brute_force_protection_message">Translation for Brute Force Protection triggered:</label>
				        		<input type="text" name="vspfw_brute_force_protection_message" value="<?php echo esc_attr( get_option('vspfw_brute_force_protection_message') ); ?>" style="min-width:400px;"/>
				        	</div>
			        	</div>
			        </td>
			        </tr>
			        <tr valign="top">
			        <th scope="row">Force reauth:</th>
			        <td>
			        	<div class="vspfw-option-group">
			        		<h2>Force Reauth</h2>
				        	<div class="vspfw-option">
				        		<input type="checkbox" name="vspfw_force_reauth" value="force_reauth"></input>
				        		<div class="vspfw-instructions">(Requests all users to enter password again)</div>
				        	</div>
			        	</div>
			        </td>
			        </tr>
			    </table>
			    <div id="vspfw-further-customization">For further customizations, edit file <span style="font-style:italic;">/wp-content/plugins/very-simple-password-for-wordpress/VSPFW_view.php</span> directly.</div>
			    <?php
				// Submit button
				submit_button();
			?>
		</form>

	</div>
<?php } // /currentusercan
}// /function ?>