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

<div class="wrap" id="affiliate-page" affiliate-id="<?php echo $aff->ID(); ?>">
	<div class="affiliate-heading">
		<p><a href="<?php echo admin_url("admin.php?page=affiliates-management"); ?>">← Back to Affiliates</a></p>
		<h1 STYLE="display: inline-block"><?php echo $aff->fullname(); ?> - details</h1>
		<a href="<?php echo admin_url("admin.php?page=affiliates-management&subpage=pay-affiliate&id=".$aff->ID()); ?>" class="page-title-action">New Payment</a>
		<a href="#" id="attach-user-to-affiliate" class="page-title-action">Attach User</a>
		<span class="pull-right">Current Balance: <span id="current_balance"><?php echo AFMHelper::formatMoney($aff->balance()); ?></span></span>
	</div>
	<div class="aff_content_cell" id="aff_content_top">
		<h2 class="nav-tab-wrapper" id="aff-tabs">
			<a class="nav-tab <?php echo $activePage == 'accounting' ? 'nav-tab-active' : ''; ?>" id="accounting-tab" href="#top#accounting">Accounting</a>
			<a class="nav-tab <?php echo $activePage == 'links' ? 'nav-tab-active' : ''; ?>" id="links-tab" href="#top#links">Links</a>
			<a class="nav-tab <?php echo $activePage == 'deal-options' ? 'nav-tab-active' : ''; ?>" id="deal-options-tab" href="#top#deal-options">Deal options</a>
			<a class="nav-tab <?php echo $activePage == 'payouts' ? 'nav-tab-active' : ''; ?>" id="payouts-tab" href="#top#payouts">Product Payouts</a>
			<a class="nav-tab <?php echo $activePage == 'contact' ? 'nav-tab-active' : ''; ?>" id="contact-tab" href="#top#contact">Contact details</a>
		</h2>
		<div id="tab-accounting" class="tab-view <?php echo $activePage == 'accounting' ? 'active' : ''; ?>">
			<?php include_once("affiliate/accounting.php"); ?>
		</div>
		<div id="tab-links" class="tab-view <?php echo $activePage == 'links' ? 'active' : ''; ?>">
			<?php include_once("affiliate/links.php"); ?>
		</div>
		<div id="tab-deal-options" class="tab-view <?php echo $activePage == 'deal-options' ? 'active' : ''; ?>">
			<?php include_once("affiliate/deal-options.php"); ?>
		</div>
		<div id="tab-payouts" class="tab-view <?php echo $activePage == 'payouts' ? 'active' : ''; ?>">
			<?php include_once("affiliate/payouts.php"); ?>
		</div>
		<div id="tab-contact" class="tab-view <?php echo $activePage == 'contact' ? 'active' : ''; ?>">
			<?php include_once("affiliate/contact.php"); ?>
		</div>
	</div>
</div>

