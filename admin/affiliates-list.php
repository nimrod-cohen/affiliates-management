<?php
	$affiliateSearch = isset($_POST["affiliate_search"]) ? $_POST["affiliate_search"] : "";

	$paged = isset($_POST["paged"]) ? $_POST["paged"] : 1;
	$pageSize = 20;

	$query = ["role" => AffiliatesManagement::AFM_ROLE_NAME,"number"=>$pageSize,"paged"=>$paged];

	if(strlen($affiliateSearch) > 0)
		$query['search'] = $affiliateSearch . (preg_match("/\*$/", $affiliateSearch) ? "" : "*");

	$affs = new WP_User_Query( $query );

	$rowsCount = $affs->total_users;

?>
<div class="tablenav">
	<form method="POST" action="">
		<p class="search-box" style="float: left;">
			<label class="screen-reader-text" for="user-search-input">Search Affiliates:</label>
			<input type="search" id="affiliate-input" name="affiliate_search" value="<?php echo $affiliateSearch; ?>">
			<input type="submit" id="search-submit" class="button" value="Search Affiliates">
		</p>
		<input type="hidden" name="action" value="aff_page"/>
		<input type="hidden" name="active_page" value="affiliates"/>
		<?php echo AFMHelper::tablePaging($rowsCount,$paged,$pageSize); ?>
	</form>
</div>
<table class="wp-list-table widefat striped posts">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<!--input id="cb-select-all-1" type="checkbox"-->
		</td>
		<th scope="col" id="p_affiliate" class="manage-column column-affiliate">Affiliate</th>
		<th scope="col" id="p_fullname" class="manage-column column-fullname">Name</th>
		<th scope="col" id="p_deal" class="manage-column column-deal">Deal</th>
		<th scope="col" id="p_balance" class="manage-column column-balance">Balance</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach( $affs->results as $affiliate )
	{
		$aff = AFMAffiliate::fromWPUser($affiliate);
		$editUrl = admin_url("admin.php?page=affiliates-management&subpage=edit-affiliate&id=".$aff->ID());
		$payUrl = admin_url("admin.php?page=affiliates-management&subpage=pay-affiliate&id=".$aff->ID());
		?>
		<tr>
			<td></td>
			<td class="username column-username has-row-actions column-primary" data-colname="Username">
				<?php echo get_avatar($aff->ID(),32); ?>
				<strong>
					<a href="<?php echo $editUrl ?>"><?php echo $aff->email(); ?></a>
				</strong>
				<br>
				<div class="row-actions">
					<span class="edit"><a href="<?php echo $editUrl; ?>">Edit</a> | </span>
					<span class="delete"><a class="submitsuspend" href="#">Suspend</a> | </span>
					<span class="pay"><a class="submitpay" href="<?php echo $payUrl; ?>">Pay</a></span>
				</div>
			</td>
			<td><?php echo $aff->fullname(); ?></td>
			<td><?php echo AFM_DealType::parseDeal($aff->deal()); ?></td>
			<td><?php echo $aff->balance(); ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
