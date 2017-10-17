<h1>General Settings</h1>
<form method="post" action="" novalidate="novalidate">
	<input type="hidden" name="action" value="save_settings"/>
	<input type="hidden" name="active_page" value="settings"/>
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="script_location">Inject tracking script into</label></th>
			<td>
				<select name="script_location" id="script_location">
					<option value="header" <?php echo $location == "header" ? "selected" : ""; ?>>Header</option>
					<option value="footer" <?php echo $location == "footer" ? "selected" : ""; ?>>Footer</option>
				</select>
			</td>
		</tr>
		<tr scope="row">
			<th scope="row"><label for="locking_event">Lock user attribution on</label></th>
			<td>
				<select name="locking_event" id="locking_event">
					<option value="register" <?php echo $lockingEvent == "register" ? "selected" : ""; ?>>Registration</option>
					<option value="authenticate" <?php echo $lockingEvent == "authenticate" ? "selected" : ""; ?>>Email Authentication</option>
					<option value="first_deposit" <?php echo $lockingEvent == "first_deposit" ? "selected" : ""; ?>>First Deposit</option>
				</select>
				<p class="description">Once this event occurs, the user will always belong to the affiliate who brought him</p>
			</td>
		</tr>
		<tr>
			<th><label for="deal_type">Default Deal</label></th>
			<td>
				<select name="deal_type" id="deal_type">
					<?php foreach(AFM_DealType::getTypes() as $type)
					{
						echo "<option value='".$type."' ".($deal["type"] == $type ? "selected" : "").">".str_replace("_"," ",AFM_DealType::toString($type))."</option>";
					}
					?>
				</select>
			</td>
		</tr>

		<tr deal-id="<?php echo AFM_DealType::CPA; ?>" class="user-url-wrap <?php echo $deal["type"] == AFM_DealType::CPA || $deal["type"] == AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE ? '' : 'hidden'; ?>">
			<th><label for="cpa">CPA</label></th>
			<td><input type="text" name="cpa" id="cpa" value="<?php echo isset($deal["CPA"]) ? $deal["CPA"] : ""; ?>" class="regular-text code"></td>
		</tr>

		<tr deal-id="<?php echo AFM_DealType::REVENUE_SHARE; ?>" class="user-url-wrap <?php echo $deal["type"] == AFM_DealType::REVENUE_SHARE || $deal["type"] == AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE ? '' : 'hidden'; ?>">
			<th><label for="revshare">Revenue Share %<br/><br/>Months (0 => Lifetime)</label></th>
			<td>
				<input type="text" name="revshare" id="revshare" value="<?php echo isset($deal["REVSHARE"]) ? $deal["REVSHARE"] : ""; ?>" class="regular-text code"><br/>
				<input type="text" name="revshare_period" id="revshare_period" value="<?php echo isset($deal["REVSHARE_PERIOD"]) ? $deal["REVSHARE_PERIOD"] : ""; ?>" class="regular-text code">
			</td>
		</tr>

		<script>
			jQuery("#deal_type").change(function(){
				var val = jQuery(this).val();

				jQuery("tr[deal-id]").each(function(){
					if(jQuery(this).attr("deal-id") != val && val != <?php echo AFM_DealType::MIXED_CPA_AND_REVEUE_SHARE; ?>)
						jQuery(this).addClass("hidden");
					else
						jQuery(this).removeClass("hidden");
				});

			});
		</script>

		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>
