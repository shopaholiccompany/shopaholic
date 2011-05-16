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

//require_once 'Engine/Vfs/Adapter/Interface.php';
//require_once 'Engine/Vfs/Adapter/Exception.php';

/**
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
abstract class Engine_Vfs_Adapter_Abstract implements Engine_Vfs_Adapter_Interface
{
  protected $_adapterType;

  protected $_adapterPrefix;

  protected $_path;
  
  protected $_resource;

  protected $_directorySeparator = '/';
  
  protected $_umask = 0022;

  protected $_systemType;
  
  public function __construct(array $config = null)
  {
    if( isset($config['path']) ) {
      $path = $config['path'];
      unset($config['path']);
      $config['path'] = $path;
    }
    if( is_array($config) ) {
      $this->setOptions($config);
    }
  }

  public function __call($method, array $arguments)
  {
    throw new Engine_Vfs_Adapter_Exception(sprintf('Method "%s" not supported in class "%s"', $method, get_class($this)));
  }

  public function __sleep()
  {
    return array('_path', '_directorySeparator', '_adapterType', '_adapterPrefix', '_umask');
  }

  public function setOptions(array $config)
  {
    foreach( $config as $key => $value ) {
      $method = 'set' . ucfirst($key);
      if( method_exists($this, $method) ) {
        $this->$method($value);
      }
    }
    return $this;
  }

  public function getAdapterType()
  {
    if( null === $this->_adapterType ) {
      $this->_adapterType = ltrim(strrchr(get_class($this), '_'), '_');
    }
    return $this->_adapterType;
  }

  public function getAdapterPrefix()
  {
    if( null === $this->_adapterPrefix ) {
      $parts = explode('_Vfs_Adapter_', get_class($this));
      if( empty($parts) ) {
        $this->_adapterPrefix = 'Engine';
      } else {
        $this->_adapterPrefix = $parts[0];
      }
    }
    return $this->_adapterPrefix;
  }

  public function getResource()
  {
    return $this->_resource;
  }
  
  public function getDirectorySeparator()
  {
    return $this->_directorySeparator;
  }

  public function setPath($path)
  {
    $this->changeDirectory($path);
    return $this;
  }

  public function setUmask($umask)
  {
    $this->_umask = (int) $umask;
    return $this;
  }

  public function getUmask($withPermission = null)
  {
    if( null === $withPermission ) {
      return $this->_umask;
    } else {
      return (int) $withPermission & ~$this->_umask;
    }
  }
  
  public function getClass($type, $adapterType = null, $adapterPrefix = null)
  {
    if( null === $adapterType ) {
      $adapterType = $this->getAdapterType();
    }
    if( null === $adapterPrefix ) {
      $adapterPrefix = $this->getAdapterPrefix();
    }

    $class = join('_', array($adapterPrefix, 'Vfs', ucfirst($type), $adapterType));

    Engine_Loader::loadClass($class);
    
    return $class;
  }



  // Informational
  
  public function path($path = '')
  {
    if( '' == $path || '.' == $path ) {
      return $this->printDirectory();
    }

    $ds = $this->getDirectorySeparator();

    // Check for windows absolute paths
    $drive_letter = null;
    if( $this->getSystemType() == self::SYS_WIN && preg_match('~^[a-z][:][/\\\\]~i', $path, $m) ) {
      $drive_letter = substr($path, 0, 2);
      $path = $ds . substr($path, 3);
    }
    // Resolve absolute paths
    else if( $path[0] != '/' && $path[0] != '\\' && $path[0] != '~' ) {
      $path = $this->printDirectory() . '/' . $path;
    }
    // Remote home paths
    else if( $path[0] == '~' ) {
      // @todo just remove for now
      $path = ltrim($path, '~/\\');
    }

    // Replace directory separators and remove double slashes and trailing slashes
    $path = preg_replace('~[/\\\\]+~', $ds, $path);
    $path = rtrim($path, $ds);

    // Remove dotpaths
    $path = str_replace($ds.'.'.$ds, $ds, $path);
    $path = preg_replace('~[/\\\\]\.$~', '', $path);
    do {
      $path = preg_replace('~[/\\\\][^/\\\\]+[/\\\\]\.\.|\.\.[/\\\\][^/\\\\]+[/\\\\]~', '', $path, 1, $count);
    } while( $count > 0 );

    // Make sure we aren't left with an empty string or a double dot path
    if( $path == '' || $path == '/..' ) {
      $path = '/';
    }
    
    if( $drive_letter ) {
      $path = $drive_letter . $path;
    }
    
    return $path;
  }



  // Factory
  
  public function info($path = '', array $info = null)
  {
    /*
    // Check path
    $path = $this->path($path);
    if( !$this->exists($path) ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('The path "%s" does not exist.', $path));
    }
    */
    
    // Create
    $class = $this->getAdapterPrefix() . '_Vfs_Info_' . $this->getAdapterType();
    Engine_Loader::loadClass($class);
    $instance = new $class($this, $path, $info);
    
    return $instance;
  }

  public function object($path, $mode = 'r')
  {
    /*
    // Check path
    $path = $this->path($path);
    if( !$this->isFile($path) ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('The path "%s" is not a file.', $path));
    }
    */

    // Create
    $class = $this->getAdapterPrefix() . '_Vfs_Object_' . $this->getAdapterType();
    Engine_Loader::loadClass($class);
    $instance = new $class($this, $path, $mode);
    
    return $instance;
  }





  public function search($path, $pattern)
  {
    $path = $this->path($path);
    $matches = array();

    if( !is_string($pattern) ) {
      return $matches;
    }

    $directory = $this->directory($path);
    foreach( $directory as $child ) {
      if( preg_match($pattern, $child->getPath()) ) {
        $matches[] = $child->getPath();
      }
      if( $child->isDirectory() ) {
        $matches = array_merge($matches, $this->search($child->getPath(), $pattern));
      }
    }

    return $matches;
  }

  public function findJailedPath($path, $fullPath)
  {
    $path = $this->path($path);
    $matches = array();

    if( !is_string($fullPath) ) {
      return $matches;
    }
    $fullPath = $this->path($fullPath);
    $parts = array_filter(explode('/', str_replace('\\', '/', $fullPath)));

    while( count($parts) > 0 ) {
      $partialPath = $this->path(join($this->getDirectorySeparator(), $parts));
      if( $this->exists($partialPath) ) {
        return $partialPath;
      }
      array_shift($parts);
    }

    return false;
  }



  // Utility

  protected function _processMode($mode, $asOct = false)
  {
    if( is_string($mode) ) {
      // 0777 / 777 mode
      if( preg_match('/^[0-1]?([0-7][0-7][0-7])$/', $mode, $m) ) {
        $return = octdec($m[1]);
      } else if( preg_match('/^d?([rwx-]{9})$/', $mode, $m) ) {
        $perms = str_replace(array('r', 'w', 'x', '-'), array('4', '2', '1', '0'), $m[1]);
        $mode = sprintf('%d%d%d', ($perms[0] + $perms[1] + $perms[2]), ($perms[3] + $perms[4] + $perms[5]), ($perms[6] + $perms[7] + $perms[8]));
        $return = octdec($mode);
      } else {
        throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to process mode: %s', $mode));
      }
    } else if( is_integer($mode) ) {
      if( $mode >= 0 && $mode <= octdec('777') ) {
        $return = $mode;
      } else {
        throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to process mode: %s', $mode));
      }
    } else {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to process mode: %s', $mode));
    }

    if( $asOct ) {
      return sprintf('%o', $return);
    } else {
      return $return;
    }
  }
}