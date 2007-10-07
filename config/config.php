<?php

if (sfConfig::get('app_sfGuardPlugin_routes_register', true) && in_array('sfGuardAuth', sfConfig::get('sf_enabled_modules')))
{
  $r = sfRouting::getInstance();

  // preprend our routes
  $r->prependRoute('sf_guard_signin', '/login', array('module' => 'sfGuardAuth', 'action' => 'signin'));
  $r->prependRoute('sf_guard_signout', '/logout', array('module' => 'sfGuardAuth', 'action' => 'signout'));
  
  $r->prependRoute('sf_guard_password', '/request_password', array('module' => 'sfGuardForgotPassword', 'action' => 'password'));
  $r->prependRoute('sf_guard_do_password', '/request_password/do', array('module' => 'sfGuardForgotPassword', 'action' => 'request_reset_password'));
  $r->prependRoute('sf_guard_forgot_password_reset_password', '/reset_password/:key/:id', array('module' => 'sfGuardForgotPassword', 'action' => 'reset_password'));
  
  $r->prependRoute('sf_guard_register', '/register', array('module' => 'sfGuardRegister', 'action' => 'index'));
  $r->prependRoute('sf_guard_do_register', '/register/do', array('module' => 'sfGuardRegister', 'action' => 'register'));
  $r->prependRoute('sf_guard_register_confirm', '/register/confirm/:key/:id', array('module' => 'sfGuardRegister', 'action' => 'register_confirm'));
  $r->prependRoute('sf_guard_register_success', '/register/success', array('module' => 'sfGuardRegister', 'action' => 'register_success'));
  $r->prependRoute('sf_guard_register_complete', '/register/complete/:id', array('module' => 'sfGuardRegister', 'action' => 'register_complete'));
}