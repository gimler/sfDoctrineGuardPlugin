<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: BasesfGuardAuthActions.class.php 7745 2008-03-05 11:05:33Z fabien $
 */
class BasesfGuardForgotPasswordActions extends sfActions
{
  /**
   * executePassword
   *
   * Form for requesting instructions on how to reset your password
   *
   * @return void
   * @author Jonathan H. Wage
   */
  public function executePassword($request)
  {
    $user = $this->getUser();
    if ($user->isAuthenticated())
    {
      return $this->redirect('@homepage');
    }

    $this->form = new sfGuardFormForgotPassword();

    if ($request->isMethod('post'))
    {
      $this->form->bind(array('username_or_email_address' => $request->getParameter('username_or_email_address')));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();

        $sfGuardUser = sfGuardUserTable::retrieveByUsernameOrEmailAddress($values['username_or_email_address'], true);

        $messageParams = array(
          'sfGuardUser' => $sfGuardUser,
        );
        $message = $this->getComponent('sfGuardForgotPassword', 'send_request_reset_password', $messageParams);

        $mailParams = array(
          'to' => $sfGuardUser->getEmailAddress(),
          'subject' => 'Request to reset password',
          'message' => $message
        );
        sfGuardMail::send($mailParams);

        return $this->redirect('@sf_guard_do_password?'.http_build_query($values));
      }
    }
  }

  /**
   * executeRequest_reset_password
   * 
   * @access public
   * @return void
   */
  public function executeRequest_reset_password()
  {
  }

  /**
   * executeReset_password 
   *
   * Reset the users password and e-mail it
   * 
   * @access public
   * @return void
   */
  public function executeReset_password($request)
  {
    $params = array($request->getParameter('key'), $request->getParameter('id'));

    $query = new Doctrine_Query();
    $query->from('sfGuardUser u')->where('u.password = ? AND u.id = ?', $params)->limit(1);

    $this->sfGuardUser = $query->execute()->getFirst();

    if ( ! $this->sfGuardUser)
    {
      $this->forward('sfGuardForgotPassword', 'invalid_key');
    }

    $newPassword = time();
    $this->sfGuardUser->setPassword($newPassword);
    $this->sfGuardUser->save();

    $messageParams = array(
      'username' => $this->sfGuardUser->getUsername(),
      'password' => $newPassword
    );
    $message = $this->getComponent('sfGuardForgotPassword', 'send_reset_password', $messageParams);

    $mailParams = array(
      'to' => $this->sfGuardUser->getEmailAddress(),
      'subject' => 'Password reset successfully',
      'message' => $message
    );
    sfGuardMail::send($mailParams);
  }

  /**
   * executeInvalid_key
   * 
   * @access public
   * @return void
   */
  public function executeInvalid_key()
  {
  }
}