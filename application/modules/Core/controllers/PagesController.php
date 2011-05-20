<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: PagesController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Core_PagesController extends Core_Controller_Action_Standard
{
  public function __call($methodName, array $arguments)
  {
    // Not an action
    if( 'Action' != substr($methodName, -6) ) {
      throw new Zend_Controller_Action_Exception(sprintf('Method "%s" does not exist and was not trapped in __call()', $methodName), 500);
    }

    // Get page
    $action = substr($methodName, 0, strlen($methodName) - 6);

    // Have to un inflect
    if( is_string($action) ) {
      $actionNormal = strtolower(preg_replace('/([A-Z])/', '-\1', $action));
      // @todo This may be temporary
      $actionNormal = str_replace('-', '_', $actionNormal);
    }

    // Get page object
    $pageTable = Engine_Api::_()->getDbtable('pages', 'core');
    $pageSelect = $pageTable->select();
    
    if( is_numeric($actionNormal) ) {
      $pageSelect->where('page_id = ?', $actionNormal);
    } else {
      $pageSelect
        ->orWhere('name = ?', $actionNormal)
        ->orWhere('url = ?', $actionNormal);
    }

    $pageObject = $pageTable->fetchRow($pageSelect);

    // Page found
    if( null !== $pageObject ) {
      // Set title/desc/keywords
      if( !empty($pageObject->title) ) {
        $this->view->headTitle($pageObject->title);
      }
      if( !empty($pageObject->description) ) {
        $this->view->headMeta()->appendName('description', $pageObject->description);
      }
      if( !empty($pageObject->keywords) ) {
        $this->view->headMeta()->appendName('keywords', $pageObject->description);
      }
      // Set layout if specified
      if( !empty($pageObject->layout) ) {
        $this->_helper->layout->setLayout($pageObject->layout);
      }
      $this->_helper->content->setContentName($pageObject->page_id)->setEnabled();
      return;
    }

    // Missing page
    throw new Zend_Controller_Action_Exception(sprintf('Action "%s" does not exist and was not trapped in __call()', $action), 404);
  }
}