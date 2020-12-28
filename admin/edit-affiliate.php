<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 08/14/17
 * Time: 13:20
 */
	$aff = AFMAffiliate::fromAffiliateId($_GET["id"]);

	$deal = $aff->deal();

	if(!$deal)
		$deal = AffiliatesManagement::defaultDeal();

	$activePage = "accounting";
?>
<?php if($showUpdated) { ?>
	<div id="message" class="updated <?php echo $updateResult ? "notice" : "error"; ?> is-dismissible">
		<p><strong><?php echo $updateResult ? "Details updated successfully" : "Failed to update details"; ?></strong></p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
<?php } ?>

<div class="wrap" id="profile-page">
	<div class="affiliate-heading">
		<p><a href="<?php echo admin_url("admin.php?page=affiliates-management"); ?>">← Back to Affiliates</a></p>
		<h1 STYLE="display: inline-block"><?php echo $aff->fullname(); ?> - details</h1>
		<a href="<?php echo admin_url("admin.php?page=affiliates-management&subpage=pay-affiliate&id=".$aff->ID()); ?>" class="page-title-action">New Payment</a>
		<span class="pull-right">Current Balance: <span id="current_balance"><?php echo AFMHelper::formatMoney($aff->balance()); ?></span></span>
	</div>
	<div class="aff_content_cell" id="aff_content_top">
		<h2 class="nav-tab-wrapper" id="aff-tabs">
			<a class="nav-tab <?php echo $activePage == 'accounting' ? 'nav-tab-active' : ''; ?>" id="accounting-tab" href="#top#accounting">Accounting</a>
			<a class="nav-tab <?php echo $activePage == 'links' ? 'nav-tab-active' : ''; ?>" id="links-tab" href="#top#links">Links</a>
			<a class="nav-tab <?php echo $activePage == 'settings' ? 'nav-tab-active' : ''; ?>" id="settings-tab" href="#top#settings">Settings</a>
		</h2>
		<div id="tab-accounting" class="tab-view <?php echo $activePage == 'accounting' ? 'active' : ''; ?>">
			<?php include_once("affiliate-accounting.php"); ?>
		</div>
		<div id="tab-links" class="tab-view <?php echo $activePage == 'links' ? 'active' : ''; ?>">
			<?php include_once("affiliate-links.php"); ?>
		</div>
		<div id="tab-settings" class="tab-view <?php echo $activePage == 'settings' ? 'active' : ''; ?>">
			<?php include_once("affiliate-settings.php"); ?>
		</div>
	</div>
</div>

