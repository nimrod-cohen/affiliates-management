<div class="page-content" >
	<?php
		$failed = true;
		$message = null;

		try {
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$email = $_REQUEST["user_login"];

				$aff = AFMAffiliate::fromAffiliateEmail($email);

				if(!$aff) throw new Exception("Could not find affiliate");
				$aff = $aff->user();
				if(!$aff) throw new Exception("Could not find affiliate");

				$key = get_password_reset_key($aff);

				if (is_wp_error($key)) throw new Exception("Cannot generate reset key");
			
				$siteName = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			
				$content = __('Someone has requested a password reset for the following account:')."\r\n\r\n";
				$content .= sprintf(__('Site Name: %s'), $siteName)."\r\n\r\n";
				$content .= sprintf(__( 'Username: %s' ), $aff->user_login)."\r\n\r\n";
				$content .= __('If this was a mistake, ignore this email and nothing will happen.')."\r\n\r\n";
				$content .= __('To reset your password, visit the following address:')."\r\n\r\n";
				$content .= site_url( "wp-login.php?action=rp&key=$key&login=".rawurlencode( $aff->user_login ),'login')."\r\n\r\n";

				$title = sprintf( __( '[%s] Password Reset' ), $siteName );
			
				$title = apply_filters( 'retrieve_password_title', $title, $aff->user_login, $aff );
				$content = apply_filters( 'retrieve_password_message', $content, $key, $aff->user_login, $aff );
			
				if(!wp_mail( $email, wp_specialchars_decode( $title ), $content)) throw new Exception("Failed to send password reset email");
				$message = "Password reset email sent";
				$failed = false;
			}
		} catch(Exception $ex) {
			$message = "Failed to send password reset email, please contact site admins";
			$failed = true;
		}

		$affPage = AffiliatesManagement::getAffiliatesPage();

		global $wp;
		$url = home_url( add_query_arg( ["pg" => "lost_pass"] ) );

		if($message)
			echo "<p class='message ".($failed ? "error" : "success")."'>".$message."</p>";
		else
			echo "<p class='message'>Please enter your email address.<br/> You will receive a link to create a new password via email</p>";
	?>
	<form action="<?php echo $url; ?>" method="post">
		<p class="row">
			<label for="user_login">Email Address</label>
			<input name="user_login" id="user_login" class="input" value="" size="20" type="text">
		</p>

		<p class="row">
			<input name="wp-submit" id="wp-submit" class="button primary full-width" value="Get A New Password" type="submit">
		</p>
	</form>
	<p id="backtoblog">
		<a href="<?php echo AffiliatesManagement::getAffiliatesPageUrl(); ?>">← Back to Affiliates Login</a>
	</p>
</div>
