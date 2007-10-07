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
 * @subpackage sfGuardDoctrinePlugin
 * @author     Jonathan H. Wage <jonwage@gmail.com>
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
	public function executePassword()
	{
	}

	/**
	 * executeRequest_reset_password
	 *
	 * Accepts username or email address and send reset password link to the entered username
	 * 
	 * @access public
	 * @return void
	 */
	public function executeRequest_reset_password()
	{
		$sfGuardUser = sfGuardUserTable::retrieveByUsernameOrEmailAddress($this->getRequestParameter('username_or_email_address'));
		
		if( $sfGuardUser && $sfGuardUser->getId() )
		{
			$rawEmail = $this->sendEmail('sfGuardForgotPassword', 'send_request_reset_password');
			$this->logMessage($rawEmail, 'debug');
		} else {
			$this->getRequest()->setError('username_or_email_address', 'Username or e-mail address not found please try again.');
			$this->forward('sfGuardForgotPassword', 'password');
		}
	}

	/**
	 * executeSend_request_reset_password 
	 * 
	 * Send request reset password email which contains the link to reset password and have it mailed to you
	 * 
	 * @access public
	 * @return void
	 */
	public function executeSend_request_reset_password()
	{
		$sfGuardUser = sfGuardUserTable::retrieveByUsernameOrEmailAddress($this->getRequestParameter('username_or_email_address'));
		
		$mail = new sfMail();
		$mail->setContentType('text/html');
		$mail->setSender(sfConfig::get('app_outgoing_emails_sender'));
		$mail->setFrom(sfConfig::get('app_outgoing_emails_from'));
		$mail->addReplyTo(sfConfig::get('app_outgoing_emails_reply_to'));
		$mail->addAddress($sfGuardUser->getEmailAddress());
		$mail->setSubject('Request to reset password');
		
		$this->sfGuardUser = $sfGuardUser;
		$this->mail = $mail;
	}
	
	/**
	 * handleErrorRequest_reset_password 
	 *
	 * Handle error for request reset password, go back to the reset password form
	 * 
	 * @access public
	 * @return void
	 */
	public function handleErrorRequest_reset_password()
	{
		$this->forward('sfGuardForgotPassword', 'password');
	}

	/**
	 * executeReset_password 
	 *
	 * Reset the users password and e-mail it
	 * 
	 * @access public
	 * @return void
	 */
	public function executeReset_password()
	{
		$params = array($this->getRequestParameter('key'), $this->getRequestParameter('id'));

		$query = new Doctrine_Query();
		$query->from('sfGuardUser u')->where('u.password = ? AND u.id = ?', $params)->limit(1);
		
		$this->sfGuardUser = $query->execute()->getFirst();
		$this->forward404Unless($this->sfGuardUser);

		$newPassword = time();
		$this->sfGuardUser->setPassword($newPassword);
		$this->sfGuardUser->save();
		
		$this->getRequest()->setAttribute('password', $newPassword);
		
		$rawEmail = $this->sendEmail('sfGuardForgotPassword', 'send_reset_password');
		$this->logMessage($rawEmail, 'debug');
	}

	/**
	 * executeSend_reset_password 
	 * 
	 * @access public
	 * @return void
	 */
	public function executeSend_reset_password()
	{
		$sfGuardUser = Doctrine_Manager::getInstance()->getTable('sfGuardUser')->find($this->getRequestParameter('id'));
		
		$mail = new sfMail();
		$mail->setContentType('text/html');
		$mail->setSender(sfConfig::get('app_outgoing_emails_sender'));
		$mail->setFrom(sfConfig::get('app_outgoing_emails_from'));
		$mail->addReplyTo(sfConfig::get('app_outgoing_emails_reply_to'));
		$mail->addAddress($sfGuardUser->getEmailAddress());
		$mail->setSubject('Password reset successfully');
		
		$this->sfGuardUser = $sfGuardUser;
		$this->username = $sfGuardUser->getUsername();
		$this->password = $this->getRequest()->getAttribute('password');
		$this->mail = $mail;
	}
}