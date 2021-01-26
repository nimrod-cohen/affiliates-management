<div class="page-content">
	<?php

	$affPage = AffiliatesManagement::getAffiliatesPage();

	$link = get_page_link($affPage);

	if( $this->error )
		echo "<p class='message error'>".$this->error."</p>";
	?>
	<form action="" method="POST">
		<input type="hidden" name="affiliate_action" value="do_login">
		<p class="row">
			<label for="user_login">Affiliate Email</label>
			<input name="log" id="user_login" class="input standard-width" value="" size="20" type="text">
		</p>
		<p class="row">
			<label for="user_pass">Password</label>
			<input name="pwd" id="user_pass" class="input standard-width" value="" size="20" type="password">
		</p>

		<p class="row">
			<label>
				<input name="rememberme" id="rememberme" value="forever" type="checkbox">Remember Me
			</label>
		</p>

		<p class="row">
			<input name="submit" id="submit" class="button primary full-width" value="Log in" type="submit">
		</p>
	</form>

	<p id="nav" class="login_options">
		<span class="lost_pass"><a href="<?php echo $link."?pg=lost_pass"; ?>">Lost your password?</a></span>&nbsp;&nbsp;|&nbsp;
		<span class="join_us"><a href="<?php echo $link."?pg=join_us"; ?>">Join our affiliate program</a></span>
	</p>
</div>
