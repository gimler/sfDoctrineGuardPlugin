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
class BasesfGuardAuthActions extends sfActions
{
  public function executeSignin()
  {
    if ($this->getRequest()->getMethod() != sfRequest::POST)
    {
      // display the form
      if (!$this->getUser()->hasAttribute('referer'))
      {
        $referer = $this->getContext()->getActionStack()->getSize() == 1 ? $this->getRequest()->getReferer() : $this->getRequest()->getUri();

        $this->getUser()->setAttribute('referer', $referer);
      }
    }
    else
    {
      // handle the form submission
      // redirect to last page
      $referer = $this->getUser()->getAttribute('referer', '@homepage');
      $this->getUser()->getAttributeHolder()->remove('referer');
      $this->redirect($referer);
    }
  }
	
	public function handleErrorSignin()
  {
    return sfView::SUCCESS;
  }

  public function executeSignout()
  {
    $this->getUser()->signOut();
    $this->redirect('@homepage');
  }

  public function executeSecure()
	{
	}
}