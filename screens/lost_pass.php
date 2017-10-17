<div id="login">
	<?php
		global $affLoginError;

		$affPage = AffiliatesManagement::getAffiliatesPage();

		$link = get_page_link($affPage);

		if( $affLoginError )
			echo "<p class='message'>".$affLoginError."</p>";
		else
			echo "<p class='message'>".__("Please enter your email address. You will receive a link to create a new password via email",'another-custom-login')."</p>";
	?>
	<form name="loginform" id="loginform" action="<?php echo $link; ?>" method="post">
		<input type="hidden" name="affiliate_action" value="do_lost_pass">
		<p class="login-username">
			<label for="user_login">Email Address</label>
			<input name="user_login" id="user_login" class="input" value="" size="20" type="text">
		</p>

		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="Get A New Password" type="submit">
		</p>
	</form>
	<p id="backtoblog">
		<a href="<?php echo $link; ?>">← Back to Affiliates Login</a>
	</p>
</div>
