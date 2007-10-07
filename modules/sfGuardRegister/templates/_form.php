<?php use_helper('Validation', 'I18N'); ?>

<?php echo form_tag('@sf_guard_do_register'); ?>
  
  <div id="sf_guard_password_form">
  
    <fieldset>
      <div class="form-row" id="sf_guard_register_username">
        <?php echo form_error('user[username]') ?>
        <label for="username"><?php echo __('username'); ?>:</label>
        <?php echo input_tag('user[username]', $sf_data->get('sf_params')->get('user[username]')) ?>
      </div>
      
      <div class="form-row" id="sf_guard_register_email_address">
        <?php echo form_error('user[email_address]') ?>
        <label for="username"><?php echo __('email address'); ?>:</label>
        <?php echo input_tag('user[email_address]', $sf_data->get('sf_params')->get('user[email_address]')) ?>
      </div>
      
      <div class="form-row" id="sf_guard_register_password">
        <?php echo form_error('user[password]') ?>
        <label for="password"><?php echo __('password'); ?>:</label>
        <?php echo input_password_tag('user[password]', $sf_data->get('sf_params')->get('user[password]')) ?>
      </div>
      
      <div class="form-row" id="sf_guard_register_password_confirmation">
        <?php echo form_error('user[password_confirmation]') ?>
        <label for="password_confirmation"><?php echo __('password confirmation'); ?>:</label>
        <?php echo input_password_tag('user[password_confirmation]', $sf_data->get('sf_params')->get('user[password_confirmation]')) ?>
      </div>
    </fieldset>
    
    <?php echo submit_tag(__('register')); ?>
  </div>
</form>