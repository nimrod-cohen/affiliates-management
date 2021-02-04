<form id="your-profile" action="" method="POST" novalidate="novalidate">
	<input type="hidden" id="wp_nonce" name="wp_nonce" value="<?php echo wp_create_nonce("save_affiliate_details"); ?>">
	<input type="hidden" name="action" value="save_affilate_settings">
	<input type="hidden" name="affiliate_id" id="affiliate_id" value="<?php echo $aff->ID(); ?>">
	<h2>Contact details</h2>
	<table class="form-table">
		<tbody><tr class="user-email-wrap">
			<th><label for="email">Email <span class="description">(required)</span></label><l></th>
			<td><input type="email" name="email" id="email" value="<?php echo $aff->email(); ?>" class="regular-text ltr"></td>
		</tr>

		<tr class="user-url-wrap">
			<th><label for="url">Full name</label></th>
			<td><input type="url" name="fullname" id="fullname" value="<?php echo $aff->fullname(); ?>" class="regular-text code"></td>
		</tr>

		<tr class="user-url-wrap">
			<th><label for="url">Website</label></th>
			<td><input type="url" name="url" id="url" value="<?php echo $aff->website(); ?>" class="regular-text code"></td>
		</tr>

		<tr>
			<th><label for="aff_phone">
					Phone	</label></th>
			<td><input type="text" name="aff_phone" id="aff_phone" value="<?php echo $aff->phone(); ?>" class="regular-text"></td>
		</tr>
		<tr class="user-twitter-wrap">
			<th><label for="skype">
					Skype username </label></th>
			<td><input type="text" name="skype" id="skype" value="<?php echo $aff->skype(); ?>" class="regular-text"></td>
		</tr>
		<tr class="user-twitter-wrap">
			<th><label for="skype">
					New password </label></th>
			<td><input type="password" name="password" id="password" value="" class="regular-text"></td>
		</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Affiliate">
	</p>
</form>
