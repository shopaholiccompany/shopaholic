<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Controller.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Activity_Widget_FeedController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = null;
    if( Engine_Api::_()->core()->hasSubject() ) {
      // Get subject
      $subject = Engine_Api::_()->core()->getSubject();
      if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
        return $this->setNoRender();
      }
    }

    $request = Zend_Controller_Front::getInstance()->getRequest();

    // Check if activity exists
    $feedOnly = $request->getParam('feedOnly', false);
    $this->view->viewAllLikes    = $request->getParam('viewAllLikes',    $request->getParam('show_likes',    false));
    $this->view->viewAllComments = $request->getParam('viewAllComments', $request->getParam('show_comments', false));

    if( $feedOnly ) {
      $this->getElement()->removeDecorator('Title');
      $this->getElement()->removeDecorator('Container');
    }

    // Get config options for activity
    $config = array(
      'action_id' => (int) $request->getParam('action_id'),
      'max_id'    => (int) $request->getParam('maxid'),
      'min_id'    => (int) $request->getParam('minid'),
      'limit'     => (int) $request->getParam('limit'),
    );

    // Get viewer and subject
    if( !empty($subject) )
    {
      $this->view->activity = Engine_Api::_()->getDbtable('actions', 'activity')->getActivityAbout($subject, $viewer, $config);
      $this->view->subjectGuid = $subject->getGuid(false);
    }
    else
    {
      $this->view->activity = Engine_Api::_()->getDbtable('actions', 'activity')->getActivity($viewer, $config);
      $this->view->subjectGuid = null;
    }

    $this->view->enableComposer = false;
    if( $viewer->getIdentity() && !$this->_getParam('action_id') )
    {
      if( !$subject || $subject->authorization()->isAllowed($viewer, 'comment') )
      {
        $this->view->enableComposer = true;
      }
    }

    $nextid = null;
    $firstid = 0;
    if( null !== $this->view->activity )
    {
      foreach( $this->view->activity as $action )
      {
        if( $action->action_id < $nextid || null === $nextid )
        {
          $nextid = $action->action_id;
        }
        if( $action->action_id > $firstid || null === $firstid )
        {
          $firstid = $action->action_id;
        }
      }
    }
    if( $nextid ) $nextid--;

    // Assign the composing values
    $composePartials = array();
    foreach( Zend_Registry::get('Engine_Manifest') as $data )
    {
      if( empty($data['composer']) ) continue;
      foreach( $data['composer'] as $type => $config )
      {
        $composePartials[] = $config['script'];
      }
    }
    $this->view->composePartials = $composePartials;

    // Assign some options
    $this->view->updateSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.liveupdate');
    $this->view->action_id = (int) $request->getParam('action_id');
    $this->view->feedOnly = $feedOnly;
    $this->view->getUpdate = $this->_getParam('getUpdate');
    $this->view->checkUpdate = $this->_getParam('checkUpdate');
    $this->view->nextid = $nextid;
    $this->view->firstid = $firstid;
    $this->view->activityCount = count($this->view->activity);
    $this->view->length = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.length', 20);
  }
}
