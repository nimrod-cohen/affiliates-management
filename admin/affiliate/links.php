<table class="wp-list-table widefat striped posts">
	<thead>
	<tr>
		<th>ID</th>
		<th>Url</th>
		<th>Creation Date</th>
		<th>Clicks</th>
		<th>Registrations</th>
		<th>Conversions</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$links = AFMStats::affLinkStats($aff->ID());
	foreach($links as $link) { ?>
		<tr>
			<td><?php echo $link["id"]; ?></td>
			<td class="left"><?php echo $link["url"]."?uid=".$link["aff_id"]."&sid=".$link["id"]; ?></td>
			<td><?php echo $link["created"]; ?></td>
			<td><?php echo $link["click"]; ?></td>
			<td><?php echo $link["register"]; ?></td>
			<td><?php echo $link["first_deposit"]; ?></td>
		</tr>
	<?php }	?>
	</tbody>
</table>
