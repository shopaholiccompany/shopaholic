<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Vote.php 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Poll_Form_Index_Vote extends Engine_Form
{
  public function init()
  {
    $this->addElement('text', 'title', array(
        'label' => 'Poll Title',
        'required' => true,
        'filters' => array(
          new Engine_Filter_Censor(),
        ),
      ));
    $this->addElement('textarea', 'description', array(
        'label' => 'Description',
        'filters' => array(
          new Engine_Filter_Censor(),
        ),
      ));
    $this->addElement('text', 'options', array(
        'label' => 'Possible Answers',
        'isArray' => TRUE,
        'id' => 'firstOptionElement',
        'filters' => array(
          new Engine_Filter_Censor(),
        ),
      ));
    $this->addElement('submit', 'Create Poll', array(
        'value' => 'Create Poll',
      ));
  }

  public function save()
  {
    $db_polls       = Engine_Api::_()->poll()->api()->getDbtable('polls', 'poll');
    $db_pollOptions = Engine_Api::_()->poll()->api()->getDbtable('pollOptions', 'poll');
    $db_pollVotes   = Engine_Api::_()->poll()->api()->getDbtable('pollVotes', 'poll');

    $db_polls->getAdapter()->beginTransaction();
    $db_pollOptions->getAdapter()->beginTransaction();

    try {
      $poll = $db_polls->createRow();
      $poll->user_id       = Engine_Api::_()->user()->getViewer()->getIdentity();
      $poll->is_closed     = 0;
      $poll->title         = $this->getElement('title')->getValue();
      $poll->description   = $this->getElement('description')->getValue();
      $poll->creation_date = date('Y-m-d H:i:s');
      $poll->save();

      foreach ($this->getElement('options')->getValue() as $option) {
        if (trim($option) != '') {
          $row = $db_pollOptions->createRow();
          $row->poll_id      = $poll->poll_id;
          $row->poll_option  = $option;
          $row->save();
        }
      }

      $db_polls->getAdapter()->rollBack();
      $db_pollOptions->getAdapter()->rollBack();
      #$db->commit();
    } catch (Zend_Mail_Transport_Exception $e) {
      $db_polls->getAdapter()->rollBack();
      $db_pollOptions->getAdapter()->rollBack();
      throw $e;
    }
  }
}