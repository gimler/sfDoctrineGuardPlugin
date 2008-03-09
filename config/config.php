<?php

if (sfConfig::get('app_sfGuardPlugin_routes_register', true) && in_array('sfGuardAuth', sfConfig::get('sf_enabled_modules')))
{
  $this->dispatcher->connect('routing.load_configuration', array('sfGuardDoctrineRouting', 'listenToRoutingLoadConfigurationEvent'));
}
