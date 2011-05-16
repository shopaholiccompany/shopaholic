<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Controller.php 7363 2010-09-14 01:20:15Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class User_Widget_ListOnlineController extends Engine_Content_Widget_Abstract
{
  protected $_onlineUserCount;
  
  public function indexAction()
  {
    // Get count
    $table = Engine_Api::_()->getItemTable('user');
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');

    $select = $onlineTable->select()
      ->from($onlineTable->info('name'), new Zend_Db_Expr('1 AS garbage'))
      ->group($onlineTable->info('name').'.user_id')
      ->where($onlineTable->info('name').'.user_id > ?', 0)
      ->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'));

    $this->_onlineUserCount = $this->view->count = $count = count($select->query()->fetchAll());
    
    if( !$count )
    {
      return $this->setNoRender();
    }

    // Parse title
    $element = $this->getElement();
    $title = $this->view->translate(array($element->getTitle(), $element->getTitle(), $count), $count);
    $element->setTitle($title);
    $element->setParam('disableTranslate', true);

    // Get users
    $select = $table->select()
      ->from($table->info('name'))
      ->joinRight($onlineTable->info('name'), $onlineTable->info('name').'.user_id = '.$table->info('name').'.user_id', null)
      ->where($onlineTable->info('name').'.user_id > ?', 0)
      ->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
      ->where('search = ?', 1)
      ->where('enabled = ?', 1)
      ->where('verified = ?', 1)
      ->order('active DESC')
      ->group($onlineTable->info('name').'.user_id')
      ->limit(18);

    $this->view->users = $rowset = $table->fetchAll($select);
  }

  public function getCacheKey()
  {
    return Engine_Api::_()->user()->getViewer()->getIdentity();
  }

  public function getCacheSpecificLifetime()
  {
    return 120;
  }

  public function getCacheExtraContent()
  {
    return $this->_onlineUserCount;
  }

  public function setCacheExtraData($data)
  {
    $element = $this->getElement();
    $element->setTitle(sprintf($element->getTitle(), (int) $data));
  }
}