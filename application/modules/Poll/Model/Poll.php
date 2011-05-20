<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Poll.php 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Poll_Model_Poll extends Core_Model_Item_Abstract
{
  protected $_parent_type = 'user';

  //protected $_owner_type = 'user';
  protected $_parent_is_owner = true;
  // needed for core_model_item

  // Interfaces
  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array())
  {
    $slug = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9-]+/i', '-', strtolower($this->getTitle()))), '-');

    $params = array_merge(array(
      'route' => 'poll_view',
      'reset' => true,
      'user_id' => $this->user_id,
      'poll_id' => $this->poll_id,
      'slug' => $slug,
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }
  
  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   **/
  public function comments()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   **/
  public function likes()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  public function getOptions()
  {
    return Engine_Api::_()->getDbtable('options', 'poll')->fetchAll(array(
      'poll_id = ?' => $this->getIdentity(),
    ));
  }

  public function viewerVoted()
  {
    $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $row     = Engine_Api::_()->getDbtable('votes', 'poll')->fetchRow(array(
      'poll_id = ?' => $this->getIdentity(),
      'user_id = ?' => $user_id,
    ));
    return $row
           ? $row->poll_option_id
           : false;
  }

  public function voteCount()
  {
    $table  = Engine_Api::_()->getDbtable('votes', 'poll');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($table->info('name'), 'COUNT(*) AS count')
                    ->where('poll_id = ?', $this->getIdentity());
    return $table->fetchRow($select)->count;
  }

  public function vote($option_id)
  {
    $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $table   = Engine_Api::_()->getDbTable('votes', 'poll');
    $row     = $table->fetchRow(array(
      'poll_id = ?' => $this->getIdentity(),
      'user_id = ?' => $user_id,
    ));
    if (!$row) {
      $row   = $table->createRow(array(
        'poll_id' => $this->getIdentity(),
        'user_id' => $user_id,
        'creation_date' => date("Y-m-d H:i:s"),
      ));
    }
    $row->poll_option_id = $option_id;
    $row->modified_date  = date("Y-m-d H:i:s");
    $row->save();

    // We also have to update the poll_options table
    // To avoid poll values getting out of sync, update all poll option counts
    foreach ($this->getOptions() as $option) {
      // $table is still set to poll_votes
      $select = $table->select()
                      ->setIntegrityCheck(false)
                      ->from($table->info('name'), 'COUNT(*) AS count')
                      ->where('poll_id = ?', $this->getIdentity())
                      ->where('poll_option_id = ?', $option->poll_option_id)
                      ->limit(1);
      $option->votes = $table->fetchRow($select)->count;
      $option->save();
    }

    // Update internal vote count
    $this->vote_count = new Zend_Db_Expr('vote_count + 1');
    $this->save();
  }

  protected function _insert()
  {
    if( null === $this->search ) {
      $this->search = 1;
    }

    parent::_insert();
  }
}