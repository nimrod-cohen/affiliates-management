<?php
	$total = 0;
	$rowsPerPage = 20;
	$page = isset($_GET["paged"]) ? $_GET["paged"] : 1;
	$rows = AFMAccounting::byAffiliate($aff->ID(),$page-1,$rowsPerPage,$total);
?>
<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="get">
	<input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
	<input type="hidden" name="subpage" value="edit-affiliate">
	<input type="hidden" name="page" value="affiliates-management">
	<?php 	echo AFMHelper::tablePaging($total,$page,$rowsPerPage); ?>
</form>
<table class="wp-list-table widefat striped posts">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<!--input id="cb-select-all-1" type="checkbox"-->
		</td>
		<th scope="col" id="p_month" class="manage-column column-month">Month</th>
		<th scope="col" id="p_ftd" class="manage-column column-ftd">FTD Revenue</th>
		<th scope="col" id="p_retention" class="manage-column column-retention">Retention Revenue</th>
		<th scope="col" id="p_total" class="manage-column column-total">Total</th>
		<th scope="col" id="p_paid" class="manage-column column-paid">Paid</th>
		<th scope="col" id="p_balance" class="manage-column column-balance">Month Balance</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach( $rows as $row )
	{
		$ftd = $row["ftd_revenue"];
		$retention = $row["retention_revenue"];
		$paid = $row["paid"];
		$month = date("M Y",strtotime($row["month"]));
		?>
		<tr>
			<td></td>
			<td><?php echo $month; ?></td>
			<td><?php echo AffiliatesManagement::moneyFormat($ftd); ?></td>
			<td><?php echo AffiliatesManagement::moneyFormat($retention); ?></td>
			<td><?php echo AffiliatesManagement::moneyFormat($ftd + $retention); ?></td>
			<td><?php echo AffiliatesManagement::moneyFormat($paid); ?></td>
			<td><?php echo AffiliatesManagement::moneyFormat($ftd + $retention - $paid); ?></td>
		</tr>
	<?php } ?>
</tbody>
</table>
