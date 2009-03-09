<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Processes the "remember me" cookie.
 * 
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 * 
 * @deprecated Use {@link sfGuardRememberMeFilter} instead
 */
class sfGuardBasicSecurityFilter extends sfBasicSecurityFilter
{
  public function execute($filterChain)
  {
    $cookieName = sfConfig::get('app_sf_guard_plugin_remember_cookie_name', 'sfRemember');

    if ($this->isFirstCall())
    {
      $this->context->getLogger()->notice(sprintf('The filter "%s" is deprecated. Use "sfGuardRememberMeFilter" instead.', __CLASS__));

      if (
        $this->context->getUser()->isAnonymous()
        &&
        $cookie = $this->getContext()->getRequest()->getCookie($cookieName)
      )
      {
        $rk = Doctrine_Query::create()
                ->from('sfGuardRememberKey')
                ->where('sfGuardRememberKey.remember_key = ?', $cookie)
                ->execute()
                ->getFirst();

        if ($rk)
        {
          $user = $rk->getUser();

          if ($user instanceof sfGuardUser && $user->exists())
          {
            $this->context->getUser()->signIn($user);
          }
        }
      }
    }

    parent::execute($filterChain);
  }
}
