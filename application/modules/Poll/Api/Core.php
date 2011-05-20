<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Core.php 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Poll_Api_Core extends Core_Api_Abstract
{
  public function getPollSelect( $params = array() )
  {
    $params  = array_merge(array(
      'user_id' => null,
      'sort'    => 'recent',
      'search'  => '',
      'closed'  => 0,
    ), $params);

    $p_table = Engine_Api::_()->getDbTable('polls', 'poll');
    $p_name  = $p_table->info('name');
    $o_table = Engine_Api::_()->getDbTable('options', 'poll');
    $o_name  = $o_table->info('name');

    $select  = $p_table->select()->from($p_name)->where('is_closed = ?', $params['closed']);

    if (!empty($params['user_id']))
      $select->where('user_id = ?', $params['user_id']);


    switch ($params['sort']) {
        case 'popular':
          $select->setIntegrityCheck(false)
                 ->from($o_name, "SUM($o_name.votes) AS total_votes")
                 ->where("$p_name.poll_id = $o_name.poll_id")
                 ->group("$p_name.poll_id");
          $select->order('total_votes DESC')
                 ->order('views DESC');
          break;
        case 'recent':
        default:
          $select->order('creation_date DESC');
          break;
    }

    if (!empty($params['search'])) {
      $search = "%{$params['search']}%";
      // if we do not need to search the Options table, we could just do this:
      // ->where("`title` LIKE ? OR `description` LIKE ?", $search);
      // but since we do, we must do the following join:
      if ('popular' != $sort) {
        $table_options = Engine_Api::_()->getDbTable('options', 'poll')->info('name');
        $select->joinLeft($o_name, "$p_name.poll_id = $o_name.poll_id", '')
               ->where("`title` LIKE ? OR `description` LIKE ? OR $o_name.poll_option LIKE ?", $search)
               ->group("$p_name.poll_id");
      } else
        $select->where("`title` LIKE ? OR `description` LIKE ? OR $o_name.poll_option LIKE ?", $search);
    }
    return $select;
  }

  public function getPollOptions($poll_id)
  {
    $table  = Engine_Api::_()->poll()->api()->getDbtable('options', 'poll');
    $select = $table->select()
                    ->where('poll_id = ?', $poll_id)
                    ->order('poll_option_id');
    return $table->fetchAll($select);
  }

  public function getPollVotes($poll_ids)
  {
    if (is_string($poll_ids))
      $poll_ids = array($poll_ids);

    $poll_votes = array();
    $table  = Engine_Api::_()->poll()->api()->getDbtable('options', 'poll');
    $select = $table->select()
                    ->from($table->info('name'), array(
                        'poll_id',
                        new Zend_Db_Expr('SUM(votes) AS votes'),
                      ))
                    ->group('poll_id')
                    ->order('poll_id');
    if (!empty($poll_ids))
      $select->where('poll_id IN (?)', $poll_ids);

    foreach ($table->fetchAll($select) as $row)
      if (!empty($row))
        $poll_votes[$row->poll_id] = $row->votes;

    return $poll_votes;
  }

  public function setVote($poll_id, $option_id, $user_id=0)
  {
    if (empty($user_id))
      $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    
    $table  = Engine_Api::_()->getDbTable('votes', 'poll');
    $select = $table->select()
                    ->where('poll_id = ?', $poll_id)
                    ->where('user_id = ?', $user_id);
    $row = $table->fetchRow($select);
    if (!empty($row)) {
      $row->poll_option_id = $option_id;
      $row->modified_date  = date('Y-m-d H:i:s');
      $row->save();
    } else {
      Engine_Api::_()->getDbTable('votes', 'poll')->insert(array(
        'poll_id' => $poll_id,
        'user_id' => $user_id,
        'poll_option_id' => $option_id,
        'creation_date' => date('Y-m-d H:i:s'),
      ));
    }

    // we also have to update the poll_options table
    $table  = Engine_Api::_()->getDbTable('votes', 'poll');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($table->info('name'), array(
                      'poll_option_id',
                      new Zend_Db_Expr('COUNT(*) AS votes'),
                    ))
                    ->where('poll_id = ?', $poll_id)
                    ->group('poll_option_id');
    $options = array();
    foreach ($table->fetchAll($select) as $row)
      $options[$row->poll_option_id] = $row->votes;
    
    $table = Engine_Api::_()->getDbTable('options', 'poll');
    $select = $table->select()
                    ->where('poll_id = ?', $poll_id);
    foreach ($table->fetchAll($select) as $row) {
      $votes = isset($options[$row->poll_option_id]) && !empty($options[$row->poll_option_id])
               ? $options[$row->poll_option_id]
               : 0;
      $row->votes = $votes;
      $row->save();
    }
  }

  public function viewerHasVoted($poll_id)
  {
    $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $table   = Engine_Api::_()->poll()->api()->getDbtable('votes', 'poll');
    $select  = $table->select()
                     ->from($table->info('name'), array(
                         'poll_option_id',
                       ))
                     ->where('poll_id = ?', $poll_id)
                     ->where('user_id = ?', $user_id)
                     ->limit(1);
    $row = $table->fetchRow($select);
    if (!empty($row) && isset($row['poll_option_id']))
      return $row['poll_option_id'];
  }
  public function deletePoll($poll_id)
  {
    // first, delete activity feed and its comments/likes
    Engine_Api::_()->getItem('poll', $poll_id)->delete();

    // next, delete poll votes
    Engine_Api::_()->getDbtable('votes', 'poll')->delete(array(
      'poll_id = ?' => $poll_id,
    ));

    // next, delete poll options
    Engine_Api::_()->getDbtable('options', 'poll')->delete(array(
      'poll_id = ?' => $poll_id,
    ));
  }

  /**
   * Gets a paginator for polls
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Paginator
   */
  public function getPollsPaginator($params = array()) {
    return Zend_Paginator::factory($this->getPollSelect($params));
  }
}