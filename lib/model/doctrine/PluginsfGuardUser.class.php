<?php
/*
/*
 * Plugin class
 *
 */
class PluginsfGuardUser extends BasesfGuardUser
{
    protected
        $allPermissions  = null,
				$groupNames      = null;

    public function __toString()
    {
      return $this->get('username');
    }

    public function setPassword( $password )
    {
      # FIXME: why is this necessary?
      if ( !$password )
      {
        return $this->get('password');
      }

      $salt = md5( rand( 100000, 999999 ) . $this->get('username') );
      $this->set('salt', $salt );
      $algorithm = sfConfig::get( 'app_sf_guard_plugin_algorithm_callable', 'sha1' );
      $algorithmAsStr = is_array( $algorithm ) ? $algorithm[ 0 ].'::' . $algorithm[ 1 ] : $algorithm;
      
      if ( !is_callable( $algorithm ) )
      {
        throw new sfException( sprintf( 'The algorithm callable "%s" is not callable.', $algorithmAsStr ) );
      }
      
      $this->set('algorithm', $algorithmAsStr );
      
      $this->rawSet('password', call_user_func_array( $algorithm, array( $salt . $password ) ));
    }

    public function checkPassword( $password )
    {
      if ( $callable = sfConfig::get( 'app_sf_guard_plugin_check_password_callable' ) )
      {
        return call_user_func_array( $callable, array( $this->get('username'), $password ) );
      }
      else
      {
        $algorithm = $this->get('algorithm');
        if ( false !== $pos = strpos( $algorithm, '::' ) )
        {
            $algorithm = array( substr( $algorithm, 0, $pos ), substr( $algorithm, $pos + 2 ) );
        }
        if ( !is_callable( $algorithm ) )
        {
            throw new sfException( sprintf( 'The algorithm callable "%s" is not callable.', $algorithm ) );
        }

        return $this->get('password') == call_user_func_array( $algorithm, array( $this->get('salt') . $password ) );
      }
    }

    public function addGroupByName( $name )
    {
      $group = sfDoctrine::getTable('sfGuardGroup')->retrieveByName( $name );
      
      if ( !$group )
      {
        throw new Exception( sprintf( 'The group "%s" does not exist.', $name ) );
      }
      
      $this->get('groups')->add($group);
    }

    public function addPermissionByName( $name )
    {
      $permission = sfDoctrine::getTable('sfGuardGroup')->retrieveByName( $name );
      
      if ( !$permission->exists() )
      {
        throw new Exception( sprintf( 'The permission "%s" does not exist.', $name ) );
      }
      
      $this->get('permissions')->add($permission);
    }

    public function hasGroup( $name )
    {
      $group = sfDoctrine::queryFrom('sfGuardGroup')->where('sfGuardGroup.name = ? AND sfGuardGroup.users.id = ?', array($name, $this->get('id')))->execute()->getFirst();
      
      return $group->exists();
    }

    public function getGroupNames()
    {
			if( !$this->groupNames )
			{
			 	foreach($this->get('groups') AS $group)
				{
					$this->groupNames[$group->getName()] = $group->getName();
				}
			}

			return $this->groupNames;
    }

    public function hasPermission( $name )
    {
      $permission = sfDoctrine::queryFrom('sfGuardPermission')->where('sfGuardPermission.name = ? AND sfGuardPermission.users.id = ?', array($name, $this->get('id')))->execute()->getFirst();
      
      return $permission->exists();
    }

    // merge of permission in a group + permissions
    public function getAllPermissions()
    {
      if ( !$this->allPermissions )
      {
        $this->allPermissions = array();

        foreach ( $this->get('groups') as $group )
        {
          foreach ( $group->get('permissions') as $permission )
          {
              $this->allPermissions[ $permission->getName() ] = $permission->getName();
          }
        }

				foreach( $this->get('permissions') as $permission )
				{
					$this->allPermissions[ $permission->getName() ] = $permission->getName();
				}
      }

      return $this->allPermissions;
    }

    public function getPermissionNames()
    {
      $q = new Doctrine_Query();
      $names = $q->select('p.name')->from('sfGuardPermission p')->where('p.users.id = ?', $this->get('id'))->execute();
      
      return $names;
    }

    public function getAllPermissionNames()
    {
      return array_keys( $this->getAllPermissions() );
    }

    public function reloadGroupsAndPermissions()
    {
      $this->allPermissions = null;
    }

    public function set($name, $value, $load = true)
    {
      // do nothing if trying to set the phony password_bis field
      if ($name == 'password_bis')
      {
        return;
      }
      
  		return parent::set($name, $value, $load);
    }
    
    public function getEmailAddress()
    {
      throw new Exception('Override this function in your sfGuardUser model so it returns the e-mail address for the user');
    }
    
    static public function hasEmailAddress()
    {
      try {
        $sfGuardUser = new sfGuardUser();
        $sfGuardUser->getEmailAddress();
        
        return true;
      } catch(Exception $e) {
        return false;
      }
    }
    
    public function confirm()
    {
      
    }
    
    public function register($userInfo)
    {
      
    }
}