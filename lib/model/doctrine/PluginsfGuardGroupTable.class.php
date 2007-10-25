<?php
/*
 * Plugin class
 *
 */
abstract class PluginsfGuardGroupTable extends Doctrine_Table
{
    public function retrieveByName( $name )
    {
				$query = new Doctrine_Query();
				$query->from('sfGuardGroup g')->where('g.name = ?', $name)->limit(1);
				
				return $query->execute()->getFirst();
		}
}
