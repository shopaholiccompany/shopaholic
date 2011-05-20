<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: General.php 7376 2010-09-14 05:58:07Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Activity_Form_Admin_Settings_General extends Engine_Form
{
  public function init()
  {
    // Set form attributes
    $this->setTitle('Activity Feed Settings');
    $this->setDescription('ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_DESCRIPTION');

    $actionTypesTable = Engine_Api::_()->getDbTable('actionTypes', 'activity');
    $multiOptions = array();
    $values = array();
    foreach( $actionTypesTable->getActionTypes() as $type ) {
      $multiOptions[$type->type] = 'ADMIN_ACTIVITY_TYPE_' . strtoupper($type->type);
      if( $type->enabled ) {
        $values[] = $type->type;
      }
    }
    
    // Create Elements
    $this->addElement('MultiCheckbox', 'allowed', array(
      'label' => 'Allowed Feed Items',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_ALLOWED_DESCRIPTION',
      'multiOptions' => $multiOptions,
      'value' => $values,
    ));

    $this->addElement('Select', 'length', array(
      'label' => 'Overall Feed Length',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_LENGTH_DESCRIPTION',
      'value' => 5,
      'multiOptions' => array(
        5 =>  "ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LENGTH_OPTION1",
        10 => "ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LENGTH_OPTION2"
      )
    ));

    $this->addElement('Select', 'userlength', array(
      'label' => 'Item Limit Per User',
      'value' => 5,
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_USERLENGTH_DESCRIPTION',
      'multiOptions' => array(
        5 =>  "ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_USERLENGTH_OPTION1",
        10 => "ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_USERLENGTH_OPTION2"
      )
    ));

    $this->addElement('Select', 'liveupdate', array(
      'label' => 'Update Frequency',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_LIVEUPDATE_DESCRIPTION',
      'value' => 120000,
      'multiOptions' => array(
        30000  => 'ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LIVEUPDATE_OPTION1',
        60000  => 'ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LIVEUPDATE_OPTION2',
        120000 => "ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LIVEUPDATE_OPTION3",
        0      => 'ACTIVITY_FORUM_ADMIN_SETTINGS_GENERAL_LIVEUPDATE_OPTION4'
      )
    ));

    $this->addElement('Radio', 'userdelete', array(
      'label' => 'Item Deletion',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_USERDELETE_DESCRIPTION',
      'value' => 1,
      'multiOptions' => array(
        1 => 'Yes, allow members to delete their feed items.',
        0 => 'No, members may not delete their feed items.'
      )
    ));

    $this->addElement('Radio', 'content', array(
      'label' => 'Feed Content',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_CONTENT_DESCRIPTION',
      'value' => 'everyone',
      'multiOptions' => array(
        'everyone' => 'All Members',
        'networks' => 'My Friends & Networks',
        'friends' => 'My Friends'
      )
    ));

    $this->addElement('Radio', 'filter', array(
      'label' => 'Feed Item Filtering',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_FILTER_DESCRIPTION',
      'value' => 1,
      'multiOptions' => array(
        1 => 'Yes, members can choose not to see certain feed item types.',
        0 => 'No, members cannot customize their view of the feed.'
      )
    ));

    $this->addElement('Radio', 'publish', array(
      'label' => 'Item Publishing Option',
      'description' => 'ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_PUBLISH_DESCRIPTION',
      'value' => 1,
      'multiOptions' => array(
        1 => 'Yes, members may specify which item types will NOT be published about them.',
        0 => 'No, members may not specify which actions will NOT be published about them.'
      )
    ));

    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));
  }
}