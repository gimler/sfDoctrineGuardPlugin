<?php

/**
 * PluginsfGuardPermission form.
 *
 * @package    form
 * @subpackage sfGuardPermission
 * @version    SVN: $Id$
 */
abstract class PluginsfGuardPermissionForm extends BasesfGuardPermissionForm
{
  public function configure()
  {
    unset($this['sf_guard_user_permission_list']);

    $this->widgetSchema['sf_guard_group_permission_list']->setLabel('Groups');
  }
}