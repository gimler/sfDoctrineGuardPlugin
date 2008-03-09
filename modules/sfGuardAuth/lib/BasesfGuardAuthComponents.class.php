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
 * @version    SVN: $Id: BasesfGuardAuthActions.class.php 1949 2006-09-05 14:40:20Z fabien $
 */
class BasesfGuardAuthComponents extends sfComponents
{
  public function executeSignin_form($request)
  {
    if (!isset($this->form))
    {
      $this->form = new sfGuardDoctrineFormSignin();
    }
  }
}
