<?php

/**
 * PluginsfGuardGroupPermission form.
 *
 * @package    form
 * @subpackage sfGuardGroupPermission
 * @version    SVN: $Id$
 */
abstract class PluginsfGuardGroupPermissionForm extends BasesfGuardGroupPermissionForm
{
  public function configure()
  {
    unset(
      $this['created_at'],
      $this['updated_at']
    );
  }
}