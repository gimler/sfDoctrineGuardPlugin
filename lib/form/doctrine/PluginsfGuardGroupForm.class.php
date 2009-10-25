<?php

/**
 * PluginsfGuardGroup form.
 *
 * @package    form
 * @subpackage sfGuardGroup
 * @version    SVN: $Id$
 */
abstract class PluginsfGuardGroupForm extends BasesfGuardGroupForm
{
  public function configure()
  {
    unset(
      $this['sf_guard_user_group_list'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema['sf_guard_group_permission_list']->setLabel('Permissions');
  }
}