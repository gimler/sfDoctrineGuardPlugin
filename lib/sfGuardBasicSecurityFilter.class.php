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
 * @package        symfony
 * @subpackage     plugin
 * @author         Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version        SVN: $Id$
 */
class sfGuardBasicSecurityFilter extends sfBasicSecurityFilter
{
    public function execute( $filterChain )
    {
        if ( $this->isFirstCall() AND !$this->getContext()->getUser()->isAuthenticated() )
        {
            if ( $cookie = $this->getContext()->getRequest()->getCookie( sfConfig::get( 'app_sf_guard_plugin_remember_cookie_name', 'sfRemember' ) ) )
            {
                $remember_key = Doctrine_Query::create()->from( 'sfGuardRememberKey' )->where( 'sfGuardRememberKey.remember_key = ?', $cookie )->execute()->getFirst();

                if ( $remember_key )
                {
                    $user = $remember_key->getUser();

                    if ( $user->exists() )
                    {
                        $this->getContext()->getUser()->signIn( $user );
                    }
                }
            }
        }

        parent::execute($filterChain);
    }
}
