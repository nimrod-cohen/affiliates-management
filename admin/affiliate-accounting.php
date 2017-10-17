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
		<th scope="col" id="p_comment" class="manage-column column-comment">Comment</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$rows = AFMAccounting::byAffiliate($aff->ID(),0);

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
			<td><?php echo nl2br($row["comment"]); ?></td>
		</tr>
	<?php } ?>
</tbody>
</table>
