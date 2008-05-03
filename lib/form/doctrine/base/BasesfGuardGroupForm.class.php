<?php

/**
 * sfGuardGroup form base class.
 *
 * @package    form
 * @subpackage sf_guard_group
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfGuardGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'name'                           => new sfWidgetFormInput(),
      'description'                    => new sfWidgetFormInput(),
      'created_at'                     => new sfWidgetFormDateTime(),
      'updated_at'                     => new sfWidgetFormDateTime(),
      'sf_guard_user_group_list'       => new sfWidgetFormDoctrineSelectMany(array('model' => 'sfGuardUser')),
      'sf_guard_group_permission_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorDoctrineChoice(array('model' => 'sfGuardGroup', 'column' => 'id', 'required' => false)),
      'name'                           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'                    => new sfValidatorString(array('max_length' => 1000, 'required' => false)),
      'created_at'                     => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                     => new sfValidatorDateTime(array('required' => false)),
      'sf_guard_user_group_list'       => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardUser', 'required' => false)),
      'sf_guard_group_permission_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardGroup';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sf_guard_user_group_list']))
    {
      $values = array();
      foreach ($this->object->Users as $obj)
      {
        $values[] = $obj->user_id;
      }

      $this->setDefault('sf_guard_user_group_list', $values);
    }

    if (isset($this->widgetSchema['sf_guard_group_permission_list']))
    {
      $values = array();
      foreach ($this->object->Permissions as $obj)
      {
        $values[] = $obj->permission_id;
      }

      $this->setDefault('sf_guard_group_permission_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->savesfGuardUserGroupList($con);
    $this->savesfGuardGroupPermissionList($con);
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
          ->where('r.group_id', $this->object->identifier())
          ->execute();

    $values = $this->getValue('sf_guard_user_group_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new sfGuardUserGroup();
        $obj->group_id = $this->object->getPrimaryKey();
        $obj->user_id = $value;
        $obj->save();
      }
    }
  }

  public function savesfGuardGroupPermissionList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sf_guard_group_permission_list']))
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
          ->from('sfGuardGroupPermission r')
          ->where('r.group_id', $this->object->identifier())
          ->execute();

    $values = $this->getValue('sf_guard_group_permission_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new sfGuardGroupPermission();
        $obj->group_id = $this->object->getPrimaryKey();
        $obj->permission_id = $value;
        $obj->save();
      }
    }
  }

}