<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: WidgetController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Event_WidgetController extends Core_Controller_Action_Standard
{
  public function profileInfoAction() 
  {
    // Don't render this if not authorized
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid() )
      return $this->_helper->viewRenderer->setNoRender(true);
  }

  public function profileRsvpAction()
  {

    $this->view->form = new Event_Form_Rsvp();
    $event = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$event->membership()->isMember($viewer, true))
    {
      return;
    }
    $row = $event->membership()->getRow($viewer);
    $this->view->viewer_id = $viewer->getIdentity();
    if ($row) {
      $this->view->rsvp = $row->rsvp;
    }
    else
    {
      return $this->_helper->viewRenderer->setNoRender(true);
    }
    if ($this->getRequest()->isPost())
    {
      $option_id = $this->getRequest()->getParam('option_id');

      $row->rsvp = $option_id;
      $row->save();
    }
  }
}