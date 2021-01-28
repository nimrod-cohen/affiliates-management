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
<div class="page-content standard-width tabs">
	<h2 class="nav-tabs">
		<a class="nav-tab <?php echo $activePage == 'links' ? 'nav-tab-active' : ''; ?>" id="links-tab" href="#top#tab_links">Links</a>
		<a class="nav-tab <?php echo $activePage == 'leads' ? 'nav-tab-active' : ''; ?>" id="leads-tab" href="#top#tab_links">Leads</a>
		<a class="nav-tab <?php echo $activePage == 'pixel' ? 'nav-tab-active' : ''; ?>" id="pixel-tab" href="#top#tab_links">Pixel</a>
		<a class="nav-tab <?php echo $activePage == 'billing' ? 'nav-tab-active' : ''; ?>" id="billing-tab" href="#top#tab_billing">Billing</a>
			<a class="nav-tab <?php echo $activePage == 'creatives' ? 'nav-tab-active' : ''; ?>" id="creatives-tab" href="#top#tab_creatives">Banner Farm</a>
		<a class="nav-tab pull-right <?php echo $activePage == 'details' ? 'nav-tab-active' : ''; ?>" id="details-tab" href="#top#tab_details">Personal Details</a>
	</h2>
	<div id="tab-details" class="tab-body <?php echo $activePage == 'details' ? 'active' : ''; ?>">
		<?php include_once  "personal-settings.php"; ?>
	</div>
	<div id="tab-links" class="tab-body <?php echo $activePage == 'links' ? 'active' : ''; ?>">
		<?php include_once "links-table.php"; ?>
	</div>
	<div id="tab-leads" class="tab-body <?php echo $activePage == 'leads' ? 'active' : ''; ?>">
		<?php include_once "leads-table.php"; ?>
	</div>
	<div id="tab-pixel" class="tab-body <?php echo $activePage == 'pixel' ? 'active' : ''; ?>">
		<?php include_once "pixel.php"; ?>
	</div>
	<div id="tab-creatives" class="tab-body <?php echo $activePage == 'creatives' ? 'active' : ''; ?>">
		<?php include_once "creatives-table.php"; ?>
	</div>
	<div id="tab-billing" class="tab-body <?php echo $activePage == 'billing' ? 'active' : ''; ?>">
		<?php include_once "billing.php"; ?>
	</div>
</div>