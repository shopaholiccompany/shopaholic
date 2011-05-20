<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Create.php 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Poll_Form_Create extends Engine_Form
{
  public function init()
  {
    $auth = Engine_Api::_()->authorization()->context;
    $user = Engine_Api::_()->user()->getViewer();


    $this->setTitle('Create Poll')
      ->setDescription('Create your poll below, then click "Create Poll" to start your poll.');
    
    $this->addElement('text', 'title', array(
      'label' => 'Poll Title',
      'required' => true,
      'maxlength' => 63,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63'))
      ),
    ));

    $this->addElement('textarea', 'description', array(
      'label' => 'Description',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '400'))
      ),
    ));

    $this->addElement('textarea', 'options', array(
      'label' => 'Possible Answers',
      'style' => 'display:none;',
    ));

    // Privacy 
    $availableLabels = array(
      'everyone'            => 'Everyone',
      'owner_network'       => 'Friends and Networks',
      'owner_member_member' => 'Friends of Friends',
      'owner_member'        => 'Friends Only',
      'owner'               => 'Just Me'
    );

    // Init profile view
    $view_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('poll', $user, 'auth_view');
    $view_options = array_intersect_key($availableLabels, array_flip($view_options));

    if( count($view_options) >= 1 ) {
      $this->addElement('Select', 'auth_view', array(
        'label' => 'Privacy',
        'description' => 'Who may see this poll?',
        'multiOptions' => $view_options,
        'value' => key($view_options),
      ));
      $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
    }
    
    // Comment
    // Init profile comment
    $comment_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('poll', $user, 'auth_comment');
    $comment_options = array_intersect_key($availableLabels, array_flip($comment_options));

    if( count($comment_options) >= 1 ) {
      $this->addElement('Select', 'auth_comment', array(
        'label' => 'Comment Privacy',
        'description' => 'Who may post comments on this poll?',
        'multiOptions' => $comment_options,
        'value' => key($comment_options),
      ));
      $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
    }

    // Search
    $this->addElement('Checkbox', 'search', array(
      'label' => "Show this poll in search results",
      'value' => 1,
    ));


    $this->addElement('button', 'submit', array(
      'label' => 'Create Poll',
      'type' => 'submit'
    ));
  }
}