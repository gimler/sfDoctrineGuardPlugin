<?php
/*
/*
 * Plugin class
 *
 */
abstract class PluginsfGuardPermissionTable extends Doctrine_Table
{
    public function retrieveByName( $name )
    {
        return $this->createQuery()->where( 'sfGuardPermission.name = ?', $name )->execute()->getFirst();
    }
}
