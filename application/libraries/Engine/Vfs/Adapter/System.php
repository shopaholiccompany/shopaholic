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

//require_once 'Engine/Vfs/Adapter/Abstract.php';
//require_once 'Engine/Vfs/Adapter/Exception.php';
//require_once 'Engine/Vfs/Directory/Standard.php';
//require_once 'Engine/Vfs/Info/System.php';
//require_once 'Engine/Vfs/Object/System.php';

/**
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Vfs_Adapter_System extends Engine_Vfs_Adapter_Abstract
{
  public function __construct(array $config = null)
  {
    parent::__construct($config);
    $this->_directorySeparator = DIRECTORY_SEPARATOR;
  }

  public function getResource()
  {
    return $this;
  }



  // Informational

  public function exists($path)
  {
    $path = $this->path($path);
    
    return file_exists($path);
  }

  public function isDirectory($path)
  {
    $path = $this->path($path);

    return is_dir($path);
  }

  public function isFile($path)
  {
    $path = $this->path($path);

    return is_file($path);
  }

  public function getSystemType()
  {
    if( null === $this->_systemType ) {
      $systype = php_uname('s');
      switch( strtoupper(substr($systype, 0, 3)) ) {
        case 'LIN': $this->_systemType = self::SYS_LIN; break;
        case 'UNI': $this->_systemType = self::SYS_UNI; break;
        case 'WIN': $this->_systemType = self::SYS_WIN; break;
        case 'DAR': $this->_systemType = self::SYS_DAR; break;
        case 'FRE': case 'OPE':
          if( stripos($systype, 'BSD') === false ) {
            throw new Engine_Vfs_Adapter_Exception(sprintf('Unknown remote system type: %s', $systype));
          }
        case 'BSD': $this->_systemType = self::SYS_BSD; break;
        default: throw new Engine_Vfs_Adapter_Exception(sprintf('Unknown remote system type: %s', $systype)); break;
      }
    }
    return $this->_systemType;
  }



  // Factory

  public function directory($path = '', array $contents = null)
  {
    $path = $this->path($path);

    // Get contents
    if( null === $contents ) {
      $contents = array();
      foreach( scandir($path) as $child ) {
        if( $child == '.' || $child == '..' ) continue;
        $contents[] = $this->info($path . '/' . $child);
      }
    }

    $class = $this->getClass('Directory', 'Standard');
    $instance = new $class($this, $path, $contents);
    return $instance;
  }

  public function info($path = '', array $info = null)
  {
    $path = $this->path($path);
    
    $class = $this->getClass('Info');
    $instance = new $class($this, $path, $info);
    return $instance;
  }


  
  // General

  public function copy($sourcePath, $destPath)
  {
    $sourcePath = $this->path($sourcePath);
    $destPath = $this->path($destPath);

    $return = @copy($sourcePath, $destPath);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to copy "%s" to "%s"', $sourcePath, $destPath));
    }

    return $return;
  }

  public function get($local, $path)
  {
    $path = $this->path($path);

    $return = @copy($path, $local);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to get "%s" to "%s"', $path, $local));
    }

    return $return;
  }

  public function getContents($path)
  {
    $path = $this->path($path);

    $return = @file_get_contents($path);

    if( false === $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to get contents of "%s"', $path));
    }

    return $return;
  }

  public function mode($path, $mode, $recursive = false)
  {
    $path = $this->path($path);
    
    $return = @chmod($path, $this->_processMode($mode));

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change mode on "%s"', $path));
    }

    if( $recursive ) {
      $info = $this->info($path);
      if( $info->isDirectory() ) {
        foreach( $info->getChildren() as $child ) {
          $return &= $this->mode($child->getPath(), $mode, true);
        }
      }
    }

    return $return;
  }

  public function move($oldPath, $newPath)
  {
    $oldPath = $this->path($oldPath);
    $newPath = $this->path($newPath);

    $return = @rename($oldPath, $newPath);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to rename "%s" to "%s"', $oldPath, $newPath));
    }

    return $return;
  }

  public function put($path, $local)
  {
    $path = $this->path($path);

    $return = @copy($local, $path);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to put "%s" to "%s"', $local, $path));
    }

    // Apply umask
    try {
      $this->mode($path, $this->getUmask(0666));
    } catch( Exception $e ) {
      // Silence
    }

    return $return;
  }

  public function putContents($path, $data)
  {
    $path = $this->path($path);

    $return = @file_put_contents($path, $data);

    if( false === $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to put contents to "%s"', $path));
    }

    // Apply umask
    try {
      $this->mode($path, $this->getUmask(0666));
    } catch( Exception $e ) {
      // Silence
    }

    return $return;
  }

  public function unlink($path)
  {
    $path = $this->path($path);

    $return = @unlink($path);

    if( false === $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to unlink "%s"', $path));
    }

    return $return;
  }



  // Directories

  public function changeDirectory($directory)
  {
    $directory = $this->path($directory);

    if( $this->isDirectory($directory) ) {
      $this->_path = rtrim($directory, '/\\');
      return true;
    } else {
      return false;
    }
  }

  public function makeDirectory($directory, $recursive = false)
  {
    $directory = $this->path($directory);

    // Already a directory
    if( $this->isDirectory($directory) ) {
      return true;
    }
    
    $return = @mkdir($directory, $this->getUmask(0777), $recursive);

    if( false === $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to make directory "%s"', $directory));
    }

    return $return;
  }

  public function printDirectory()
  {
    if( null === $this->_path ) {
      $this->_path = getcwd();
    }
    return $this->_path;
  }

  public function removeDirectory($directory, $recursive = false)
  {
    $directory = $this->path($directory);

    // Recursive
    if( $recursive ) {
      $return = true;

      // Iterate over contents
      $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::KEY_AS_PATHNAME), RecursiveIteratorIterator::CHILD_FIRST);
      foreach( $it as $key => $child ) {
        if( $child->getFilename() == '..' || $child->getFilename() == '.' ) continue;
        if( $child->isDir() ) {
          $return &= $this->removeDirectory($child->getPathname(), false);
        } else if( $it->isFile() ) {
          $return &= $this->unlink($child->getPathname(), false);
        }
      }
      $return &= $this->removeDirectory($directory, false);
    }

    // Normal
    else {
      $return = @rmdir($directory);
    }

    if( false === $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to remove directory "%s"', $directory));
    }

    return $return;
  }
}