<form method="POST" action="">
	<div class="actions-bar">
		<input type="hidden" name="affiliate_action" value="">
		<input type="hidden" name="link_id" value="">
		<input type="hidden" name="landing_page_id" value="">
		<input type="button" id="new-link" class="button small primary" value="New link">
	</div>
	<table class="full-width">
		<thead>
		<tr>
			<th>ID</th>
			<th>Url</th>
			<th>Creation Date</th>
			<th>Clicks</th>
			<th>Registrations</th>
			<th>Conversions</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$links = AFMStats::affLinkStats(get_current_user_id());
		foreach($links as $link) { 
			$linkUrl = urldecode($link["url"])."?uid=".$link["aff_id"]."&sid=".$link["id"];
			?>
		
			<tr>
				<td><?php echo $link["id"]; ?></td>
				<td>
					<div class="copiable">
						<span title="<?php echo $linkUrl; ?>"><?php echo $linkUrl; ?></span>
						<button class="button primary small"><i class="fa fa-copy"></i></button>
					</div>
				</td>
				<td><?php echo $link["created"]; ?></td>
				<td><?php echo $link["click"]; ?></td>
				<td><?php echo $link["register"]; ?></td>
				<td><?php echo $link["first_deposit"]; ?></td>
				<td><input type="button" class="button small danger delete-link" link-id="<?php echo $link["id"]; ?>" value="Delete"></td>
			</tr>
		<?php }	?>
		</tbody>
	</table>
</form>
<script>
	//implement full url on hover + copy to clipboard

</script>