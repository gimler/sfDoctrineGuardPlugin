<div id="sf_guard_auth_form">
  <?php echo get_partial('signin_header'); ?>
  <?php echo get_component($sf_module, 'signin_form', array('form'=>$form)); ?>
  <?php echo get_partial('signin_footer'); ?>
</div>
