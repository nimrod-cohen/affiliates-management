<form class="settings" name="frmPixel" id="frmPixel" method="POST" novalidate="novalidate">
	<input type="hidden" name="affiliate_action" value="do_save_pixel">
	<?php
		$pixel = AFMAffiliate::fromCurrentUser()->pixel();
		$integration = AFMAffiliate::fromCurrentUser()->integration();
		$smoove = ["enabled" => false];
		if(isset($integration["smoove"])) {
			$smoove = $integration["smoove"];
		}
	?>

	<p class="row">
		<label>Conversion pixel</label>
		<input 
			name="pixel"
			id="pixel"
			class="input full-width"
			type="text" 
			placeholder="Ex. https://www.mydomain.com/?utm_source={source}&utm_campaign={campaign}&event={event}" 
			value="<?php echo $pixel; ?>">
	</p>
	<label class="description small">Use {source}, {campaign} to receive traffic source info, and {event} placeholder to get type of conversion.<br/>
	Available events: 'register','authenticated','payment'</label>

	<section id="smoove">
		<p class="row">
			<label for="chk_smoove">
			<input type="checkbox" id="chk_smoove" name="chk_smoove" <?php echo $smoove["enabled"] ? "checked" : ""; ?>>
			Smoove integration
			</label>
		<p/>
		<p class="row">
			<label>API Key</label>
			<input 
				name="smoove[api_key]"
				id="smoove_api_key"
				class="input full-width"
				type="text" 
				placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX" 
				<?php echo $smoove["enabled"] ? "" : "disabled"; ?>
				value="<?php echo isset($smoove["api_key"]) ? $smoove["api_key"] : ""; ?>">
		</p>
		<p class="row">
			<label>Lead list</label>
			<input 
				name="smoove[lead_list]"
				id="smoove_lead_list"
				class="input full-width"
				type="text" 
				placeholder="XXXXXX" 
				<?php echo $smoove["enabled"] ? "" : "disabled"; ?>
				value="<?php echo isset($smoove["lead_list"]) ? $smoove["lead_list"] : ""; ?>">
		</p>
		<p class="row">
			<label>Conversion list</label>
			<input 
				name="smoove[conversion_list]"
				id="smoove_conversion_list"
				class="input full-width"
				type="text" 
				placeholder="XXXXXX" 
				<?php echo $smoove["enabled"] ? "" : "disabled"; ?>
				value="<?php echo isset($smoove["conversion_list"]) ? $smoove["conversion_list"] : ""; ?>">
		</p>
		<label class="description small">use conversion list (paying customers) to remove from marketing messages</label>
	</section>

	<div style="margin-top:20px;clear: both">
		<input type="submit" class="button primary" value="Save">
	</div>
</form>
