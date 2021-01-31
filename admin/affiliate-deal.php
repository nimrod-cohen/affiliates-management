<?php 
  $payouts = $aff->getProductPayouts();
?>
<header>
  <h2>Product deals override the main deal</h2>
</header>
<form method="POST" class="single-line-form" action="">
  <input type="hidden" name="action" value="add_product_payout">
  <input type="hidden" name="affiliate_id" value="<?php echo $aff->ID(); ?>">
  <div class="form-input">
    <label for="product_id">Product ID</label>
    <input type="text" id="product_id" name="product_id">
  </div>
  <div class="form-input">
    <label for="is_first">First deposit? 
    <input type="checkbox" id="is_first" name="is_first">
    </label>
  </div>
  <div class="form-input">
    <label for="is_first">Payout </label>
    <input type="text" id="payout" name="payout">
    </label>
  </div>
  <button class="button primary">Add</button>
</form>
<table class="wp-list-table widefat striped posts">
  <thead>
    <tr>
      <th>Product ID</th>
      <th>First Deposit</th>
      <th>Payout</th>
    </thead>
    <tbody>
    <?php 
      if(count($payouts) == 0) { ?>
        <tr>
          <td colspan="3">No payouts found</td>
        </tr>
    <?php } else { 
      foreach($payouts as $payout) {
      ?>
    <tr>
      <td><?php echo $payout["product_id"]; ?></td>
      <td><?php echo $payout["is_first"] ? "YES" : "" ?></td>
      <td><?php echo $payout["payout"]; ?></td>
    </tr>
    <?php } 
    } ?>
    </tbody>
</table>