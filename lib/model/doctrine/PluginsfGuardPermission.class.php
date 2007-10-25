<?php
/*
 * Plugin class
 *
 */
abstract class PluginsfGuardPermission extends BasesfGuardPermission
{
    public function __toString()
    {
        return $this->get( 'name' );
    }
}
