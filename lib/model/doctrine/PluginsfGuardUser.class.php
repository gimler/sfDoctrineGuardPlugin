<?php

abstract class PluginsfGuardUser extends BasesfGuardUser
{
  protected
    $profile        = null,
    $groups         = null,
    $permissions    = null,
    $allPermissions = null;

  /**
   * Returns the string representation of the object.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getUsername();
  }

  /**
   * Sets the user password.
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    if (!$password && 0 == strlen($password))
    {
      return;
    }

    if (!$salt = $this->getSalt())
    {
      $salt = md5(rand(100000, 999999).$this->getUsername());
      $this->setSalt($salt);
    }
    $modified = $this->getModified();
    if ((!$algorithm = $this->getAlgorithm()) || (isset($modified['algorithm']) && $modified['algorithm'] == $this->getTable()->getDefaultValueOf('algorithm')))
    {
      $algorithm = sfConfig::get('app_sf_guard_plugin_algorithm_callable', 'sha1');
    }
    $algorithmAsStr = is_array($algorithm) ? $algorithm[0].'::'.$algorithm[1] : $algorithm;
    if (!is_callable($algorithm))
    {
      throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
    }
    $this->setAlgorithm($algorithmAsStr);

    parent::_set('password', call_user_func_array($algorithm, array($salt.$password)));
  }

  /**
   * Sets the second password.
   *
   * @param string $password
   */
  public function setPasswordBis($password)
  {
  }

  /**
   * Returns whether or not the given password is valid.
   *
   * @param string $password
   * @return boolean
   */
  public function checkPassword($password)
  {
    if ($callable = sfConfig::get('app_sf_guard_plugin_check_password_callable'))
    {
      return call_user_func_array($callable, array($this->getUsername(), $password, $this));
    }
    else
    {
      return $this->checkPasswordByGuard($password);
    }
  }

  /**
   * Returns whether or not the given password is valid.
   *
   * @param string $password
   * @return boolean
   * @throws sfException
   */
  public function checkPasswordByGuard($password)
  {
    $algorithm = $this->getAlgorithm();
    if (false !== $pos = strpos($algorithm, '::'))
    {
      $algorithm = array(substr($algorithm, 0, $pos), substr($algorithm, $pos + 2));
    }
    if (!is_callable($algorithm))
    {
      throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithm));
    }

    return $this->getPassword() == call_user_func_array($algorithm, array($this->getSalt().$password));
  }

  /**
   * Adds the user a new group from its name.
   *
   * @param string $name The group name
   * @param Doctrine_Connection $con A Doctrine_Connection object
   * @throws sfException
   */
  public function addGroupByName($name, $con = null)
  {
    $group = Doctrine::getTable('sfGuardGroup')->findOneByName($name);
    if (!$group)
    {
      throw new sfException(sprintf('The group "%s" does not exist.', $name));
    }

    $ug = new sfGuardUserGroup();
    $ug->setsfGuardUser($this);
    $ug->setsfGuardGroup($group);

    $ug->save($con);
  }

  /**
   * Adds the user a permission from its name.
   *
   * @param string $name The permission name
   * @param Doctrine_Connection $con A Doctrine_Connection object
   * @throws sfException
   */
  public function addPermissionByName($name, $con = null)
  {
    $permission = Doctrine::getTable('sfGuardPermission')->findOneByName($name);
    if (!$permission)
    {
      throw new sfException(sprintf('The permission "%s" does not exist.', $name));
    }

    $up = new sfGuardUserPermission();
    $up->setsfGuardUser($this);
    $up->setsfGuardPermission($permission);

    $up->save($con);
  }

  /**
   * Checks whether or not the user belongs to the given group.
   *
   * @param string $name The group name
   * @return boolean
   */
  public function hasGroup($name)
  {
    $this->loadGroupsAndPermissions();

    return isset($this->groups[$name]);
  }

  /**
   * Returns all related groups names.
   *
   * @return array
   */
  public function getGroupNames()
  {
    $this->loadGroupsAndPermissions();

    return array_keys($this->groups);
  }

  /**
   * Returns whether or not the user has the given permission.
   *
   * @return boolean
   */
  public function hasPermission($name)
  {
    $this->loadGroupsAndPermissions();

    return isset($this->allPermissions[$name]);
  }

  /**
   * Returns an array of all user's permissions names.
   *
   * @return array
   */
  public function getPermissionNames()
  {
    $this->loadGroupsAndPermissions();
    return array_keys($this->allPermissions);
  }

  /**
   * Returns an array containing all permissions, including groups permissions
   * and single permissions.
   *
   * @return array
   */
  public function getAllPermissions()
  {
    if (!$this->allPermissions)
    {
      $this->allPermissions = array();
      $permissions = $this->getPermissions();
      foreach ($permissions as $permission)
      {
        $this->allPermissions[$permission->getName()] = $permission;
      }

      foreach ($this->getGroups() as $group)
      {
        foreach ($group->getPermissions() as $permission)
        {
          $this->allPermissions[$permission->getName()] = $permission;
        }
      }
    }

    return $this->allPermissions;
  }

  /**
   * Returns an array of all permission names.
   *
   * @return array
   */
  public function getAllPermissionNames()
  {
    return array_keys($this->getAllPermissions());
  }

  /**
   * Loads the user's groups and permissions.
   *
   */
  public function loadGroupsAndPermissions()
  {
    $this->getAllPermissions();
    
    if (!$this->permissions)
    {
      $permissions = $this->getPermissions();
      foreach ($permissions as $permission)
      {
        $this->permissions[$permission->getName()] = $permission;
      }
    }
    
    if (!$this->groups)
    {
      $groups = $this->getGroups();
      foreach ($groups as $group)
      {
        $this->groups[$group->getName()] = $group;
      }
    }
  }

  /**
   * Reloads the user's groups and permissions.
   *
   */
  public function reloadGroupsAndPermissions()
  {
    $this->groups         = null;
    $this->permissions    = null;
    $this->allPermissions = null;
  }

  /**
   * Deletes the sfGuardUser and its associated profile object if exists.
   *
   * @param Doctrine_Connection $conn A Doctrine_Connection object
   * @return int Number of affected rows
   */
  public function delete(Doctrine_Connection $conn = null)
  {
    try
    {
      if ($profile = $this->getProfile())
      {
        $profile->delete();
      }
    }
    catch (Exception $e)
    {
    }

    return parent::delete($conn);
  }

  /**
   * Sets the password hash.
   *
   * @param string $v
   */
  public function setPasswordHash($v)
  {
    if (!is_null($v) && !is_string($v))
    {
      $v = (string) $v;
    }

    if ($this->password !== $v)
    {
      $this->_set('password', $v);
    }
  }
}
