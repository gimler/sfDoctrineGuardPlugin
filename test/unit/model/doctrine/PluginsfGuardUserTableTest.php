<?php

/**
 * PluginsfGuardUserTable tests.
 */
include dirname(__FILE__).'/../../../../../../test/bootstrap/unit.php';

$t = new lime_test(7);

$databaseManager = new sfDatabaseManager($configuration);
$table = Doctrine::getTable('sfGuardUser');

// ::retrieveByUsername()
$t->diag('::retrieveByUsername()');

$table->createQuery()->delete()->execute();

$inactiveUser = new sfGuardUser();
$inactiveUser->username = 'inactive_user';
$inactiveUser->password = 'password';
$inactiveUser->is_active = false;
$inactiveUser->save();

$activeUser = new sfGuardUser();
$activeUser->username = 'active_user';
$activeUser->password = 'password';
$activeUser->is_active = true;
$activeUser->save();

$t->is(PluginsfGuardUserTable::retrieveByUsername('invalid'), null, '::retrieveByUsername() returns "null" if username is invalid');
$t->is(PluginsfGuardUserTable::retrieveByUsername('inactive_user'), null, '::retrieveByUsername() returns "null" if user is inactive');
$t->isa_ok(PluginsfGuardUserTable::retrieveByUsername('inactive_user', false), 'sfGuardUser', '::retrieveByUsername() returns an inactive user when second parameter is false');
$t->isa_ok(PluginsfGuardUserTable::retrieveByUsername('active_user'), 'sfGuardUser', '::retrieveByUsername() returns an active user');
$t->is(PluginsfGuardUserTable::retrieveByUsername('active_user', false), null, '::retrieveByUsername() returns "null" if user is active and second parameter is false');
$t->isa_ok($table->retrieveByUsername('active_user'), 'sfGuardUser', '::retrieveByUsername() can be called non-statically');

try
{
  PluginsfGuardUserTable::retrieveByUsername(null);
  $t->pass('::retrieveByUsername() does not throw an exception if username is null');
}
catch (Exception $e)
{
  $t->diag($e->getMessage());
  $t->fail('::retrieveByUsername() does not throw an exception if username is null');
}
