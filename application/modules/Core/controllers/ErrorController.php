<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: ErrorController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Core_ErrorController extends Core_Controller_Action_Standard
{
  public function init()
  {
    $ajaxContext = $this->_helper->getHelper('AjaxContext');
    $ajaxContext
      ->addActionContext('error', 'json')
      ->addActionContext('notfound', 'json')
      ->addActionContext('requireauth', 'json')
      ->addActionContext('requiresubject', 'json')
      ->addActionContext('requireuser', 'json')
      ->initContext();
  }
  
  public function errorAction()
  {
    $error = $this->_getParam('error_handler');

    $log = Zend_Registry::get('Zend_Log');
    if( $log )
    {
      // Unfortuantely, we can't log the exception because the trace will
      // cause infinite loop
      $e = $error->exception;
      $log->log($e->__toString(), Zend_Log::CRIT);
    }

    switch( $error->type )
    {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        return $this->_forward('notfound');
        break;
        
      default:
        break;
    }

    // C
    //$this->getResponse()->setRawHeader('HTTP/1.1 500 Internal server error');
    $this->view->status = false;
    if( APPLICATION_ENV != 'production' )
    {
      $this->view->error = $error->exception->__toString();
    }
    else
    {
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('An error has occurred');
    }
  }

  public function notfoundAction()
  {
    // 404 error -- controller or action not found
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('The requested resource could not be found.');
  }

  public function requiresubjectAction()
  {
    return $this->_forward('notfound');
    
    // 404 error -- subject not found
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('The requested resource could not be found.');
  }

  public function requireauthAction()
  {
    // 403 error -- authorization failed
    if( !$this->_helper->requireUser()->isValid() ) return;
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.');
  }

  public function requireuserAction()
  {
    // 403 error -- authorization failed
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . '403 Forbidden');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.');

    // Show the login form for them :P
    $this->view->form = $form = new User_Form_Login();
    $form->addError('Please sign in to continue..');
    $form->return_url->setValue(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    // Facebook login
    if (User_Model_DbTable_Facebook::authenticate($form))
      // Facebook login succeeded, redirect to home
      $this->_helper->redirector->gotoRoute(array(), 'home');
  }

  public function requireadminAction()
  {
    // Should probably make this do something else later
    //$this->_helper->layout->setLayout('admin');
    return $this->_forward('notfound');
  }
}