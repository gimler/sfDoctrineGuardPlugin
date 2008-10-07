<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardMail.class.php 7636 2008-02-27 18:50:43Z fabien $
 */
class sfGuardMail
{
  /**
   * send mail
   *
   * @param array parameter
   */
  static public function send(array $params)
  {
    if(!(isset($params['to']) and isset($params['subject']) and isset($params['message'])))
    {
      throw new sfException('You must provide the following parameter to, subject and message');
    }
  	mail($params['to'], $params['subject'], $params['message']);
  }
}