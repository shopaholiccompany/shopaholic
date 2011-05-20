<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Package
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Package.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Filter
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Package_Manifest_Entity_Package extends Engine_Package_Manifest_Entity_Abstract
{
  protected $_type;

  protected $_name;

  protected $_version;

  protected $_revision;

  protected $_path;

  protected $_repository;

  protected $_addDirectoryToArchive = false;

  protected $_sourcePath;

  // Entities

  protected $_callback;

  protected $_dependencies;

  protected $_meta;

  protected $_permissions;

  protected $_structure;

  protected $_tests;

  protected $_actions;

  protected $_props = array(
    'type',
    'name',
    'version',
    'revision',
    'path',
    'repository',
    'meta',
    'dependencies',
    'permissions',
    'actions',
    'callback',
    'tests',
    'structure',
  );



  // General

  public function __construct($spec = null, $options = null)
  {
    if( is_array($spec) ) {
      $this->setOptions($spec);
    }
    if( is_array($options) ) {
      $this->setOptions($options);
    }
    if( is_string($spec) ) {
      $this->setPath($spec);
    }

    // Build if empty structure
    if( null === $this->_structure && !empty($this->_path) ) {
      $this->read($this->_path);
    }
  }

  public function getKey()
  {
    return sprintf('%s-%s-%s', $this->getType(), $this->getName(), $this->getVersion());
  }

  public function getGuid()
  {
    return sprintf('%s-%s', $this->getType(), $this->getName());
  }

  public function getType()
  {
    return $this->_type;
  }

  public function setType($type)
  {
    $this->_type = (string) $type;
    return $this;
  }

  public function getName()
  {
    return $this->_name;
  }

  public function setName($name)
  {
    $this->_name = (string) $name;
    return $this;
  }

  public function getVersion()
  {
    return $this->_version;
  }

  public function setVersion($version)
  {
    $this->_version = (string) $version;
    return $this;
  }

  public function getRevision()
  {
    return $this->_revision;
  }

  public function setRevision($revision)
  {
    if( is_numeric($revision) ) {
      $this->_revision = $revision;
    } else if( is_string($revision) && 
        preg_match('~\$Revision\: (\d+) \$~', $revision, $m) ) {
      $this->_revision = $m[1];
    }
    return $this;
  }

  public function getPath()
  {
    if( null === $this->_path ) {
      throw new Engine_Package_Manifest_Exception('Path cannot be empty');
    }
    return $this->_path;
  }

  public function setPath($path)
  {
    $this->_path = $path;
    return $this;
  }

  public function getRepository()
  {
    return $this->_repository;
  }

  public function setRespository($repository)
  {
    $this->_repository = $repository;
    return $this;
  }

  public function getAddDirectoryToArchive()
  {
    return (bool) $this->_addDirectoryToArchive;
  }

  public function setAddDirectoryToArchive($flag = false)
  {
    $this->_addDirectoryToArchive = (bool) $flag;
    return $this;
  }

  public function getSourcePath()
  {
    return $this->_sourcePath;
  }



  // Structure

  public function getStructure()
  {
    if( null === $this->_structure && null !== $this->_path ) {
      $this->read($this->_path);
    }
    return $this->_structure;
  }

  public function setStructure(array $structure)
  {
    $this->_structure = array();
    foreach( $structure as $key => $value ) {
      if( !is_array($value) ) continue;
      if( !isset($value['type']) ) continue;
      if( !is_string($value['type']) ) continue;

      $method = 'set' . ucfirst($value['type']);
      $this->$method($value);
    }
    return $this;
  }

  public function getFileStructure($assoc = false)
  {
    $files = array();
    foreach( $this->getStructure() as $struct ) {
      if( !($struct instanceof Engine_Package_Manifest_Entity_Abstract) ) {
        throw new Engine_Package_Manifest_Exception('Not a package entity');
      }
      if( method_exists($struct, 'getFileStructure') ) {
        $files = array_merge($files, $struct->getFileStructure($assoc));
      }
    }
    return $files;
  }



  // Actions

  public function addAction($action)
  {
    if( !in_array($action, (array) $this->_actions) ) {
      $this->_actions[] = $action;
    }
    return $this;
  }

  public function addActions(array $actions)
  {
    foreach( $actions as $action ) {
      $this->addAction($action);
    }
    return $this;
  }

  public function getAction($action)
  {
    if( in_array($action, (array) $this->_actions) ) {
      return $action;
    }
    return null;
  }

  public function getActions()
  {
    return (array) $this->_actions;
  }

  public function hasAction($action)
  {
    return in_array($action, (array) $this->_actions);
  }

  public function setAction($action)
  {
    $this->addAction($action);
    return $this;
  }

  public function setActions(array $actions)
  {
    $this->addActions($actions);
    return $this;
  }



  // Callbacks

  public function getCallback()
  {
    return $this->_callback;
  }

  public function setCallback($callback, $options = null)
  {
    if( !($callback instanceof Engine_Package_Manifest_Entity_Callback) ) {
      $callback = new Engine_Package_Manifest_Entity_Callback($callback, $options);
    } else if( is_array($options) ) {
      $callback->setOptions($options);
    }
    $this->_callback = $callback;
    return $this;
  }



  // Dependencies

  public function addDependency($dependency, $options = null)
  {
    if( !($dependency instanceof Engine_Package_Manifest_Entity_Dependency) ) {
      $dependency = new Engine_Package_Manifest_Entity_Dependency($dependency, $options);
    } else if( is_array($options) ) {
      $dependency->setOptions($options);
    }
    $this->_dependencies[$dependency->getGuid()] = $dependency;
    return $this;
  }

  public function addDependencies(array $dependencies = null)
  {
    foreach( (array) $dependencies as $key => $value ) {
      $dependency = null;
      $options = null;
      if( $value instanceof Engine_Package_Manifest_Entity_Dependency ) {
        $dependency = $value;
      } else if( is_string($key) ) {
        $dependency = $key;
        $options = $value;
      } else {
        $dependency = $value;
      }
      $this->addDependency($dependency, $options);
    }
    return $this;
  }

  public function getDependencies()
  {
    return $this->_dependencies;
  }

  public function getDependency($name)
  {
    if( isset($this->_dependencies[$name]) ) {
      return $this->_dependencies[$name];
    }
    return null;
  }

  public function setDependencies(array $dependencies = null)
  {
    $this->addDependencies($dependencies);
    return $this;
  }

  public function setDependency($dependency)
  {
    $this->addDependency($dependency);
    return $this;
  }



  // Directories

  public function addDirectory($directory, $options = null)
  {
    if( !($directory instanceof Engine_Package_Manifest_Entity_Directory) ) {
      if( !isset($options['basePath']) ) {
        $options['basePath'] = $this->getBasePath();
      }
      $directory = new Engine_Package_Manifest_Entity_Directory($directory, $options);
    } else if( is_array($options) ) {
      $directory->setOptions($options);
    }

    $directory->setBasePath($this->getBasePath());

    $name = self::fix_path($directory->getPath());
    $this->_structure[$name] = $directory;

    return $this;
  }

  public function addDirectories(array $directories = null)
  {
    foreach( (array) $directories as $key => $value ) {
      $directory = null;
      $options = null;
      if( $value instanceof Engine_Package_Manifest_Entity_Directory ) {
        $directory = $value;
      } else if( is_string($key) ) {
        $directory = $key;
        $options = $value;
      } else {
        $directory = $value;
      }
      $this->addDirectory($directory, $options);
    }
    return $this;
  }

  public function getDirectories()
  {
    $directories = array();
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Directory ) {
        $directories[$key] = $value;
      }
    }
    return $directories;
  }

  public function getDirectory($directory)
  {
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Directory ) {
        if( $value->getPath() == $directory ) {
          return $value;
        }
      }
    }
    return null;
  }

  public function setDirectories(array $directories = null)
  {
    $this->addDirectories($directories);
    return $this;
  }

  public function setDirectory($directory, $options = null)
  {
    $this->addDirectory($directory, $options);
    return $this;
  }



  // Files

  public function addFiles(array $files = null)
  {
    foreach( (array) $files as $key => $value ) {
      $file = null;
      $options = null;
      if( $value instanceof Engine_Package_Manifest_Entity_File ) {
        $file = $value;
      } else if( is_string($key) ) {
        $file = $key;
        $options = $value;
      } else {
        $file = $value;
      }
      $this->addFile($file, $options);
    }
    return $this;
  }

  public function addFile($file, $options = null)
  {
    if( !($file instanceof Engine_Package_Manifest_Entity_File) ) {
      if( !isset($options['basePath']) ) {
        $options['basePath'] = $this->getBasePath();
      }
      $file = new Engine_Package_Manifest_Entity_File($file, $options);
    } else if( is_array($options) ) {
      $file->setOptions($options);
    }
    $file->setBasePath($this->getBasePath());

    $name = self::fix_path($file->getPath());
    $this->_structure[$name] = $file;

    return $this;
  }

  public function getFiles()
  {
    $files = array();
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_File ) {
        $files[$key] = $value;
      }
    }
    return $files;
  }

  public function getFile($file)
  {
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_File ) {
        if( $value->getPath() == $file ) {
          return $value;
        }
      }
    }
    return null;
  }

  public function setFiles(array $files = null)
  {
    $this->addFiles($files);
    return $this;
  }

  public function setFile($file, $options = null)
  {
    $this->addFile($file, $options);
    return $this;
  }



  // Meta

  public function addMeta($meta, $value = null)
  {
    if( is_string($meta) ) {
      $meta = array(
        $meta => $value,
      );
    } else if( $meta instanceof Engine_Package_Manifest_Entity_Meta ) {
      $meta = $meta->toArray();
    } else if( !is_array($meta) ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown meta format: "%s"', gettype($meta)));
    }

    if( $this->_meta instanceof Engine_Package_Manifest_Entity_Meta ) {
      $this->_meta->setOptions($meta);
    } else {
      $this->_meta = new Engine_Package_Manifest_Entity_Meta($meta);
    }

    return $this;
  }

  public function clearMeta()
  {
    $this->_meta = new Engine_Package_Manifest_Entity_Meta();
    return $this;
  }

  public function getMeta()
  {
    if( null === $this->_meta ) {
      $this->_meta = new Engine_Package_Manifest_Entity_Meta(array());
    }

    return $this->_meta;
  }

  public function setMeta($meta, $value = null)
  {
    if( is_string($meta) ) {
      $this->addMeta($meta, $value);
    } else if( $meta instanceof Engine_Package_Manifest_Entity_Meta ) {
      $this->_meta = $meta;
    } else if( is_array($meta) ) {
      $this->_meta = new Engine_Package_Manifest_Entity_Meta($meta);
    } else {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown meta format: "%s"', gettype($meta)));
    }
    return $this;
  }



  // Packages

  public function addPackages(array $packages = null)
  {
    foreach( (array) $packages as $key => $value ) {
      $package = null;
      $options = null;
      if( $value instanceof Engine_Package_Manifest_Entity_Package ) {
        $package = $value;
      } else if( is_string($key) ) {
        $package = $key;
        $options = $value;
      } else {
        $package = $value;
      }
      $this->addPackage($package, $options);
    }
    return $this;
  }

  public function addPackage($package, $options = null)
  {
    if( !($package instanceof Engine_Package_Manifest_Entity_Package) ) {
      if( !isset($options['basePath']) ) {
        $options['basePath'] = $this->getBasePath();
      }
      $package = new Engine_Package_Manifest_Entity_Package($package, $options);
    } else if( is_array($options) ) {
      $package->setOptions($options);
    }

    $package->setBasePath($this->getBasePath());

    $name = self::fix_path($package->getPath());
    $this->_structure[$name] = $package;

    return $this;
  }

  public function getPackages()
  {
    $packages = array();
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Package ) {
        $packages[$key] = $value;
      }
    }
    return $packages;
  }

  public function getPackage($package)
  {
    foreach( $this->_structure as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Package ) {
        if( $value->getPath() == $package ) {
          return $value;
        }
      }
    }
    return null;
  }

  public function setPackages(array $packages = null)
  {
    $this->addPackages($packages);
    return $this;
  }

  public function setPackage($package, $options = null)
  {
    $this->addPackage($package, $options);
    return $this;
  }



  // Permissions

  public function addPermission($permission, $options = null)
  {
    if( !($permission instanceof Engine_Package_Manifest_Entity_Permission) ) {
      if( !isset($options['basePath']) ) {
        $options['basePath'] = $this->getBasePath();
      }
      $permission = new Engine_Package_Manifest_Entity_Permission($permission, $options);
    } else if( is_array($options) ) {
      $permission->setOptions($options);
    }

    $permission->setBasePath($this->getBasePath());

    $name = self::fix_path($permission->getPath());
    $this->_permissions[$name] = $permission;

    return $this;
  }

  public function addPermissions(array $permissions = null)
  {
    foreach( (array) $permissions as $key => $value ) {
      $permission = null;
      $options = null;
      if( $value instanceof Engine_Package_Manifest_Entity_Permission ) {
        $permission = $value;
      } else if( is_string($key) ) {
        $permission = $key;
        $options = $value;
      } else {
        $permission = $value;
      }
      $this->addPermission($permission, $options);
    }
    return $this;
  }

  public function getPermissions()
  {
    $permissions = array();
    foreach( (array) $this->_permissions as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Permission ) {
        $permissions[$key] = $value;
      }
    }
    return $permissions;
  }

  public function getPermission($permission)
  {
    foreach( (array) $this->_permissions as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Permission ) {
        if( $value->getPath() == $permission ) {
          return $value;
        }
      }
    }
    return null;
  }

  public function setPermissions(array $permissions = null)
  {
    $this->addPermissions($permissions);
    return $this;
  }

  public function setPermission($permission, $options = null)
  {
    $this->addPermission($permission, $options);
    return $this;
  }



  // Tests

  public function addTest($test)
  {
    if( !($test instanceof Engine_Package_Manifest_Entity_Test) ) {
      $test = new Engine_Package_Manifest_Entity_Test($test);
    }
    $this->_tests[] = $test;
    return $this;
  }

  public function addTests(array $tests = null)
  {
    foreach( (array) $tests as $test ) {
      $this->addTest($test);
    }
    return $this;
  }

  public function getTests()
  {
    return $this->_tests;
  }

  public function setTest()
  {
    $this->addTest($options);
    return $this;
  }

  public function setTests(array $tests = null)
  {
    $this->addTests($tests);
    return $this;
  }



  // Data conversion

  public function read($file = null)
  {
    // Detect base path if necessary
    if( null === $this->_basePath ) {
      if( !is_string($file) ) {
        throw new Engine_Package_Manifest_Exception(sprintf('Unknown source format: "%s"', gettype($file)));
      } else if( substr($file, 1, 2) != ':\\' && $file[0] != '/' ) {
        throw new Engine_Package_Manifest_Exception(sprintf('Path "%s" is not absolute and no base path defined', $file));
      } else if( is_dir($file) ) {
        $this->_basePath = $file;
      } else if( is_file($file) ) {
        $this->_basePath = dirname($file);
      } else {
        $this->getBasePath(); // Initalize to default
      }
    }

    // Make sure file is an absolute path
    if( $file === $this->getBasePath() ) {
      // We're good
    } else if( substr($file, 0, strlen($this->getBasePath())) === $this->getBasePath() ) {
      // We're good
      //$file = ltrim(substr($file, - (strlen($file) - strlen($this->getBasePath()))), '/');
    } else {
      $file = $this->getBasePath() . DIRECTORY_SEPARATOR . $file;
    }

    if( is_dir($file) ) {
      $packageFiles = glob(rtrim($file, '/\\') . DIRECTORY_SEPARATOR . 'package.*');
      if( !is_array($packageFiles) || count($packageFiles) != 1 ) {
        throw new Engine_Package_Manifest_Exception(sprintf('Found %d package files in directory %s', count($packageFiles), $file));
      }
      $file = $file . DIRECTORY_SEPARATOR . basename($packageFiles[0]);
    } else if( !is_file($file) ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown source file: "%s"', $file));
    }

    $parser = Engine_Package_Manifest_Parser::factory($file);
    $this->fromArray($parser->fromFile($file));
    $this->_sourcePath = $file;
    
    return $this;
  }

  public function write($file = null)
  {
    $file = $this->_getPath($file);

    $parser = Engine_Package_Manifest_Parser::factory($file);
    $parser->toFile($this->getBasePath() . DIRECTORY_SEPARATOR . $file, $this->toArray());

    return $this;
  }

  public function fromString($string)
  {
    $parser = Engine_Package_Manifest_Parser::factory('json');
    $this->fromArray($parser->fromString($string));

    return $this;
  }

  public function toString($file = null)
  {
    $parser = Engine_Package_Manifest_Parser::factory($file);
    return $parser->toString($this->toArray());
  }

  public function toArray()
  {
    $arr = parent::toArray();
    // Meta
    if( $arr['meta'] instanceof Engine_Package_Manifest_Entity_Abstract ) {
      $arr['meta'] = $arr['meta']->toArray();
    }
    // Callbacks
    if( $arr['callback'] instanceof Engine_Package_Manifest_Entity_Callback ) {
      $arr['callback'] = $arr['callback']->toArray();
    }
    // Dependencies
    foreach( (array) $arr['dependencies'] as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Abstract ) {
        $arr['dependencies'][$key] = array_merge(array(
          //'type' => $value->getEntityType(),
        ), $value->toArray());
      }
    }
    // Permissions
    foreach( (array) $arr['permissions'] as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Abstract ) {
        $arr['permissions'][$key] = array_merge(array(
          //'type' => $value->getEntityType(),
        ), $value->toArray());
      }
    }
    // Tests
    foreach( (array) $arr['tests'] as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Test ) {
        $arr['tests'][$key] = array_merge(array(
          //'type' => $value->getEntityType(),
        ), $value->toArray());
      }
    }
    // Structure
    foreach( (array) $arr['structure'] as $key => $value ) {
      if( $value instanceof Engine_Package_Manifest_Entity_Abstract ) {
        $arr['structure'][$key] = array_merge(array(
          'type' => $value->getEntityType(),
        ), $value->toArray());
      }
    }
    return $arr;
  }

  public function addToArchive(Archive_Tar $archive)
  {
    // Add package file
    $rval = $archive->addString('application' . DIRECTORY_SEPARATOR . 'packages' .
      DIRECTORY_SEPARATOR . $this->getKey() . '.json', $this->toString('json'));
    if( $archive->isError($rval) ) {
      throw new Engine_Package_Manifest_Exception('Error in archive: ' . $rval->getMessage());
    }

    // Add internal structure
    if( $this->getAddDirectoryToArchive() ) {
      $rval = $archive->addModify($this->getBasePath() . DIRECTORY_SEPARATOR . $this->getPath(), null, $this->getBasePath());
      if( $archive->isError($rval) ) {
        throw new Engine_Package_Manifest_Exception('Error in archive: ' . $rval->getMessage());
      }
    } else {
      foreach( $this->getStructure() as $key => $value ) {
        if( !($value instanceof Engine_Package_Manifest_Entity_Abstract) ) continue;

        if( method_exists($value, 'setAddDirectoryToArchive') ) {
          $value->setAddDirectoryToArchive($this->getAddDirectoryToArchive());
        }

        $value->addToArchive($archive);
      }
    }
  }



  // Utility

  protected function _getPath($file = null)
  {
    if( null === $this->_path && null === $file ) {
      throw new Engine_Package_Exception('no source file defined');
    } else if( null === $file ) {
      return $this->_path;
    } else {
      return $file;
    }
  }

  protected function _setPath($source)
  {
    if( !is_string($source) ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown source format: "%s"', gettype($source)));
    } else if( is_file($this->getBasePath() . DIRECTORY_SEPARATOR . $source) ) {
      $this->_path = $source;
      $this->read($this->getBasePath() . DIRECTORY_SEPARATOR . $source);
    } else if( is_dir($this->getBasePath() . DIRECTORY_SEPARATOR . $source) ) {
      $packageFiles = glob(rtrim($this->getBasePath() . DIRECTORY_SEPARATOR . $source, '/\\') . DIRECTORY_SEPARATOR . 'package.*');
      if( count($packageFiles) != 1 ) {
        throw new Engine_Package_Manifest_Exception(sprintf('Found %d package files in target directory.', count($packageFiles)));
      }
      $this->_path = $source . '/' . basename($packageFiles[0]);
      $this->read($packageFiles[0]);
    } else {
      //throw new Engine_Package_Manifest_Exception(sprintf('Missing source path: "%s"', $source));
    }
  }

  protected function _getParserClass($ext)
  {
    if( strpos($ext, '.') !== false ) {
      $ext = strtolower(ltrim(strrchr($ext, '.'), '.'));
    }

    $class = 'Engine_Package_Manifest_Parser_' . ucfirst($ext);

    if( !class_exists($class) ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown source format "%s"', $ext));
    }

    if( !is_subclass_of($class, 'Engine_Package_Manifest_Parser_Abstract') ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Unknown source format "%s"', $ext));
    }

    return $class;
  }
}
