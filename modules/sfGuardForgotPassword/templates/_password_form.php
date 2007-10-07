<?php use_helper('Validation', 'I18N'); ?>

<?php echo form_tag('@sf_guard_do_password'); ?> 
  <div id="sf_guard_password_form">
    
    <h3><?php echo __('Enter your username or e-mail address to receive instructions on changing your password.'); ?></h3>
    
  	<fieldset>
      <div class="form-row" id="sf_guard_auth_username_or_email_address">
        <?php echo form_error('username_or_email_address') ?>
        <label for="username_or_email_address"><?php echo __('username or e-mail address'); ?>:</label>
        <?php echo input_tag('username_or_email_address', $sf_data->get('sf_params')->get('username_or_email_address')) ?>
      </div>
    </fieldset>

    <?php echo submit_tag(__('request')) ?>
  </div>  
</form>