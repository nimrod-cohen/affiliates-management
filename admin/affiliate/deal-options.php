<?php 
	$exposeLeads = $aff->exposeLeads();
?>
<form id="your-profile" action="" method="POST" novalidate="novalidate">
	<input type="hidden" id="wp_nonce" name="wp_nonce" value="<?php echo wp_create_nonce("save_affiliate_deal"); ?>">
	<input type="hidden" name="action" value="save_affilate_deal">
	<input type="hidden" name="affiliate_id" id="affiliate_id" value="<?php echo $aff->ID(); ?>">
	<h2>Deal options</h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="deal_type">Deal</label></th>
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
			
			<tr>
						<th>Expose leads details</th>
						<td>
							<input type="checkbox" name="expose_leads" <?php echo $exposeLeads ? "checked" : ""; ?>/>
						</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Affiliate">
	</p>
</form>
