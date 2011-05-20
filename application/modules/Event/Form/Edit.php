<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Edit.php 7244 2010-09-01 01:49:53Z john $
 * @author     Sami
 */

/**
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Event_Form_Edit extends Engine_Form
{
  protected $_parent_type;

  protected $_parent_id;

  public function setParent_type($value)
  { 
    $this->_parent_type = $value;
  }

  public function setParent_id($value)
  {
    $this->_parent_id = $value;
  }

  public function init()
  {
    $user = Engine_Api::_()->user()->getViewer();
    $this->setTitle('Edit Event')
      ->setAttrib('id', 'event_create_form')
      ->setMethod("POST")
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
      
    // Title
    $this->addElement('Text', 'title', array(
      'label' => 'Event Name',
      'allowEmpty' => false,
      'required' => true,
      'validators' => array(
        array('NotEmpty', true),
        array('StringLength', false, array(1, 64)),
      ),
      'filters' => array(
        'StripTags',
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
      ),
    ));

    $title = $this->getElement('title');

    // Description
    $this->addElement('Textarea', 'description', array(
      'label' => 'Event Description',
      'maxlength' => '512',
      'filters' => array(
        new Engine_Filter_Censor(),
      ),
    ));

    // Start time
    $start = new Engine_Form_Element_CalendarDateTime('starttime');
    $start->setLabel("Start Time");
    $this->addElement($start);

    // End time
    $end = new Engine_Form_Element_CalendarDateTime('endtime');
    $end->setLabel("End Time");
    $this->addElement($end);
    
    // Host
    if ($this->_parent_type == 'user')
    {
      $this->addElement('Text', 'host', array(
        'label' => 'Host',
        'filters' => array(
          new Engine_Filter_Censor(),
        ),
      ));
    }
    // Location
    $this->addElement('Text', 'location', array(
      'label' => 'Location',
      'filters' => array(
        new Engine_Filter_Censor(),
      ),
    ));

    // Photo
    $this->addElement('File', 'photo', array(
      'label' => 'Main Photo'
    ));
    $this->photo->addValidator('Extension', false, 'jpg,png,gif');

    // Category
    $this->addElement('Select', 'category_id', array(
      'label' => 'Event Category',
      'multiOptions' => array(
        '0' => ' '
      ),
    ));

    // Search
    $this->addElement('Checkbox', 'search', array(
      'label' => 'People can search for this event',
      'value' => 1,
    ));

    // Approval
    $this->addElement('Checkbox', 'approval', array(
      'label' => 'People must be invited to RSVP for this event',
    ));

    // Invite
    $this->addElement('Checkbox', 'auth_invite', array(
      'label' => 'Invited guests can invite other people as well',
      'value' => 1,
    ));
    
    // Privacy
    $view_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('event', $user, 'auth_view');
    $comment_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('event', $user, 'auth_comment');
    $photo_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('event', $user, 'auth_photo');

    if( $this->_parent_type == 'user' ) {
      $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member'  => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'member' => 'Event Guests Only',
        'owner' => 'Just Me'
      );
      $view_options = array_intersect_key($availableLabels, array_flip($view_options));
      $comment_options = array_intersect_key($availableLabels, array_flip($comment_options));
      $photo_options = array_intersect_key($availableLabels, array_flip($photo_options));

    } else if( $this->_parent_type == 'group' ) {

      $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'Registered Members',
        'parent_member' => 'Group Members',
        'member' => 'Event Guests Only',
        'owner' => 'Just Me',
      );
      $view_options = array_intersect_key($availableLabels, array_flip($view_options));
      $comment_options = array_intersect_key($availableLabels, array_flip($comment_options));
      $photo_options = array_intersect_key($availableLabels, array_flip($photo_options));
    }

    // View
    if( !empty($view_options) && count($view_options) > 1 ) {
      $this->addElement('Select', 'auth_view', array(
        'label' => 'Privacy',
        'description' => 'Who may see this event?',
        'multiOptions' => $view_options,
        'value' => key($view_options),
      ));    
    }

    // Comment
    if( !empty($comment_options) && count($comment_options) > 1 ) {
      $this->addElement('Select', 'auth_comment', array(
        'label' => 'Comment Privacy',
        'description' => 'Who may post comments on this event?',
        'multiOptions' => $comment_options,
        'value' => key($comment_options),
      ));
    }

    if( !empty($photo_options) && count($photo_options) > 1 ) {
      $this->addElement('Select', 'auth_photo', array(
        'label' => 'Photo Uploads',
        'description' => 'Who may upload photos to this event?',
        'multiOptions' => $photo_options,
        'value' => key($photo_options),
      ));
    }

    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
  }
}