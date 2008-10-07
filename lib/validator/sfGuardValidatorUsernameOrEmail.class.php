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
 * @version    SVN: $Id: sfGuardValidatorUser.class.php 7904 2008-03-15 13:18:36Z fabien $
 */
class sfGuardValidatorUsernameOrEmail extends sfValidatorBase
{
  public function configure($options = array(), $messages = array())
  {
  }

  protected function doClean($value)
  {
    $clean = (string) $value;

    // user exists?
    if (false !== sfGuardUserTable::retrieveByUsernameOrEmailAddress($clean))
    {
    	return $value;
    }

    throw new sfValidatorError($this, 'invalid', array('value' => $value));
  }
}
