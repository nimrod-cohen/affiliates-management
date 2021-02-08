<?php
/**
 * Created by PhpStorm.
 * User: nimrod
 * Date: 08/14/17
 * Time: 13:20
 */
	$aff = AFMAffiliate::fromAffiliateId($_GET["id"]);
?>

<?php if($showUpdated) { ?>
	<div id="message" class="updated <?php echo $updateResult ? "notice" : "error"; ?> is-dismissible">
		<p><strong><?php echo $updateResult ? "Details updated successfully" : "Failed to update details"; ?></strong></p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
<?php } ?>

<div class="wrap" id="profile-page">
	<p><a href="<?php echo admin_url("admin.php?page=affiliates-management&subpage=edit-affiliate&id=".$aff->ID()); ?>">← Back to Edit Affiliate</a></p>
	<h1 class="wp-heading-inline">Pay <?php echo $aff->fullname(); ?>	</h1>
	<hr class="wp-header-end">
	<form id="your-profile" action="" method="POST" novalidate="novalidate">
		<input type="hidden" id="wp_nonce" name="wp_nonce" value="<?php echo wp_create_nonce("save_affiliate_details"); ?>">
		<input type="hidden" name="action" value="pay_affilate">
		<input type="hidden" name="affiliate_id" id="affiliate_id" value="<?php echo $aff->ID(); ?>">
		<h2>Payment details</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label>Current balance</label></th>
					<td><label><?php echo AFMHelper::formatMoney($aff->balance()); ?></label></td>
				</tr>
				<tr>
					<th><label for="payment_date">Payment Date <span class="required">*</span></label></th>
					<td><input type="date" name="payment_date" id="payment_date" value="" class="regular-text ltr"></td>
				</tr>
				<tr>
					<th><label for="amount">Amount <span class="required">*</span></label></th>
					<td><input type="text" name="amount" id="amount" value="" class="regular-text ltr"></td>
				</tr>
				<tr>
					<th><label for="comment">Comment</label></th>
					<td><textarea type="text" name="comment" id="comment" value="" class="regular-text ltr"></textarea></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Do payment">
		</p>
	</form>
</div>

