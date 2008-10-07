<?php

class BasesfGuardFormForgotPassword extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username_or_email_address' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'username_or_email_address' => new sfGuardValidatorUsernameOrEmail(array('trim' => true)),
    ));
  }
}
