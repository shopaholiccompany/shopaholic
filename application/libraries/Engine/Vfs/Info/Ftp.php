<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Ftp.php 7244 2010-09-01 01:49:53Z john $
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
class Engine_Vfs_Info_Ftp extends Engine_Vfs_Info_Abstract
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
  }

  public function isFile()
  {
    return !$this->isDirectory();
  }

  public function isLink()
  {
    return null; // unknown
  }

  public function isReadable()
  {
    return $this->_checkPerms(0x004);
  }

  public function isWritable()
  {
    return $this->_checkPerms(0x002);
  }

  protected function _checkPerms($type)
  {
    if( $type !== 1 && $type !== 2 && $type !== 4 ) {
      return null;
    }

    if( empty($this->_info['rights']) ) {
      return null;
    }

    // Prep
    if( preg_match('/([0-1]?)([0-7]{3})/', $this->_info['rights'], $m) ) {
      // Octal mode
      list($null, $d, $perms) = $m;
      list($o, $g, $p) = str_split($perms);
      $o = (int) $o;
      $g = (int) $g;
      $p = (int) $p;
    } else if( preg_match('/(d?)([rwx-]{9})/', $this->_info['rights'], $m) ) {
      // The human (scoff) readable mode
      list($null, $d, $perms) = $m;
      list($o, $g, $p) = str_split($perms, 3);
      $o = ( (strpos($o, 'r') !== false) ? 4 : 0 ) + ( (strpos($o, 'w') !== false) ? 2 : 0 ) + ( (strpos($o, 'x') !== false) ? 1 : 0 );
      $g = ( (strpos($g, 'r') !== false) ? 4 : 0 ) + ( (strpos($g, 'w') !== false) ? 2 : 0 ) + ( (strpos($g, 'x') !== false) ? 1 : 0 );
      $p = ( (strpos($p, 'r') !== false) ? 4 : 0 ) + ( (strpos($p, 'w') !== false) ? 2 : 0 ) + ( (strpos($p, 'x') !== false) ? 1 : 0 );
    } else {
      // Whoops couldn't find anything
      return null;
    }
    
    // Calc
    $remoteUid = $this->getAdapter()->getRemoteUid();
    $remoteGid = $this->getAdapter()->getRemoteGid();

    if( false !== $remoteUid && @$this->_info['user'] === $remoteUid && ($o & $type) ) {
      return true;
    }

    if( false !== $remoteGid && @$this->_info['group'] === $remoteGid && ($g & $type) ) {
      return true;
    }

    if( $p & $type ) {
      return true;
    }

    return false;
  }
}