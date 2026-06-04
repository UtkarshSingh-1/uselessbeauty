<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="lost-password-wrapper">
  <h2>Reset Your Password</h2>
  <p>Please enter your email address below. You’ll receive a link to create a new password.</p>

  <form method="post" class="woocommerce-ResetPassword lost_reset_password">
    <p class="form-row">
      <label for="user_login">Email Address</label>
      <input class="input-text" type="text" name="user_login" id="user_login" autocomplete="username" required />
    </p>

    <p class="form-row">
      <input type="hidden" name="wc_reset_password" value="true" />
      <button type="submit" class="button">Send Reset Link</button>
    </p>

    <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
  </form>

  </p>
</div>
