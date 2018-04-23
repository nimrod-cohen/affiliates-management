<form class="settings" name="frmPixel" id="frmPixel" method="POST" novalidate="novalidate">
	<input type="hidden" name="affiliate_action" value="do_save_pixel">
	<?php
		$pixel = AFMAffiliate::fromCurrentUser()->pixel();
	?>
	<div class="form-group">
		<label for="pixel" class="form-field">Conversion pixel</label>
		<input type="text" class="form-control" id="pixel" name="pixel"  placeholder="Ex. https://www.mydomain.com/?utm_source={source}&utm_campaign={campaign}&event={event}" value="<?php echo $pixel; ?>">
		<label class="description">Use {source}, {campaign} to receive traffic source info, and {event} placeholder to get type of conversion, available events: 'register','authenticated','payment'</label>
	</div>
	<div style="margin-top:20px;clear: both">
		<input type="submit" class="form-button" value="Save">
	</div>
</form>
