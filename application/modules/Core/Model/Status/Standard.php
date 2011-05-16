<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Standard.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Core_Model_Status_Standard
{
  protected $_custom = false;

  public function __construct()
  {
    if( get_class($this) != 'Core_Model_Status_Standard' )
    {
      $this->_custom = true;
    }
  }

  public function getTable()
  {
    return Engine_Api::_()->getDbtable('status', 'core');
  }

  public function setStatus(Core_Model_Item_Abstract $resource, $body)
  {
    // Create status row
    $table = $this->getTable();
    $row = $table->createRow();

    if( !$this->_custom )
    {
      $row->resource_type = $resource->getType();
    }

    $row->resource_id = $resource->getIdentity();
    $row->body = $body;
    $row->creation_date = date('Y-m-d H:i:s');
    $row->save();

    // Update resource if necessary
    $resource_modified = false;

    if( isset($resource->status) )
    {
      $resource_modified = true;
      $resource->status = $body;
    }
    
    if( isset($resource->status_date) )
    {
      $resource_modified = true;
      $resource->status_date = date('Y-m-d H:i:s');
    }

    if( isset($resource->status_count) )
    {
      $resource_modified = true;
      $resource->status_count++;
    }

    if( $resource_modified )
    {
      $resource->save();
    }

    return $row;
  }

  public function getStatus(Core_Model_Item_Abstract $resource, $status_id)
  {
    $table = $this->getTable();
    $select = $table->select();

    if( !$this->_custom )
    {
      $select->where('resource_type = ?', $resource->getType());
    }

    $select
      ->where('resource_id = ?', $resource->getIdentity())
      ->where('status_id = ?', (int) $status_id)
      ->limit(1);

    return $table->fetchRow($select);
  }

  public function getLastStatus(Core_Model_Item_Abstract $resource)
  {
    $table = $this->getTable();
    $select = $table->select();

    if( !$this->_custom )
    {
      $select->where('resource_type = ?', $resource->getType());
    }

    $select
      ->where('resource_id = ?', $resource->getIdentity())
      ->order('status_id DESC')
      ->limit(1);

    return $table->fetchRow($select);
  }

  public function getStatusSelect(Core_Model_Item_Abstract $resource)
  {
    $table = $this->getTable();
    $select = $table->select();

    if( !$this->_custom )
    {
      $select->where('resource_type = ?', $resource->getType());
    }

    $select
      ->where('resource_id = ?', $resource->getIdentity())
      ->order('status_id DESC');

    return $select;
  }

  public function getStatusPaginator(Core_Model_Item_Abstract $resource)
  {
    return Zend_Paginator::factory($this->getStatusSelect($resource));
  }
}