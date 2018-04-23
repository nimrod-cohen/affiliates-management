<div id="login" class="afm_form_container">
	<?php

	$affPage = AffiliatesManagement::getAffiliatesPage();

	$link = get_page_link($affPage);

	if( $this->error )
		echo "<p class='message'>".$this->error."</p>";
	?>
	<form id="loginform" action="" method="POST">
		<input type="hidden" name="affiliate_action" value="do_login">
		<p class="login-username">
			<label for="user_login">Affiliate Email</label>
			<input name="log" id="user_login" class="input standard-width" value="" size="20" type="text">
		</p>
		<p class="login-password">
			<label for="user_pass">Password</label>
			<input name="pwd" id="user_pass" class="input standard-width" value="" size="20" type="password">
		</p>

		<p class="login-remember"><label><input name="rememberme" id="rememberme" value="forever" type="checkbox">Remember Me</label></p>
		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="Log in" type="submit">
		</p>
	</form>
	<p id="nav" class="login_options">
		<span class="lost_pass"><a href="<?php echo $link."?page=lost_pass"; ?>">Lost your password?</a></span>
		<span class="join_us"><a href="<?php echo $link."?page=join_us"; ?>">Join our affiliate program</a></span>
	</p>
</div>
