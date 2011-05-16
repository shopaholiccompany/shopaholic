<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Package
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Manager.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Filter
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Package_Manager
{
  const PATH_BASE = 'base';
  const PATH_TEMP = 'temporary';
  const PATH_ARCHIVES = 'archives';
  const PATH_MANIFESTS = 'manifests';
  const PATH_PACKAGES = 'packages';
  const PATH_REPOSITORIES = 'repositories';
  const PATH_INSTALLED = 'installed';
  const PATH_SETTINGS = 'settings';
  const PATH_SETTINGS_NODE = 'nodesettings';
  const PATH_SETTINGS_REPOSITORIES = 'repositoriessettings';

  protected $_basePath;

  protected $_paths = array(
    self::PATH_BASE => null,
    self::PATH_TEMP => 'temporary',
    self::PATH_ARCHIVES => 'temporary/package/archives',
    self::PATH_MANIFESTS => 'temporary/package/manifests',
    self::PATH_PACKAGES => 'temporary/package/packages',
    self::PATH_REPOSITORIES => 'temporary/package/repositories',
    self::PATH_INSTALLED => 'application/packages',
    self::PATH_SETTINGS => 'application/settings',
    self::PATH_SETTINGS_NODE => 'application/settings/node.php',
    self::PATH_SETTINGS_REPOSITORIES => 'application/settings/repositories.php',
  );

  protected $_db;
  
  protected $_vfs;

  protected $_repositories;

  protected $_installers;



  // General
  
  public function __construct($options = null)
  {
    if( is_array($options) ) {
      $this->setOptions($options);
    }
  }

  public function __sleep()
  {
    return array('_basePath', '_paths', '_db', '_vfs', '_repositories');
  }

  public function __wakeup()
  {

  }

  public function setOptions(array $options)
  {
    foreach( $options as $key => $value ) {
      $method = 'set' . ucfirst($key);
      if( method_exists($this, $method) ) {
        $this->$method($value);
      }
    }

    return $this;
  }



  // Paths

  public function setPath($path, $type = self::PATH_BASE) {
    if( !array_key_exists($type, $this->_paths) ) {
      throw new Engine_Package_Manager_Exception('Invalid path type');
    }
    $this->_paths[$type] = $path;
    if( $type == self::PATH_BASE ) {
      $this->_basePath = $path; // B/c
    }
    return $this;
  }

  public function setPaths(array $paths)
  {
    foreach( $paths as $type => $path ) {
      $this->setPath($path, $type);
    }
    return $this;
  }

  public function getAbsPath($type)
  {
    $path = $this->getPath($type);
    if( $type !== self::PATH_BASE && @$path[0] != DIRECTORY_SEPARATOR && @$path[1] != ':' ) {
      $path = $this->_basePath . DS . $path;
    }
    return $path;
  }

  public function getPath($type = self::PATH_BASE)
  {
    if( !isset($this->_paths[$type]) ) {
      throw new Engine_Package_Manager_Exception('Invalid path type');
    }
    if( $type == self::PATH_BASE && null === $this->_paths[$type] ) {
      $this->_paths[$type] = $this->_basePath = APPLICATION_PATH; // B/c
    }
    return $this->_paths[$type];
  }

  public function getPaths()
  {
    return $this->_paths;
  }

  public function setBasePath($path)
  {
    return $this->setPath($path, self::PATH_BASE);
  }

  public function getBasePath()
  {
    return $this->getPath(self::PATH_BASE);
  }



  // FTP
  
  public function setVfs(Engine_Vfs_Adapter_Abstract $vfs)
  {
    $this->_vfs = $vfs;
    return $this;
  }

  public function getVfs()
  {
    return $this->_vfs;
  }

  public function setDb(Zend_Db_Adapter_Abstract $db)
  {
    $this->_db = $db;
    return $this;
  }

  /**
   * Get the db adapter
   * 
   * @return Zend_Db_Adapter_Abstract
   */
  public function getDb()
  {
    return $this->_db;
  }



  // Repositories

  public function setRepository($spec, array $options = array())
  {
    $repository = null;
    if( !($spec instanceof Engine_Package_Manager_Repository) ) {
      if( is_string($spec) ) {
        $options['name'] = $spec;
      } else if( is_array($spec) ) {
        $options = array_merge($options, $spec);
      }

      $options['basePath'] = $this->getBasePath();

      $repository = new Engine_Package_Manager_Repository($options);
    }

    $repository->setManager($this);
    $this->_repositories[$repository->getName()] = $repository;

    return $this;
  }

  public function setRepositories(array $repositories)
  {
    foreach( $repositories as $key => $value ) {
      $this->setRepository($key, $value);
    }

    return $this;
  }

  public function getRepositories()
  {
    if( null === $this->_repositories ) {
      $configFile = $this->getAbsPath(self::PATH_SETTINGS_REPOSITORIES);
      $config = include $configFile;
      if( empty($config) || !is_array($config) ) {
        $this->_repositories = array();
      } else {
        $this->setRepositories($config);
      }
    }

    return $this->_repositories;
  }

  /**
   * Gets a repository by name or host
   * 
   * @param string $repository
   * @return Engine_Package_Manager_Repository
   */
  public function getRepository($repository)
  {
    foreach( $this->getRepositories() as $repositoryObject ) {
      if( $repositoryObject->getHost() == $repository || $repositoryObject->getName() == $repository ) {
        return $repositoryObject;
      }
    }
    return null;
  }



  // Actions

  public function decide(array $packages, array $actions = null)
  {
    $extractedPackages = $this->listExtractedPackages();
    $installedPackages = $this->listInstalledPackages();
    
    // Check packages array against extracted packages
    foreach( $packages as $key => $package ) {
      if( is_string($package) ) {
        $package = str_replace(':', '-', $package);
        foreach( $extractedPackages as $extractedPackage ) {
          if( $package == $extractedPackage->getKey() ) {
            $package = $extractedPackage;
            break;
          }
        }
        foreach( $installedPackages as $installedPackage ) {
          if( $package == $installedPackage->getKey() ) {
            $package = $installedPackage;
            break;
          }
        }
      }
      if( $package instanceof Engine_Package_Manifest ) {
        $packages[$key] = $package;
      } else {
        unset($packages[$key]);
      }
    }

    // Check packages array against installed packages
    $operations = array();
    foreach( $packages as $key => $package ) {
      if( !($package instanceof Engine_Package_Manifest) ) {
        continue;
      }
      $action = null;
      if( isset($actions[$key]) ) {
        $action = $actions[$key];
      }
      
      // Check installed packages
      $previousPackage = null;
      foreach( $installedPackages as $installedPackage ) {
        // Packages are not the same
        if( $installedPackage->getType() != $package->getType() || $installedPackage->getName() != $package->getName() ) {
          continue;
        }
        $previousPackage = $installedPackage;
        break;
      }

      if( null === $action ) {
        if( null === $previousPackage ) {
          $action = 'install';
        } else {
          switch( version_compare($previousPackage->getVersion(), $package->getVersion()) ) {
            case 1:
              $action = 'downgrade';
              break;
            case 0:
              //$action = 'ignore';
              $action = 'refresh';
              break;
            case -1:
              $action = 'upgrade';
              break;
            default:
              throw new Engine_Exception('wth happened here?');
              break;
          }
        }
      }

      switch( $action ) {
        case 'remove':
        case 'install':
          $class = 'Engine_Package_Manager_Operation_' . ucfirst($action);
          $operation = new $class($this, $package);
          break;
        case 'refresh':
          $class = 'Engine_Package_Manager_Operation_' . ucfirst($action);
          $operation = new $class($this, $package);
          //$operation = new $class($this, $package, $previousPackage);
          break;
        case 'ignore':
        case 'downgrade':
        case 'upgrade':
          $class = 'Engine_Package_Manager_Operation_' . ucfirst($action);
          $operation = new $class($this, $package, $previousPackage);
          break;
        default:
          throw new Engine_Exception('wth happened here?');
          break;
      }
      
      $operations[] = $operation;
    }

    //$transaction = new Engine_Package_Manager_Transaction($operations);
    //return $transaction;
    
    return $operations;
  }

  public function depend(array $operations)
  {
    // Build target package structure
    $targetPackageStructure = array();
    $installedPackages = $this->listInstalledPackages();
    foreach( $installedPackages as $installedPackage ) {
      $targetPackageStructure[$installedPackage->getGuid()] = $installedPackage;
    }
    
    // Validate operations array
    foreach( $operations as $operation ) {
      if( !($operation instanceof Engine_Package_Manager_Operation_Abstract) ) {
        throw new Engine_Package_Manager_Exception(sprintf('Invalid operation data type "%s"', gettype($operation)));
      }
      // Set manager (in case they were loaded from session
      $operation->setManager($this);
      // Index operations
      $packageGuid = $operation->getPackageGuid();
      $resultantPackage = $operation->getResultantPackage();
      if( $resultantPackage ) {
        $targetPackageStructure[$packageGuid] = $resultantPackage;
      } else {
        unset($targetPackageStructure[$packageGuid]);
      }
    }

    // Check all packages for dependencies
    $targetPackageDependencies = array();
    foreach( $targetPackageStructure as $targetPackage ) {
      $dependencies = $targetPackage->getDependencies();
      // No dependencies
      if( empty($dependencies) ) continue;

      $targetDependencies = new Engine_Package_Manager_Dependencies($targetPackage);
      $targetDependencies->addDependencies($dependencies);
      $targetDependencies->compare($targetPackageStructure);
      $targetPackageDependencies[] = $targetDependencies;
    }

    return $targetPackageDependencies;
  }

  public function test(array $operations)
  {
    // Check registry for db adapter
    if( Zend_Registry::isRegistered('Zend_Db') && ($db = Zend_Registry::get('Zend_Db')) instanceof Zend_Db_Adapter_Abstract ) {
      Engine_Sanity::setDefaultDbAdapter($db);
    }

    // Make tests
    $batteries = new Engine_Sanity();
    foreach( $operations as $operation ) {
      if( !($operation instanceof Engine_Package_Manager_Operation_Abstract) ) {
        throw new Engine_Package_Manager_Exception(sprintf('Invalid operation data type "%s"', gettype($operation)));
      }
      $battery = $operation->getTests();
      if( $battery ) {
        $batteries->addTest($battery);
      }
    }

    $batteries->run();

    return $batteries;
  }

  public function diff(array $operations)
  {
    $this->_verifyOperationsAndSetSelf($operations);
    
    $diffs = array();
    foreach( $operations as $operation ) {
      $diff = $operation->getDiff();
      if( $diff ) {
        $diff->execute();
        $diffs[] = $diff;
      }
    }

    return $diffs;
  }

  public function callback(array $operations, $type, array $params = null)
  {
    $this->_verifyOperationsAndSetSelf($operations);
    
    // Index by priority
    $priorityIndex = array();
    $callbackIndex = array();
    $operationIndex = array();
    
    foreach( $operations as $operation ) {
      $package = $operation->getPackage();
      $callback = $package->getCallback();
      if( empty($callback) || !($callback instanceof Engine_Package_Manifest_Entity_Callback) || !$callback->getClass() ) {
        continue;
      }
      
      $index = count($callbackIndex);
      $callbackIndex[$index] = $callback;
      $priorityIndex[$index] = $callback->getPriority();
      $operationIndex[$index] = $operation;
    }

    arsort($priorityIndex);

    $results = array();
    
    foreach( $priorityIndex as $index => $priorityIndex ) {
      $callback = $callbackIndex[$index];
      $operation = $operationIndex[$index];
      $package = $operation->getPackage();

      $result = $this->execute($operation, $type, $params);
      $result['type'] = $type;
      $result['operation'] = $operation;
      $results[] = $result;
    }

    return $results;
  }

  public function execute(Engine_Package_Manager_Operation_Abstract $operation, $type, array $params = null)
  {
    $package = $operation->getPackage();
    $callback = $package->getCallback();
    if( !$callback || !($callback instanceof Engine_Package_Manifest_Entity_Callback) || !$callback->getClass() ) {
      return false;
    }

    // Include the path, if set
    if( $callback->getPath() ) {
      include_once $package->getBasePath() . '/' . $callback->getPath();
    }
    
    try {
      $instance = $this->getInstaller($callback->getClass(), $operation, $params);
      $instance->notify($type);
      $errors = $instance->getErrors();
      $messages = $instance->getMessages();
      $instance->clearErrors()->clearMessages();
    } catch( Exception $e ) {
      $errors = array($e->getMessage());
      $messages = array();
    }

    return array(
      'errors' => $errors,
      'messages' => $messages,
    );
  }

  public function cleanup(array $operations)
  {
    $this->_verifyOperationsAndSetSelf($operations);
    
    foreach( $operations as $operation ) {
      $operation->cleanup();
    }

    return $this;
  }



  // Informational

  public function listInstalledPackages()
  {
    $installedPackages = array();
    $it = new DirectoryIterator($this->getAbsPath(self::PATH_INSTALLED));

    // List installed packages
    foreach( $it as $file ) {
      if( $file->isDot() || $file->isDir() || $file->getFilename() === 'index.html' ) continue;
      try {
        $packageFile = new Engine_Package_Manifest($file->getPathname());
        // Reset base path
        $packageFile->setBasePath($this->_basePath);

        // Check for package files for two versions of the package
        $packageGuid = $packageFile->getGuid();
        if( !isset($installedPackages[$packageGuid]) ) {
          $installedPackages[$packageGuid] = $packageFile;
        } else if( version_compare($packageFile->getVersion(), $installedPackages[$packageGuid]->getVersion(), '>') ) {
          $installedPackages[$packageGuid] = $packageFile;
        } else {
          // Ignore
          // We might want to remove the old package file
        }
      } catch( Exception $e ) {
        // Silence?
        if( APPLICATION_ENV == 'development' ) {
          throw $e;
        }
      }
    }

    return array_values($installedPackages);
    //return $installedPackages;
  }

  public function listAvailablePackages()
  {
    $availablePackages = array();
    $extractedPath = $this->getAbsPath(self::PATH_PACKAGES);
    $it = new DirectoryIterator($this->getAbsPath(self::PATH_ARCHIVES));
    foreach( $it as $file ) {
      if( $file->isDot() || $file->isDir() || $file->getFilename() === 'index.html' ) continue;
      // Already extracted
      if( is_dir($extractedPath . DIRECTORY_SEPARATOR . substr($file->getFilename(), 0, strrpos($file->getFilename(), '.'))) ) continue;
      try {
        $packageFile = Engine_Package_Archive::readPackageFile($file->getPathname());
        $availablePackages[] = $packageFile;
      } catch( Exception $e ) {
        // Silence?
        //if( APPLICATION_ENV == 'development' ) {
        //  throw $e;
        //}
      }
    }

    return $availablePackages;
  }

  public function listExtractedPackages()
  {
    $extractedPackages = array();
    $it = new DirectoryIterator($this->getAbsPath(self::PATH_PACKAGES));
    foreach( $it as $file ) {
      if( $file->isDot() || !$file->isDir() || $file->getFilename() == '.svn' ) continue;
      try {
        $packageFile = new Engine_Package_Manifest($file->getPathname(), array(
          'basePath' => $file->getPathname(),
        ));
        $extractedPackages[] = $packageFile;
      } catch( Exception $e ) {
        // Silence?
        //if( APPLICATION_ENV == 'development' ) {
        //  throw $e;
        //}
      }
    }

    return $extractedPackages;
  }

  public function listUpgradeablePackages()
  {
    $installedPackages = $this->listInstalledPackages();
    $repositories = $this->getRepositories();

    // Index installed packages
    $repoIndex = array();
    foreach( $installedPackages as $installedPackage ) {
      $repositoryName = $installedPackage->getRepository();
      // No repo
      if( empty($repositoryName) ) continue;
      // No configured repo
      if( empty($repositories[$repositoryName]) ) continue;
      // Add to queue
      $repoIndex[$repositoryName][] = $installedPackage;
    }

    // Check for updates
    foreach( $repoIndex as $repositoryName => $packages ) {
      $repository = $repositories[$repositoryName];
      if( empty($repository) ) continue; // Sanity

      //$repository->
    }
  }



  // Utility

  public function getInstaller($class, Engine_Package_Manager_Operation_Abstract $operation,
    array $params = null)
  {
    $key = $operation->getKey();
    if( !isset($this->_installers[$key]) ) {
      if( !class_exists($class) ) { // Forces autoload
        throw new Engine_Package_Installer_Exception(sprintf('Unable to load installer class %s', $class));
      }
      $params['db'] = $this->getDb();
      $params['vfs'] = $this->getVfs();
      $this->_installers[$key] = new $class($operation, $params);
    }
    return $this->_installers[$key];
  }

  protected function _verifyOperationsAndSetSelf($operations)
  {
    if( $operations instanceof Engine_Package_Manager_Operation_Abstract ) {
      $operations->setManager($this);
      return $operations;
    } else if( is_array($operations) ) {
      foreach( $operations as $operation ) {
        if( !($operation instanceof Engine_Package_Manager_Operation_Abstract) ) {
          throw new Engine_Package_Manager_Exception('Not an operation');
        }
        $operation->setManager($this);
      }
      return $operations;
    } else {
      throw new Engine_Package_Manager_Exception('Unknown arguments');
    }
  }
}