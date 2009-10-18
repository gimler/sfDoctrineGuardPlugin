<?php

abstract class PluginsfGuardUserGroup extends BasesfGuardUserGroup
{
  /**
   * Saves the current sfGuardUserGroup object in database.
   *
   * @param Doctrine_Connection $conn A Doctrine_Connection object
   */
  public function save(Doctrine_Connection $conn = null)
  {
    parent::save($conn);

    $this->getsfGuardUser()->reloadGroupsAndPermissions();
  }
}