<?php
/*
 * Plugin class
 *
 */
class PluginsfGuardUserTable extends Doctrine_Table
{
	  # should this be a static function?
    static public function retrieveByUsername( $username, $isActive = true )
    {
      $query = new Doctrine_Query();
      return $query->from('sfGuardUser u')->where( 'u.username = ? AND u.is_active = ?', array( $username, $isActive ) )->execute()->getFirst();
    }

		static function retrieveByUsernameOrEmailAddress($usernameOrEmail)
		{
		  throw new Exception('Override this function in your sfGuardUserTable class so it queries for the user based on the username or email address');
		}
}
