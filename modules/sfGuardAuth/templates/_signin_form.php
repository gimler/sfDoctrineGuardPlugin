<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" />
  <fieldset>
    <div class="form-row" id="sf_guard_auth_username">
      <?php echo $form['username']; ?>
    </div>

    <div class="form-row" id="sf_guard_auth_password">
      <?php echo $form['password']; ?>
    </div>

    <?php if(sfConfig::get('sfGuardPlugin_use_remember') ): ?>
      <div class="form-row" id="sf_guard_auth_remember">
        <?php echo $form['remember']; ?>
      </div>
    <?php endif; ?>
  </fieldset>

  <button type="submit"><?php echo __('sign in') ?></button>
  
  <?php if(sfConfig::get('sfGuardPlugin_use_account_helpers') ): ?>
    <?php echo link_to(__('Forgot your password?'), '@sf_guard_password', array('id' => 'sf_guard_auth_forgot_password')) ?> or 
    <?php echo link_to(__('Need an account?'), '@sf_guard_register'); ?>
  <?php endif; ?>
</form>
