<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Api
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Api.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Api
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Engine_Api
{
  /**
   * The singleton Api object
   *
   * @var Engine_Api
   */
  protected static $_instance;

  /**
   * The current application instance
   * 
   * @var Engine_Application
   */
  protected $_application;

  /**
   * An array of module api objects
   * 
   * @var array
   */
  protected $_modules = array();

  /**
   * Contains the current set module name
   * @var string
   */
  protected $_currentModuleName;
  
  /**
   * @var array assoc map of item type => module
   */
  protected $_itemTypes;

  /**
   * Get or create the current api instance
   * 
   * @return Engine_Api
   */
  public static function getInstance()
  {
    if( is_null(self::$_instance) )
    {
      self::$_instance = new self();
    }
    
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance
   *
   * @return Engine_Api
   */
  public static function _()
  {
    return self::getInstance();
  }

  /**
   * Set or unset the current api instance
   * 
   * @param Engine_Api $api
   * @return Engine_Api
   */
  public static function setInstance(Engine_Api $api = null) 
  {
    return self::$_instance = $api;
  }

  public function getAutoloader()
  {
    if( null === $this->_autoloader )
    {
      throw new Exception('Autoloader not set');
    }

    return $this->_autoloader;
  }

  public function setAutoloader(Engine_Application_Autoloader $autoloader)
  {
    $this->_autoloader = $autoloader;
    return $this;
  }



  // Application
  
  /**
   * Sets the current application instance
   * 
   * @param Engine_Application $application
   * @return Engine_Api
   */
  public function setApplication(Engine_Application $application)
  {
    $this->_application = $application;
    return $this;
  }

  /**
   * Gets the current application object
   * 
   * @return Engine_Application
   * @throws Engine_Api_Exception If application is not set
   */
  public function getApplication()
  {
    if( is_null($this->_application) )
    {
      throw new Engine_Api_Exception('Application instance not set');
    }
    
    return $this->_application;
  }



  // Bootstraps
  
  /**
   * Checks if the specfied module has been bootstrapped
   * 
   * @param string $name The module name
   * @return bool
   */
  public function hasModuleBootstrap($name)
  {
    return isset($this->_modules[$name]);
  }

  /**
   * Sets the local copy of a module bootstrap
   * 
   * @param Zend_Application_Module_Bootstrap $bootstrap
   * @return Engine_Api
   */
  public function setModuleBootstrap(Engine_Application_Bootstrap_Abstract $bootstrap)
  {
    $name = strtolower($bootstrap->getModuleName());
    $this->_modules[$name] = $bootstrap;
    return $this;
  }

  /**
   * Gets a module bootstrap
   * 
   * @param string $name The module name
   * @return Zend_Application_Module_Bootstrap|Zend_Application_Bootstrap_Bootstrap
   * @throws Engine_Api_Exception If module not found
   */
  public function getModuleBootstrap($name = null)
  {
    if( !$name )
    {
      $name = Zend_Controller_Front::getInstance()->getDefaultModule();
    }

    if( !isset($this->_modules[$name]) )
    {
      // Special case, default module can be detected and set
      if( $name == Zend_Controller_Front::getInstance()->getDefaultModule() )
      {
        $this->_modules[$name] = $this->getApplication()->getBootstrap();
      }

      // Normal modules must be registered manually
      else
      {
        throw new Engine_Api_Exception(sprintf('Module "%s" not found', $name));
      }
    }

    return $this->_modules[$name];
  }



  // Loading
  
  /**
   * Shorthand for loadModuleApi
   *
   * @return Engine_Application_Module_Api
   * @throws Engine_Api_Exception If given improper arguments or module is missing
   */
  public function __call($method, $args)
  {
    if( 'get' == substr($method, 0, 3) )
    {
      $type = strtolower(substr($method, 3));
      if( empty($args) )
      {
        throw new Engine_Api_Exception("Cannot load resources; no resource specified");
      }
      $resource = array_shift($args);
      $module = array_shift($args);
      if( $module === null )
      {
        if( $this->_currentModuleName === null )
        {
          throw new Engine_Api_Exception("Cannot load resources; no module specified");
        }
        else
        {
          $module = $this->_currentModuleName;
          $this->_currentModuleName = null;
        }
      }
      
      return $this->load($module, $type, $resource);
    }

    // Backwards
    if( isset($this->_modules[$method]) )
    {
      return $this->load($method, 'api', 'core');
      //return $this->load($method, 'model', 'api');
    }

    // Boo
    throw new Engine_Exception("Method '$method' is not supported");
  }

  /**
   * Used to shorten some api calls, sets the default module to load resources
   * from
   * 
   * @param string $module
   * @return Engine_Api
   */
  public function setCurrentModule($module)
  {
    if( is_string($module) )
    {
      $this->_currentModuleName = $module;
    }
    else if( is_object($module) && method_exists($object, 'getModuleName') )
    {
      $this->_currentModuleName = $object->getModuleName();
    }
    else
    {
      $this->_currentModuleName = null;
    }

    return $this;
  }

  /**
   * Loads a singleton instance of a module resource
   *
   * @param string $module The module name
   * @param string $type The resource type
   * @param string $resource The resource name
   * @return mixed The requested singleton object
   */
  public function load($module, $type, $resource)
  {
    if( strtolower($type) == 'dbtable' )
    {
      $type = 'Model_DbTable';
    }
    return Engine_Loader::getInstance()->load(ucfirst($module) . '_' . ucfirst($type) . '_' . ucfirst($resource));
    //return $this->getModuleBootstrap($module)->getResourceLoader()->load($resource, $type);
  }

  /**
   * Loads a singleton instance of a module resource using a full class name
   *
   * @param string $class The class name
   * @return mixed The requested singleton object
   */
  public function loadClass($class)
  {
    return Engine_Loader::getInstance()->load($class);
  }



  // Item handling stuff

  /**
   * Checks if the item of $type has been registered
   * 
   * @param string $type
   * @return bool
   */
  public function hasItemType($type)
  {
    $this->_loadItemInfo();
    return isset($this->_itemTypes[$type]);
  }
  
  /**
   * Gets an item given a type and identity
   * 
   * @param string $type
   * @param int $identity
   * @return Core_Model_Item_Abstract
   */
  public function getItem($type, $identity)
  {
    $this->_loadItemInfo();
    
    $api = $this->getItemApi($type);

    $method = 'get'.ucfirst($type);
    if( method_exists($api, $method) )
    {
      return $api->$method($identity);
    }
    else if( method_exists($api, 'getItem') )
    {
      return $api->getItem($type, $identity);
    }

    return $this->getItemTable($type)->find($identity)->current();
  }

  /**
   * Gets multiple items of a type from an array of ids
   * 
   * @param string $type
   * @param array $identities
   * @return Engine_Db_Table_Rowset
   */
  public function getItemMulti($type, array $identities)
  {
    $this->_loadItemInfo();
    
    $api = $this->getItemApi($type);

    $method = 'get'.ucfirst($type).'Multi';
    if( method_exists($api, $method) )
    {
      return $api->$method($identities);
    }
    else if( method_exists($api, 'getItemMulti') )
    {
      return $api->getItemMulti($type, $identities);
    }

    return $this->getItemTable($type)->find($identities);
  }
  
  /**
   * Gets an item using a guid array or string
   * 
   * @param array|string $guid
   * @return Core_Model_Item_Abstract
   * @throws Engine_Api_Exception If given improper arguments
   */
  public function getItemByGuid($guid)
  {
    $this->_loadItemInfo();
    
    if( is_string($guid) )
    {
      $guid = explode('_', $guid);
      if( count($guid) > 2 )
      {
        $id = array_pop($guid);
        $guid = array(join('_', $guid), $id);
      }
    }
    if( !is_array($guid) || count($guid) !== 2 || !is_string($guid[0]) || !is_numeric($guid[1]) )
    {
      throw new Engine_Api_Exception(sprintf('Malformed guid passed to getItemByGuid(): %s', join('_', $guid)));
    }
    return $this->getItem($guid[0], $guid[1]);
  }

  /**
   * Gets the name of the module that an item type belongs to
   * 
   * @param string $type The item type
   * @return string The module name
   * @throws Engine_Api_Exception If item type isn't registered
   */
  public function getItemModule($type)
  {
    $this->_loadItemInfo();
    
    return $this->getItemInfo($type, 'module');
  }

  /**
   * Gets info about an item
   * 
   * @param string $type The item type
   * @param string (OPTIONAL) $key The info key
   * @return mixed
   */
  public function getItemInfo($type, $key = null)
  {
    $this->_loadItemInfo();
    
    if( empty($this->_itemTypes[$type]) )
    {
      throw new Engine_Api_Exception(sprintf("Unknown item type: %s", $type));
    }
    
    if( null === $key )
    {
      return $this->_itemTypes[$type];
    }

    else if( array_key_exists($key, $this->_itemTypes[$type]) )
    {
      return $this->_itemTypes[$type][$key];
    }
    
    return null;
  }

  /**
   * Gets the class of an item
   *
   * @param string $type The item type
   * @return string The class name
   */
  public function getItemClass($type)
  {
    $this->_loadItemInfo();
    
    // Check api for overriding method
    $api = $this->getItemApi($type);
    if( method_exists($api, 'getItemClass') )
    {
      return $api->getItemClass($type);
    }

    // Generate item class manually
    $module = $this->getItemModule($type);
    return ucfirst($module) . '_Model_' . self::typeToClassSuffix($type, $module);
  }

  /**
   * Gets the class of the dbtable that an item type belongs to
   *
   * @param string $type The item type
   * @return string The table class name
   */
  public function getItemTableClass($type)
  {
    $this->_loadItemInfo();
    
    // Check api for overriding method
    $api = $this->getItemApi($type);
    if( method_exists($api, 'getItemTableClass') )
    {
      return $api->getItemTableClass($type);
    }

    // Generate item table class manually
    $module = $this->getItemInfo($type, 'moduleInflected');
    $class = $module . '_Model_DbTable_' . self::typeToClassSuffix($type, $module);
    if( substr($class, -1, 1) === 'y' ) {
      $class = substr($class, 0, -1) . 'ies';
    } else if( substr($class, -1, 1) !== 's' ) {
      $class .= 's';
    }
    return $class;
  }

  /**
   * Gets a singleton instance of the dbtable an item type belongs to
   *
   * @param string $type The item type
   * @return Engine_Db_Table The table object
   */
  public function getItemTable($type)
  {
    $this->_loadItemInfo();
    
    // Check api for overriding method
    $api = $this->getItemApi($type);
    if( method_exists($api, 'getItemTable') )
    {
      return $api->getItemTable($type);
    }

    $class = $this->getItemTableClass($type);
    return $this->loadClass($class);
  }

  /**
   * Gets the item api object that an item type belongs to
   *
   * @param string $type The item type
   * @return Engine_Application_Module_Api
   */
  public function getItemApi($type)
  {
    $this->_loadItemInfo();
    
    $module = $this->getItemInfo($type, 'moduleInflected');
    return $this->load($module, 'api', 'core');
  }

  /**
   * Load item info from manifest
   */
  protected function _loadItemInfo()
  {
    if( null === $this->_itemTypes )
    {
      $manifest = Zend_Registry::get('Engine_Manifest');
      if( null === $manifest )
      {
        throw new Engine_Api_Exception('Manifest data not loaded!');
      }
      $this->_itemTypes = array();
      foreach( $manifest as $module => $config )
      {
        if( !isset($config['items']) ) continue;
        foreach( $config['items'] as $key => $value )
        {
          if( is_numeric($key) ) {
            $this->_itemTypes[$value] = array(
              'module' => $module,
              'moduleInflected' => self::inflect($module),
            );
          } else {
            $this->_itemTypes[$key] = $value;
            $this->_itemTypes[$key]['module'] = $module;
            $this->_itemTypes[$key]['moduleInflected'] = self::inflect($module);
          }
        }
      }
    }
  }



  // Utility

  static public function inflect($string)
  {
    return str_replace(' ', '', ucwords(str_replace(array('.', '-'), ' ' , $string)));
  }

  static public function deflect($string)
  {
    return strtolower(trim(preg_replace('/([a-z0-9])([A-Z])/', '\1-\2', $string), '-. '));
    //return strtolower(trim(preg_replace('/([a-z0-9])([A-Z])/', '\1-\2', preg_replace('/[^A-Za-z0-9-]/', '', $string)), '-. '));
  }

  /**
   * Used to inflect item types to class suffix.
   * 
   * @param string $type
   * @param string $module
   * @return string
   */
  static public function typeToClassSuffix($type, $module)
  {
    $parts = explode('_', $type);
    if( count($parts) > 1 && $parts[0] === strtolower($module) )
    {
      array_shift($parts);
    }
    $partial = str_replace(' ', '', ucwords(join(' ', $parts)));
    return $partial;
  }

  /**
   * Used to inflect item class to type.
   * 
   * @param string $class
   * @param string $module
   * @return string
   * @throws Engine_Api_Exception If given improper arguments
   */
  static public function classToType($class, $module)
  {
    list($classModule, $resourceType, $resourceName)
      = explode('_', $class, 3);

    // Throw stuff
    if( strtolower($classModule) != strtolower($module) )
    {
      throw new Engine_Api_Exception('class and module do not match');
    }
    else if( $resourceType != 'Model' )
    {
      throw new Engine_Api_Exception('resource type must be a model');
    }

    // Parse camel case
    preg_match_all('/([A-Z][a-z]+)/', $resourceName, $matches);
    if( empty($matches[0]) )
    {
      throw new Engine_Exception('resource name not useable');
    }
    $matches = $matches[0];

    // Append module name if first not equal
    if( strtolower($matches[0]) != strtolower($module) )
    {
      array_unshift($matches, $module);
    }
    $type = strtolower(join('_', $matches));
    return $type;
  }

  /**
   * Inflects a type to the class name suffix
   * @todo Not used?
   * 
   * @param string $type
   * @param string $module
   * @return string
   */
  static public function typeToShort($type, $module)
  {
    $parts = explode('_', $type);
    if( count($parts) > 1 && strtolower($parts[0]) == strtolower($module) )
    {
      array_shift($parts);
    }
    return strtolower(join('_', $parts));
  }
}
