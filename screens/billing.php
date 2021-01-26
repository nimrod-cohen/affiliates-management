<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 08/10/17
 * Time: 16:03
 */
	$aff = AFMAffiliate::fromCurrentUser();
?>
<div class="form-group half-width small">
	<label for="deal_summary" class="field-title">Deal Summary</label>
	<label id="deal_summary" class="field-text"><?php echo AFM_DealType::parseDeal($aff->deal()); ?></label>
</div>
<div class="form-group pull-right half-width small">
	<label for="balance" class="field-title">Current Balance</label>
	<label id="balance" class="field-text"><?php echo AFMHelper::formatMoney($aff->balance()); ?></label>
</div>
<table class="full-width">
	<thead>
	<tr>
		<th scope="col" class="manage-column column-month">Month</th>
		<th scope="col" class="manage-column column-ftd">FTD Revenue</th>
		<th scope="col" class="manage-column column-retention">Retention Revenue</th>
		<th scope="col" class="manage-column column-total">Total</th>
		<th scope="col" class="manage-column column-paid">Paid</th>
		<th scope="col" class="manage-column column-balance">Month Balance</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$rows = AFMAccounting::byAffiliate($aff->ID(),0);

	if(count($rows) == 0) { ?>
		<tr><td colspan='6'>No data found</td></tr>
	<?php }
	foreach( $rows as $row )
	{
		$ftd = $row["ftd_revenue"];
		$retention = $row["retention_revenue"];
		$paid = $row["paid"];
		$month = date("M Y",strtotime($row["month"]));
		?>
		<tr>
			<td><?php echo $month; ?></td>
			<td><?php echo AFMHelper::formatMoney($ftd); ?></td>
			<td><?php echo AFMHelper::formatMoney($retention); ?></td>
			<td><?php echo AFMHelper::formatMoney($ftd + $retention); ?></td>
			<td><?php echo AFMHelper::formatMoney($paid); ?></td>
			<td><?php echo AFMHelper::formatMoney($ftd + $retention - $paid); ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
