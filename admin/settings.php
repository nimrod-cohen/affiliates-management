<?php
	$location = get_option("afm-tracker-script-location","header");
	$lockingEvent = AffiliatesManagement::lockingEvent();
	$activePage = isset($_POST["active_page"]) ? $_POST["active_page"] : "affiliates";

	$keepDays = get_option("afm-keep-days",AffiliatesManagement::AFM_KEEP_DAYS);

	$deal = AffiliatesManagement::defaultDeal();
?>
<div class="wrap">
	<?php if($showUpdated) { ?>
		<div id="message" class="updated <?php echo $updateResult ? "notice" : "error"; ?> is-dismissible">
			<p><strong><?php echo $updateResult ? "Details updated successfully" : "Failed to update details"; ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
	<?php } ?>

	<div class="aff_content_cell" id="aff_content_top">
		<h2 class="nav-tab-wrapper" id="aff-tabs">
			<a class="nav-tab <?php echo $activePage == 'affiliates' ? 'nav-tab-active' : ''; ?>" id="affiliates-tab" href="#top#affiliates">Affiliates</a>
			<a class="nav-tab <?php echo $activePage == 'landingpages' ? 'nav-tab-active' : ''; ?>" id="landingpages-tab" href="#top#landingpages">Landing Pages</a>
			<a class="nav-tab <?php echo $activePage == 'settings' ? 'nav-tab-active' : ''; ?>" id="settings-tab" href="#top#settings">Settings</a>
		</h2>
		<div id="tab-affiliates" class="tab-view <?php echo $activePage == 'affiliates' ? 'active' : ''; ?>">
			<?php include_once("affiliates-list.php"); ?>
		</div>
		<div id="tab-landingpages" class="tab-view <?php echo $activePage == 'landingpages' ? 'active' : ''; ?>">
			<?php include_once("landingpages.php"); ?>
		</div>
		<div id="tab-settings" class="tab-view <?php echo $activePage == 'settings' ? 'active' : ''; ?>">
			<?php include_once("general-settings.php"); ?>
		</div>
	</div>
</div>