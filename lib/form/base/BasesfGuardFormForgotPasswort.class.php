<?php

class BasesfGuardFormForgotPassword extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username_or_email_address' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'username_or_email_address' => new sfGuardValidatorUsernameOrEmail(array('trim' => true), array('required' => 'Your username or e-mail address is required.', 'invalid' => 'Username or e-mail address not found please try again.')),
    ));
  }
}
