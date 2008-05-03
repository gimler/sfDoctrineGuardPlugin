<?php

/**
 * sfGuardUser form base class.
 *
 * @package    form
 * @subpackage sf_guard_user
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                            => new sfWidgetFormInputHidden(),
      'username'                      => new sfWidgetFormInput(),
      'algorithm'                     => new sfWidgetFormInput(),
      'salt'                          => new sfWidgetFormInput(),
      'password'                      => new sfWidgetFormInput(),
      'is_active'                     => new sfWidgetFormInputCheckbox(),
      'is_super_admin'                => new sfWidgetFormInputCheckbox(),
      'last_login'                    => new sfWidgetFormDateTime(),
      'email_address'                 => new sfWidgetFormInput(),
      'created_at'                    => new sfWidgetFormDateTime(),
      'updated_at'                    => new sfWidgetFormDateTime(),
      'sf_guard_user_group_list'      => new sfWidgetFormDoctrineSelectMany(array('model' => 'sfGuardGroup')),
      'sf_guard_user_permission_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'id'                            => new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'column' => 'id', 'required' => false)),
      'username'                      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'algorithm'                     => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'                      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'                     => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'                => new sfValidatorBoolean(array('required' => false)),
      'last_login'                    => new sfValidatorDateTime(array('required' => false)),
      'email_address'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'                    => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                    => new sfValidatorDateTime(array('required' => false)),
      'sf_guard_user_group_list'      => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardGroup', 'required' => false)),
      'sf_guard_user_permission_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sf_guard_user_group_list']))
    {
      $values = array();
      foreach ($this->object->Groups as $obj)
      {
        $values[] = $obj->group_id;
      }

      $this->setDefault('sf_guard_user_group_list', $values);
    }

    if (isset($this->widgetSchema['sf_guard_user_permission_list']))
    {
      $values = array();
      foreach ($this->object->Permissions as $obj)
      {
        $values[] = $obj->permission_id;
      }

      $this->setDefault('sf_guard_user_permission_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->savesfGuardUserGroupList($con);
    $this->savesfGuardUserPermissionList($con);
  }

  public function savesfGuardUserGroupList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sf_guard_user_group_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $q = Doctrine_Query::create()
          ->delete()
          ->from('sfGuardUserGroup r')
          ->where('r.user_id', $this->object->identifier())
          ->execute();

    $values = $this->getValue('sf_guard_user_group_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new sfGuardUserGroup();
        $obj->user_id = $this->object->getPrimaryKey();
        $obj->group_id = $value;
        $obj->save();
      }
    }
  }

  public function savesfGuardUserPermissionList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sf_guard_user_permission_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $q = Doctrine_Query::create()
          ->delete()
          ->from('sfGuardUserPermission r')
          ->where('r.user_id', $this->object->identifier())
          ->execute();

    $values = $this->getValue('sf_guard_user_permission_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new sfGuardUserPermission();
        $obj->user_id = $this->object->getPrimaryKey();
        $obj->permission_id = $value;
        $obj->save();
      }
    }
  }

}