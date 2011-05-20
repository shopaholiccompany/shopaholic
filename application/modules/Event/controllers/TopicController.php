<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: TopicController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Event_TopicController extends Core_Controller_Action_Standard
{
  public function init()
  {
    if( Engine_Api::_()->core()->hasSubject() ) return;

    /*
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('event_post', $post_id)) )
    {
      Engine_Api::_()->core()->setSubject($post);
    }
    
    else */if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
        null !== ($topic = Engine_Api::_()->getItem('event_topic', $topic_id)) )
    {
      Engine_Api::_()->core()->setSubject($topic);
    }
    
    else if( 0 !== ($event_id = (int) $this->_getParam('event_id')) &&
        null !== ($event = Engine_Api::_()->getItem('event', $event_id)) )
    {
      Engine_Api::_()->core()->setSubject($event);
    }

    $this->_helper->requireUser->addActionRequires(array(
      'close', 'create', 'delete', 'post', 'rename', 'reply', 'sticky'
    ));

    $this->_helper->requireSubject->setActionRequireTypes(array(
      'close' => 'event_topic',
      'create' => 'event',
      'delete' => 'event_topic',
      'index' => 'event',
      'post' => 'event_topic',
      'rename' => 'event_topic',
      'reply' => 'event_topic',
      'sticky' => 'event_topic',
      'view' => 'event_topic',
    ));
  }
  
  public function indexAction()
  {
    if( !$this->_helper->requireSubject('event')->isValid() ) return;
    //if( !$this->_helper->requireAuth()->setAuthParams()->isValid() ) return;

    $this->view->event = $event = Engine_Api::_()->core()->getSubject();
    
    $table = $this->_helper->api()->getDbtable('topics', 'event');
    $select = $table->select()
      ->where('event_id = ?', $event->getIdentity())
      ->order('sticky DESC')
      ->order('modified_date DESC');

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->can_post = $can_post = $this->_helper->requireAuth->setAuthParams(null, null, 'comment')->checkRequire();
    $paginator->setCurrentPageNumber($this->_getParam('page'));
  }
  
  public function viewAction()
  {
    if( !$this->_helper->requireSubject('event_topic')->isValid() ) return;
    //if( !$this->_helper->requireAuth()->setAuthParams()->isValid() ) return;

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->event = $event = $topic->getParentEvent();

    if( !$viewer || !$viewer->getIdentity() || $viewer->getIdentity() != $topic->user_id ) {
      $topic->view_count = new Zend_Db_Expr('view_count + 1');
      $topic->save();
    }

    // @todo implement scan to post
    $this->view->post_id = $post_id = (int) $this->_getParam('post');

    $table = $this->_helper->api()->getDbtable('posts', 'event');
    $select = $table->select()
      ->where('event_id = ?', $event->getIdentity())
      ->where('topic_id = ?', $topic->getIdentity())
      ->order('creation_date ASC');
    
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);

    // Skip to page of specified post
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('event_post', $post_id)) )
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
      $this->view->form = $form = new Event_Form_Post_Create();
      $form->topic_id->setValue($topic->getIdentity());
      $form->ref->setValue($topic->getHref());
    }
  }

  public function createAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireSubject('event')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid() ) return;

    $this->view->event = $event = $this->_helper->api()->core()->getSubject();
    if( !$event instanceof Event_Model_Event )
    {
      throw new Exception('Whoops!');
    }

    // Make form
    $this->view->form = $form = new Event_Form_Topic_Create();

    // Process form
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )
    {
      $db = $event->getTable()->getAdapter();
      $db->beginTransaction();

      try
      {
        $viewer = $this->_helper->api()->user()->getViewer();
        
        $values = $form->getValues();
        $values['user_id'] = $viewer->getIdentity();
        $values['event_id'] = $event->getIdentity();

        // Create topic
        $topicTable = $this->_helper->api()->getDbtable('topics', 'event');
        $topic = $topicTable->createRow();
        $topic->setFromArray($values);
        $topic->save();

        // Create post
        $values['topic_id'] = $topic->topic_id;

        $postTable = $this->_helper->api()->getDbtable('posts', 'event');
        $post = $postTable->createRow();
        $post->setFromArray($values);
        $post->save();

        // Add activity
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $topic, 'event_topic_create');
        $action->attach($topic);
        
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
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireSubject('event_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid() ) return;

    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->event = $event = $topic->getParentEvent();

    if( $topic->closed ) {
      $this->view->status = false;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('This has been closed for posting.');
      return;
    }
    
    // Make form
    $this->view->form = $form = new Event_Form_Post_Create();

    // Process form
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )
    {
      $db = $event->getTable()->getAdapter();
      $db->beginTransaction();

      try
      {
        $viewer = $this->_helper->api()->user()->getViewer();

        $values = $form->getValues();
        $values['user_id'] = $viewer->getIdentity();
        $values['event_id'] = $event->getIdentity();
        $values['topic_id'] = $topic->getIdentity();

        // Create post
        $postTable = $this->_helper->api()->getDbtable('posts', 'event');
        $post = $postTable->createRow();
        $post->setFromArray($values);
        $post->save();

        // Notifications
        $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
        $topicOwner = $topic->getOwner();
        
        // Activity
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $topic, 'event_topic_reply');
        $action->attach($post, Activity_Model_Action::ATTACH_DESCRIPTION);

        // Notification of origin
        if( !$viewer->isSelf($topicOwner) ) {
          $notifyApi->addNotification($topicOwner, $viewer, $post, 'event_discussion_response');
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
          $notifyApi->addNotification($notifyUser, $viewer, $post, 'event_discussion_reply');
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

    $topic = Engine_Api::_()->core()->getSubject();
    $event = Engine_Api::_()->getItem('event', $topic->event_id);
    if( !$this->_helper->requireAuth()->setAuthParams($event, null, 'edit')->isValid() ) return;
    
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
    $topic = Engine_Api::_()->core()->getSubject();
    $event = Engine_Api::_()->getItem('event', $topic->event_id);
    if( !$this->_helper->requireAuth()->setAuthParams($event, null, 'edit')->isValid() ) return;
 
       
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

    $topic = Engine_Api::_()->core()->getSubject();
    $event = Engine_Api::_()->getItem('event', $topic->event_id);
    if( !$this->_helper->requireAuth()->setAuthParams($event, null, 'edit')->isValid() ) return;

    $this->view->form = $form = new Event_Form_Topic_Rename();

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
      $title = $form->getValue('title');

      $topic = Engine_Api::_()->core()->getSubject();
      $topic->title = htmlspecialchars($title);
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


    $topic = Engine_Api::_()->core()->getSubject();
    $event = Engine_Api::_()->getItem('event', $topic->event_id);
    if( !$this->_helper->requireAuth()->setAuthParams($event, null, 'edit')->isValid() ) return;

    $this->view->form = $form = new Event_Form_Topic_Delete();

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
      $event = $topic->getParent('event');
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
      'parentRedirect' => $event->getHref(),
    ));
  }
}