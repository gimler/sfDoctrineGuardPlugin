<?php

abstract class PluginsfGuardUserPermission extends BasesfGuardUserPermission
{
  /**
   * Saves the current sfGuardUserPermission object in database.
   *
   * @param Doctrine_Connection $conn A Doctrine_Connection object
   */
  public function save(Doctrine_Connection $conn = null)
  {
    parent::save($conn);

    $this->getsfGuardUser()->reloadGroupsAndPermissions();
  }
}