<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Controller.php 7284 2010-09-03 19:28:01Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class User_Widget_ListSignupsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $table = Engine_Api::_()->getDbtable('users', 'user');
    $select = $table->select()
      ->where('search = ?', 1)
      ->where('enabled = ?', 1)
      ->where('verified = ?', 1)
      ->order('creation_date DESC')
      ->limit(4);

    $users = $table->fetchAll($select);

    if( count($users) < 1 )
    {
      return $this->setNoRender();
    }

    $this->view->users = $users;
  }

  public function getCacheKey()
  {
    return Engine_Api::_()->user()->getViewer()->getIdentity();
  }

  public function getCacheSpecificLifetime()
  {
    return 120;
  }
}