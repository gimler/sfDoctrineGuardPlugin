<?php
/*
 * Plugin class
 *
 */
class PluginsfGuardPermission extends BasesfGuardPermission
{
    public function __toString()
    {
        return $this->get( 'name' );
    }
}
