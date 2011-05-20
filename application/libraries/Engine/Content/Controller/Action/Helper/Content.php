<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Content
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Content.php 7244 2010-09-01 01:49:53Z john $
 */

/**
 * @category   Engine
 * @package    Engine_Content
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Engine_Content_Controller_Action_Helper_Content extends Zend_Controller_Action_Helper_Abstract
{
  // Properties
  
  protected $_enabled = false;

  protected $_content;

  protected $_contentName;

  protected $_noRender = true;



  // General

  public function postDispatch()
  {
    if( $this->_enabled ) {
      $this->_enabled = false;
      $this->render();
      $this->reset();
    }
  }

  public function __call($method, $args)
  {
    throw new Engine_Content_Exception(sprintf("Invalid method '%s' called on content action helper", $method));
  }


  // Options
  
  public function setEnabled($flag = true)
  {
    $this->_enabled = (bool) $flag;
    return $this;
  }

  public function getEnabled()
  {
    return (bool) $this->_enabled;
  }

  public function setNoRender($flag = true)
  {
    $this->_noRender = (bool) $flag;
    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
    $viewRenderer->setNoRender($this->_noRender);
    return $this;
  }

  public function getNoRender()
  {
    return (bool) $this->_noRender;
  }

  public function setContent(Engine_Content $content)
  {
    $this->_content = $content;
    return $this;
  }

  public function getContent()
  {
    if( null === $this->_content ) {
      $this->_content = Engine_Content::getInstance();
    }

    return $this->_content;
  }

  public function setContentName($name)
  {
    $this->_contentName = $name;
    return $this;
  }

  public function getContentName()
  {
    if( null === $this->_contentName ) {
      $controller = $this->getActionController();
      $request = $controller->getRequest();
      $this->_contentName = $request->getModuleName() . '_' . $request->getControllerName() . '_' . $request->getActionName();
    }

    return $this->_contentName;
  }



  public function reset()
  {
    $this->_enabled = false;
    $this->_contentName = null;
  }



  // Rendering

  public function render()
  {
    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
    $viewRenderer->setNoRender($this->_noRender);

    $content = $this->getContent();
    $contentName = $this->getContentName();

    $this->getResponse()->setBody($content->render($contentName));

    return $this;
  }
}