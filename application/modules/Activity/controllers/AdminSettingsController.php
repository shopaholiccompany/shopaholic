<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: AdminSettingsController.php 7378 2010-09-14 06:12:41Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Activity_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function generalAction()
  {
    // Make form
    $this->view->form = $form = new Activity_Form_Admin_Settings_General();

    // Populate settings
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $values = $settings->activity;
    unset($values['allowed']);
    $form->populate($values);


    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }


    // Process
    $values = $form->getValues();
    $allowed = $values['allowed'];
    $list = array_keys($form->getElement('allowed')->getMultiOptions());
    $disallowed = array_diff($list, $allowed);
    unset($values['allowed']);
    
    // Save settings
    $settings->activity = $values;

    // Save action type settings
    if( !empty($disallowed) && is_array($disallowed) ) {
      $actionTypesTable = Engine_Api::_()->getDbTable('actionTypes', 'activity');
      $actionTypesTable->update(array(
        'enabled' => 0,
      ), array(
        'type IN(?)' => (array) $disallowed,
      ));
    }
  }
}