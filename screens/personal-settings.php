<form class="settings" name="frmDetails" id="frmDetails" method="POST" novalidate="novalidate">
	<input type="hidden" name="affiliate_action" value="do_save_details">
	<div class="form-group">
		<label for="user_login" class="form-field">Your email</label>
		<input type="email" class="form-control" id="user_login" name="user_login"  placeholder="Ex. John@mail.com" value="<?php echo $user->email(); ?>">
	</div>
	<div class="form-group">
		<label for="full_name" class="form-field">Your full name</label>
		<input type="text" class="form-control" id="full_name" name="full_name"  placeholder="Ex. John Smith" value="<?php echo $user->fullname(); ?>">
	</div>
	<div class="form-group">
		<label for="full_name" class="form-field">Website URL</label>
		<input type="text" class="form-control" id="site_url" name="site_url"  placeholder="Ex. http://www.myblog.com" value="<?php echo $user->website(); ?>">
	</div>
	<div class="form-group">
		<label for="phone" class="form-field">Your phone</label>
		<input type="phone" class="form-control" name="phone" id="phone" placeholder="Ex. 818 222 33 44" value="<?php echo $user->phone(); ?>">
	</div>
	<div class="form-group">
		<label for="skype" class="form-field">Skype User ID</label>
		<input type="text" class="form-control" name="skype" id="skype" placeholder="Ex. mySkypeUser" value="<?php echo $user->skype(); ?>">
	</div>
	<div class="form-group">
		<label for="user_pass" class="form-field">Change your password</label>
		<input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="Leave empty to keep your current password">
	</div>
	<div style="margin-top:20px;">
		<input type="submit" class="button primary" value="Save Details">
	</div>
</form>
