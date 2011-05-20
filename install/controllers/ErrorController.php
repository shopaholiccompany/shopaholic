<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: ErrorController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class ErrorController extends Zend_Controller_Action
{
  public function init()
  {
    // Check if already logged in
    if( !Zend_Registry::get('Zend_Auth')->getIdentity() ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }
  }
  
  public function errorAction()
  {
    $error_handler = $this->_getParam('error_handler');

    if( isset($error_handler->exception) ) {
      if ('development' == APPLICATION_ENV) {
        $this->view->error = $errStr = $error_handler->exception->__toString();
      } else {
        $this->view->error = $errStr = $error_handler->exception->getMessage();
      }
      Zend_Registry::get('Zend_Log')->log($errStr, Zend_Log::ERR);
    }

    switch( $error_handler->type )
    {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        return $this->_forward('notfound');
        break;

      default:
        break;
    }
  }

  public function notfoundAction()
  {
    // 404 error -- controller or action not found
    $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
    $this->view->status = false;
    //$this->view->error = 'The requested resource could not be found.';
  }
}