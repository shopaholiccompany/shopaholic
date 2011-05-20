<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: ActionTypes.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Activity_Model_DbTable_ActionTypes extends Engine_Db_Table
{
  protected $_actionTypes;
  
  /**
   * Gets all action type meta info
   *
   * @param string|null $type
   * @return Engine_Db_Rowset
   */
  public function getActionTypes()
  {
    if( null === $this->_actionTypes )
    {
      // Only get enabled types
      //$this->_actionTypes = $this->fetchAll();
      $enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
      $select = $this->select()
        ->where('module IN(?)', $enabledModuleNames)
        ;
      $this->_actionTypes = $this->fetchAll($select);
    }

    return $this->_actionTypes;
  }

  public function getActionType($type)
  {
    return $this->getActionTypes()->getRowMatching('type', $type);
  }
  
  public function getActionTypesAssoc()
  {
    $arr = array();
    $translate = Zend_Registry::get('Zend_Translate');
    foreach( $this->getActionTypes() as $type )
    {
      $arr[$type->type] = $translate->_('_ACTIVITY_ACTIONTYPE_'.strtoupper($type->type));
    }
    return $arr;
  }
}