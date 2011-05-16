<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: System.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

//require_once 'Engine/Vfs/Info/Abstract.php';
//require_once 'Engine/Vfs/Info/Exception.php';

/**
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Vfs_Info_System extends Engine_Vfs_Info_Abstract
{
  // General

  public function reload()
  {
    clearstatcache(true, $this->_path);
  }


  
  // Tree

  public function getChildren()
  {
    if( !$this->isDirectory() ) {
      return false;
    }

    $children = array();
    foreach( scandir($this->_path) as $child ) {
      if( $child == '.' || $child == '..' ) continue;
      $children[] = $this->getAdapter()->info($this->_path . '/' . $child);
    }

    return new Engine_Vfs_Directory_Standard($this->getAdapter(), $this->_path, $children);
  }



  // Other

  public function exists()
  {
    return file_exists($this->_path);
  }

  public function getSize()
  {
    return filesize($this->_path);
  }

  public function isDirectory()
  {
    return is_dir($this->_path);
  }

  public function isExecutable()
  {
    return is_executable($this->_path);
  }

  public function isFile()
  {
    return is_file($this->_path);
  }

  public function isLink()
  {
    return is_link($this->_path);
  }

  public function isReadable()
  {
    return is_readable($this->_path);
  }

  public function isWritable()
  {
    return is_writeable($this->_path);
  }
}