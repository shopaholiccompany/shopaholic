<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: TopicController.php 7381 2010-09-14 21:00:44Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Group_TopicController extends Core_Controller_Action_Standard
{
  public function init()
  {
    if( Engine_Api::_()->core()->hasSubject() ) return;

    /*
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('group_post', $post_id)) )
    {
      Engine_Api::_()->core()->setSubject($post);
    }
    
    else */if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
        null !== ($topic = Engine_Api::_()->getItem('group_topic', $topic_id)) )
    {
      Engine_Api::_()->core()->setSubject($topic);
    }
    
    else if( 0 !== ($group_id = (int) $this->_getParam('group_id')) &&
        null !== ($group = Engine_Api::_()->getItem('group', $group_id)) )
    {
      Engine_Api::_()->core()->setSubject($group);
    }
  }
  
  public function indexAction()
  {
    if( !$this->_helper->requireSubject('group')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid() ) return;
    
    $this->view->group = $group = Engine_Api::_()->core()->getSubject();

    $table = $this->_helper->api()->getDbtable('topics', 'group');
    $select = $table->select()
      ->where('group_id = ?', $group->getIdentity())
      ->order('sticky DESC')
      ->order('modified_date DESC');

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->can_post = $can_post = $this->_helper->requireAuth->setAuthParams(null, null, 'comment')->checkRequire();
    $paginator->setCurrentPageNumber($this->_getParam('page'));
  }
  
  public function viewAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid() ) return;

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->group = $group = $topic->getParentGroup();

    if( !$viewer || !$viewer->getIdentity() || $viewer->getIdentity() != $topic->user_id ) {
      $topic->view_count = new Zend_Db_Expr('view_count + 1');
      $topic->save();
    }
    
    // @todo implement scan to post
    $this->view->post_id = $post_id = (int) $this->_getParam('post');

    $table = $this->_helper->api()->getDbtable('posts', 'group');
    $select = $table->select()
      ->where('group_id = ?', $group->getIdentity())
      ->where('topic_id = ?', $topic->getIdentity())
      ->order('creation_date ASC');
    
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);

    // Skip to page of specified post
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('group_post', $post_id)) )
    {
      $icpp = $paginator->getItemCountPerPage();
      $page = ceil(($post->getPostIndex() + 1) / $icpp);
      $paginator->setCurrentPageNumber($page);
    }

    // Use specified page
    else if( 0 !== ($page = (int) $this->_getParam('page')) )
    {
      $paginator->setCurrentPageNumber($this->_getParam('page'));
    }

    $this->view->auth = $this->_helper->requireAuth->setAuthParams(null, null, 'comment')->checkRequire();

    if( $this->view->auth && !$topic->closed ) {
      $this->view->form = $form = new Group_Form_Post_Create();
      $form->topic_id->setValue($topic->getIdentity());
      $form->ref->setValue($topic->getHref());
    }
  }

  public function createAction()
  {
    if( !$this->_helper->requireSubject('group')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid() ) return;

    $this->view->group = $group = Engine_Api::_()->core()->getSubject('group');
    
    // Make form
    $this->view->form = $form = new Group_Form_Topic_Create();

    // Process form
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )
    {
      $db = $group->getTable()->getAdapter();
      $db->beginTransaction();

      try
      {
        $viewer = $this->_helper->api()->user()->getViewer();
        
        $values = $form->getValues();
        $values['user_id'] = $viewer->getIdentity();
        $values['group_id'] = $group->getIdentity();

        // Create topic
        $topicTable = $this->_helper->api()->getDbtable('topics', 'group');
        $topic = $topicTable->createRow();
        $topic->setFromArray($values);
        $topic->save();

        // Create post
        $values['topic_id'] = $topic->topic_id;

        $postTable = $this->_helper->api()->getDbtable('posts', 'group');
        $post = $postTable->createRow();
        $post->setFromArray($values);
        $post->save();

        // Add activity
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $topic, 'group_topic_create');
        if( $action ){
          $action->attach($topic);
        }
        
        $db->commit();

        // Redirect to the post
        $this->_redirectCustom($post);
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
    }

  }
  
  public function postAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid() ) return;

    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->group = $group = $topic->getParentGroup();

    if( $topic->closed ) {
      $this->view->status = false;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('This has been closed for posting.');
      return;
    }
    
    // Make form
    $this->view->form = $form = new Group_Form_Post_Create();

    // Process form
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )
    {
      $db = $group->getTable()->getAdapter();
      $db->beginTransaction();

      try
      {
        $viewer = $this->_helper->api()->user()->getViewer();

        $values = $form->getValues();
        $values['user_id'] = $viewer->getIdentity();
        $values['group_id'] = $group->getIdentity();
        $values['topic_id'] = $topic->getIdentity();

        // Create post
        $postTable = $this->_helper->api()->getDbtable('posts', 'group');
        $post = $postTable->createRow();
        $post->setFromArray($values);
        $post->save();

        // Notifications
        $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
        $topicOwner = $topic->getOwner();
        
        // Activity
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $topic, 'group_topic_reply');
        if( $action ){
          $action->attach($post, Activity_Model_Action::ATTACH_DESCRIPTION);
        }

        // Notification of origin
        if( !$viewer->isSelf($topicOwner) ) {
          $notifyApi->addNotification($topicOwner, $viewer, $post, 'group_discussion_response');
        }

        // Notification of replicants
        $postUserSelect = new Zend_Db_Select($postTable->getAdapter());
        $postUserSelect
          ->from($postTable->info('name'), 'user_id')
          ->where('topic_id = ?', $topic->getIdentity())
          ;

        $notifyUserIds = array();
        foreach( $postUserSelect->query()->fetchAll() as $userInfo ) {
          if( $topic->user_id == $userInfo['user_id'] || $viewer->getIdentity() == $userInfo['user_id'] ) continue;
          $notifyUserIds[] = $userInfo['user_id'];
        }
        $notifyUserIds = array_filter(array_unique($notifyUserIds));

        $notifyUsers = Engine_Api::_()->getItemMulti('user', $notifyUserIds);
        foreach( $notifyUsers as $notifyUser )
        {
          $notifyApi->addNotification($notifyUser, $viewer, $post, 'group_discussion_reply');
        }

        $db->commit();
        
        // Redirect to the post
        $this->_redirectCustom($post);
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
    }
  }

  public function stickyAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() ) return;
    
    $topic = Engine_Api::_()->core()->getSubject('group_topic');

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $topic = Engine_Api::_()->core()->getSubject();
      $topic->sticky = ( null === $this->_getParam('sticky') ? !$topic->sticky : (bool) $this->_getParam('sticky') );
      $topic->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->_redirectCustom($topic);
  }

  public function closeAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() ) return;

    $topic = Engine_Api::_()->core()->getSubject();
    
    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $topic = Engine_Api::_()->core()->getSubject();
      $topic->closed = ( null === $this->_getParam('closed') ? !$topic->closed : (bool) $this->_getParam('closed') );
      $topic->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->_redirectCustom($topic);
  }

  public function renameAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() ) return;

    $topic = Engine_Api::_()->core()->getSubject();

    $this->view->form = $form = new Group_Form_Topic_Rename();

    if( !$this->getRequest()->isPost() )
    {
      $form->title->setValue(htmlspecialchars_decode($topic->title));
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $title = htmlspecialchars($form->getValue('title'));

      $topic = Engine_Api::_()->core()->getSubject();
      $topic->title = $title;
      $topic->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Topic renamed.')),
      'layout' => 'default-simple',
      'parentRefresh' => true,
    ));
  }

  public function deleteAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() ) return;

    $topic = Engine_Api::_()->core()->getSubject();

    $this->view->form = $form = new Group_Form_Topic_Delete();

    if( !$this->getRequest()->isPost() )
    {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $topic = Engine_Api::_()->core()->getSubject();
      $group = $topic->getParent('group');
      $topic->delete();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Topic deleted.')),
      'layout' => 'default-simple',
      'parentRedirect' => $group->getHref(),
    ));
  }
}