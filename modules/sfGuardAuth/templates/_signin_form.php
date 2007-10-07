<?php use_helper('Validation', 'I18N') ?>

<?php echo form_tag('@sf_guard_signin') ?>
  <fieldset>
    <div class="form-row" id="sf_guard_auth_username">
      <?php echo form_error('username') ?>
      <label for="username"><?php echo __('username'); ?>:</label>
      <?php echo input_tag('username', $sf_data->get('sf_params')->get('username'), array('autocomplete' => 'off')) ?>
    </div>

    <div class="form-row" id="sf_guard_auth_password">
      <?php echo form_error('password') ?>
      <label for="password"><?php echo __('password'); ?>:</label>
      <?php echo input_password_tag('password') ?>
    </div>

    <div class="form-row" id="sf_guard_auth_remember">
	    <label for="remember"><?php echo __('Remember me?'); ?></label>
	    <?php echo checkbox_tag('remember')?>
    </div>
  </fieldset>

  <?php echo submit_tag(__('sign in')) ?>
  
  <?php if( sfGuardUser::hasEmailAddress() ): ?>
    <?php echo link_to(__('Forgot your password?'), '@sf_guard_password', array('id' => 'sf_guard_auth_forgot_password')) ?> or 
    <?php echo link_to(__('Need an account?'), '@sf_guard_register'); ?>
  <?php endif; ?>
</form>