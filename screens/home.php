<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 07/22/17
 * Time: 15:38
 */
	$activePage = "links";

	$user = AFMAffiliate::fromAffiliateId(get_current_user_id());
?>
<div class="tabs-wrapper">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php echo $activePage == 'links' ? 'nav-tab-active' : ''; ?>" id="links-tab" href="#top#tab_links">Links</a>
		<a class="nav-tab <?php echo $activePage == 'billing' ? 'nav-tab-active' : ''; ?>" id="billing-tab" href="#top#tab_billing">Billing</a>
			<a class="nav-tab <?php echo $activePage == 'creatives' ? 'nav-tab-active' : ''; ?>" id="creatives-tab" href="#top#tab_creatives">Banner Farm</a>
		<a class="nav-tab <?php echo $activePage == 'details' ? 'nav-tab-active' : ''; ?>" id="details-tab" href="#top#tab_details">Personal Details</a>
	</h2>
	<div id="tab-details" class="tab-view <?php echo $activePage == 'details' ? 'active' : ''; ?>">
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
				<input type="submit" class="form-button" value="Save Details">
			</div>
		</form>
	</div>
	<div id="tab-links" class="tab-view <?php echo $activePage == 'links' ? 'active' : ''; ?>">
		<?php include_once "links-table.php"; ?>
	</div>
	<div id="tab-creatives" class="tab-view <?php echo $activePage == 'creatives' ? 'active' : ''; ?>">
		<?php include_once "creatives-table.php"; ?>
	</div>
	<div id="tab-billing" class="tab-view <?php echo $activePage == 'billing' ? 'active' : ''; ?>">
		<?php include_once "billing.php"; ?>
	</div>
</div>