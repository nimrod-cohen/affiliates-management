<div class="page-content raise">
	<?php

	$affPage = AffiliatesManagement::getAffiliatesPage();

	$link = get_page_link($affPage);

	$fullName = isset($_POST["full_name"]) ? $_POST["full_name"] : "";
	$phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
	$skype = isset($_POST["skype"]) ? $_POST["skype"] : "";
	$siteUrl = isset($_POST["site_url"]) ? $_POST["site_url"] : "";
	$login = isset($_POST["user_login"]) ? $_POST["user_login"] : "";

	if( $this->error )
		echo "<p class='message'>".$this->error."</p>";
	?>
	<form id="join-form" action="" method="POST">
		<input type="hidden" name="affiliate_action" value="do_register">
		<p>
			<label for="full_name">Full Name <span class="mandatory">*</span></label>
			<input name="full_name" id="full_name" class="input full-width" value="<?php echo $fullName; ?>" type="text">
		</p>
		<p>
			<label for="phone">Phone <span class="mandatory">*</span></label>
			<input name="phone" id="phone" class="input full-width" value="<?php echo $phone; ?>" type="text">
		</p>
		<p>
			<label for="skype">Skype User</label>
			<input name="skype" id="skype" class="input full-width" value="<?php echo $skype; ?>" type="text">
		</p>
		<p>
			<label for="site_url">Website Url <span class="mandatory">*</span></label>
			<input name="site_url" id="site_url" class="input full-width" value="<?php echo $siteUrl; ?>" type="text">
		</p>
		<p>
			<label for="user_login">Affiliate Email <span class="mandatory">*</span></label>
			<input name="user_login" id="user_login" class="input full-width" value="<?php echo $login; ?>" type="text">
		</p>
		<p>
			<label for="user_pass">Desired Password <span class="mandatory">*</span></label>
			<input name="user_pass" id="user_pass" class="input full-width" value="" size="20" type="password">
		</p>
		<p>
			<label for="user_pass_2">Repeat Password <span class="mandatory">*</span></label>
			<input name="user_pass_2" id="user_pass_2" class="input full-width" value="" size="20" type="password">
		</p>
		<p class="login-submit">
			<input name="wp-submit" id="wp-submit" class="button-primary" value="Sign Up" type="submit">
		</p>
	</form>
	<p id="nav" class="login_options">
		<span class="login_page"><a href="<?php echo $link; ?>">← Back to Affiliates Login</a></span>
	</p>
</div>
