<?php

class PluginsfGuardUserTable extends Doctrine_Table
{
  /**
   * Retrieves a sfGuardUser object from his username and is_active flag.
   *
   * @param string $username The username
   * @param boolean $isActive The user's status
   * @return sfGuardUser
   */
  public function retrieveByUsername($username, $isActive = true)
  {
    return Doctrine::getTable('sfGuardUser')->createQuery('u')
            ->where('u.username = ?', $username)
            ->addWhere('u.is_active = ?', $isActive)
            ->fetchOne();
  }
}
