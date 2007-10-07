<?php

/*
 * This file is part of the symfony package.
 * ( c ) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package        symfony
 * @subpackage plugin
 * @author         Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version        SVN: $Id: sfGuardSecurityUser.class.php 3187 2007-01-08 10:51:03Z fabien $
 */
class sfGuardSecurityUser extends sfBasicSecurityUser
{
    protected $user = null;

    public function hasCredential( $credential, $useAnd = true )
    {
        if ( !$this->getGuardUser() )
        {
            return false;
        }

        if ( $this->getGuardUser()->getIsSuperAdmin() )
        {
            return true;
        }

        return parent::hasCredential( $credential, $useAnd );
    }

    public function getUsername()
    {
        return $this->getAttribute('username', null, 'sfGuardSecurityUser');
    }

    public function isAnonymous()
    {
        return $this->getAttribute( 'user_id', null, 'sfGuardSecurityUser' ) ? false : true;
    }

    public function signIn( $user, $remember = false )
    {
        // signin
        $this->setAttribute( 'user_id', $user->get('id'), 'sfGuardSecurityUser' );
        $this->setAttribute( 'username', $user->get('username'), 'sfGuardSecurityUser' );
        $this->setAuthenticated( true );
        $this->clearCredentials();
        $this->addCredentials( $user->getAllPermissionNames() );

        // Get a new date formatter
        $dateFormat = new sfDateFormat();

        // save last login
        $current_time_value = $dateFormat->format( time(), 'I' );
        $user->set('last_login', $current_time_value );
        $user->save();

        // remember?
        if ( $remember )
        {
            // remove old keys
            $expiration_age = sfConfig::get( 'app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600 );
            $expiration_time_value = $dateFormat->format( time() - $expiration_age, 'I' );
            sfDoctrine::queryFrom( 'sfGuardRememberKey' )->delete( 'sfGuardRememberKey' )->where( 'sfGuardRememberKey.created_at < ?', $expiration_time_value )->execute();

            // remove other keys from this user
            sfDoctrine::queryFrom( 'sfGuardRememberKey' )->delete( 'sfGuardRememberKey' )->where( 'sfGuardRememberKey.user_id = ?', $user->getId() )->execute();

            // generate new keys
            $key = $this->generateRandomKey();

            // save key
            $rk = new sfGuardRememberKey();
            $rk->set('remember_key', $key );
            $rk->set('user', $user);
            $rk->set('ip_address', $_SERVER[ 'REMOTE_ADDR' ] );
            $rk->save();

            // make key as a cookie
            $remember_cookie = sfConfig::get( 'app_sf_guard_plugin_remember_cookie_name', 'sfRemember' );
            sfContext::getInstance()->getResponse()->setCookie( $remember_cookie, $key, time() + $expiration_age );
        }
    }

    protected function generateRandomKey( $len = 20 )
    {
        $string = '';
        $pool = 'abcdefghijklmnopqrstuvwzyzABCDEFGHIJKLMNOPQRSTUVWZYZ0123456789';
        for ( $i = 1; $i <= $len; $i++ )
        {
            $string .= substr( $pool, rand( 0, 61 ), 1 );
        }

        return md5( $string );
    }

    public function signOut()
    {
        $this->getAttributeHolder()->removeNamespace( 'sfGuardSecurityUser' );
        $this->user = null;
        $this->clearCredentials();
        $this->setAuthenticated( false );
        $expiration_age = sfConfig::get( 'app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600 );
        $remember_cookie = sfConfig::get( 'app_sf_guard_plugin_remember_cookie_name', 'sfRemember' );
        sfContext::getInstance()->getResponse()->setCookie( $remember_cookie, '', time() - $expiration_age );
    }

    public function getGuardUser()
    {
        if ( !$this->user && $id = $this->getAttribute( 'user_id', null, 'sfGuardSecurityUser' ) )
        {
            $this->user = sfDoctrine::getTable( 'sfGuardUser' )->find ( $id );

            if ( !$this->user )
            {
                // the user does not exist anymore in the database
                $this->signOut();

                throw new sfException( 'The user does exist anymore in the database.' );
            }
        }

        return $this->user;
    }
}
