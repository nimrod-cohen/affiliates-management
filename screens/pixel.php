<form class="settings" name="frmPixel" id="frmPixel" method="POST" novalidate="novalidate">
	<input type="hidden" name="affiliate_action" value="do_save_pixel">
	<?php
		$pixel = AFMAffiliate::fromCurrentUser()->pixel();
	?>

	<p class="row">
		<label for="user_pass">Conversion pixel</label>
		<input 
			name="pixel"
			id="pixel"
			class="input full-width"
			type="text" 
			placeholder="Ex. https://www.mydomain.com/?utm_source={source}&utm_campaign={campaign}&event={event}" 
			value="<?php echo $pixel; ?>">
	</p>
	<label class="description">Use {source}, {campaign} to receive traffic source info, and {event} placeholder to get type of conversion.<br/>
	Available events: 'register','authenticated','payment'</label>

	<div style="margin-top:20px;clear: both">
		<input type="submit" class="button primary" value="Save">
	</div>
</form>
