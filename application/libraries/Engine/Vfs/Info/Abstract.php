<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Abstract.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

//require_once 'Engine/Vfs/Info/Interface.php';
//require_once 'Engine/Vfs/Info/Exception.php';

/**
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
abstract class Engine_Vfs_Info_Abstract implements Engine_Vfs_Info_Interface
{
  protected $_adapter;

  protected $_path;

  protected $_info;
  
  public function __construct(Engine_Vfs_Adapter_Interface $adapter, $path, array $info = null)
  {
    $this->_adapter = $adapter;
    $this->_path = $path;
    $this->_info = $info;
    $this->init();
  }

  public function __sleep()
  {
    return array('_path', '_info');
  }

  public function init()
  {
    
  }

  public function getAdapter()
  {
    if( null === $this->_adapter ) {
      throw new Engine_Vfs_Info_Exception('No adapter registered. This object may have been serialized');
    }
    return $this->_adapter;
  }

  public function getInfo()
  {
    return $this->_info;
  }



  // Tree

  public function getParent()
  {
    return $this->getAdapter()->info($this->getDirectoryName());
  }

  //public function getChildren();



  // Path

  public function getPath()
  {
    return $this->_path;
  }

  public function getBaseName()
  {
    return basename($this->_path);
  }

  public function getDirectoryName()
  {
    return dirname($this->_path);
  }

  public function getRealPath()
  {
    // Note: most of the time it will be real already
    return $this->_path;
  }

  public function toString()
  {
    return $this->_path;
  }

  public function __toString()
  {
    return $this->_path;
  }



  // Object

  public function open($mode = 'r')
  {
    return $this->getAdapter()->object($this->_path, $mode);
  }
}