<form class="settings" name="frmDetails" id="frmDetails" method="POST" novalidate="novalidate">
	<input type="hidden" name="affiliate_action" value="do_save_details">
	<div class="row">
		<label for="user_login">Your email</label>
		<input type="email" id="user_login" name="user_login"  placeholder="Ex. John@mail.com" value="<?php echo $user->email(); ?>">
	</div>
	<div class="row">
		<label for="full_name">Your full name</label>
		<input type="text" id="full_name" name="full_name"  placeholder="Ex. John Smith" value="<?php echo $user->fullname(); ?>">
	</div>
	<div class="row">
		<label for="full_name">Website URL</label>
		<input type="text" id="site_url" name="site_url"  placeholder="Ex. http://www.myblog.com" value="<?php echo $user->website(); ?>">
	</div>
	<div class="row">
		<label for="phone">Your phone</label>
		<input type="phone" name="phone" id="phone" placeholder="Ex. 818 222 33 44" value="<?php echo $user->phone(); ?>">
	</div>
	<div class="row">
		<label for="skype">Skype User ID</label>
		<input type="text" name="skype" id="skype" placeholder="Ex. mySkypeUser" value="<?php echo $user->skype(); ?>">
	</div>
	<div class="row">
		<label for="user_pass">Password</label>
		<input type="text" name="user_pass" id="user_pass" placeholder="Leave empty to keep your current password">
	</div>
	<div style="margin-top:20px;">
		<input type="submit" class="button primary" value="Save Details">
	</div>
</form>
