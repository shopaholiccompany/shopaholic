<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Ssh.php 7244 2010-09-01 01:49:53Z john $
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
class Engine_Vfs_Info_Ssh extends Engine_Vfs_Info_Abstract
{
  // General

  public function reload()
  {
    $info = $this->getAdapter()->info($this->_path);
    $this->_info = $info->getInfo();
  }



  // Tree

  public function getChildren()
  {
    if( !$this->isDirectory() ) {
      return false;
    }

    return $this->getAdapter()->directory($this->_path);
  }



  // Other

  public function exists()
  {
    return !empty($this->_info);
  }

  public function getSize()
  {
    return (int) @$this->_info['size'];
  }

  public function isDirectory()
  {
    return (bool) ( @$this->_info['is_dir'] == 'd' );
  }

  public function isExecutable()
  {
    return $this->_checkPerms(0x001);
    //return is_executable('ssh2.sftp://' . $this->_adapter->getSftpResource() . $this->_path);
  }

  public function isFile()
  {
    return !$this->isDirectory();
  }

  public function isLink()
  {
    return is_link('ssh2.sftp://' . $this->getAdapter()->getSftpResource() . $this->_path);
  }

  public function isReadable()
  {
    return $this->_checkPerms(0x004);
    //return is_readable('ssh2.sftp://' . $this->_adapter->getSftpResource() . $this->_path);
  }

  public function isWritable()
  {
    return $this->_checkPerms(0x002);
    //return is_writable('ssh2.sftp://' . $this->_adapter->getSftpResource() . $this->_path);
  }

  protected function _checkPerms($type)
  {
    if( strlen(@$this->_info['rights']) != 4 ) {
      return null;
    }
    
    if( $type !== 1 && $type !== 2 && $type !== 4 ) {
      return null;
    }
    
    // Prep
    list($d, $o, $g, $p) = str_split($this->_info['rights']);
    $o = (int) $o;
    $g = (int) $g;
    $p = (int) $p;

    // Calc
    $remoteUid = $this->getAdapter()->getRemoteUid();
    $remoteGid = $this->getAdapter()->getRemoteGid();

    if( false !== $remoteUid && @$this->_info['uid'] === $remoteUid && ($o & $type) ) {
      return true;
    }

    if( false !== $remoteGid && @$this->_info['gid'] === $remoteGid && ($g & $type) ) {
      return true;
    }

    if( $p & $type ) {
      return true;
    }

    return false;
  }
}