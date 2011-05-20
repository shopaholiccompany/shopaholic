<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Package
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Dependencies.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Filter
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Package_Manager_Dependencies
{
  protected $_package;

  protected $_dependencies;

  protected $_dependecyPackages;
  
  public function __construct(Engine_Package_Manifest_Entity_Package $package)
  {
    $this->_package = $package;
  }

  public function getPackage()
  {
    return $this->_package;
  }
  


  // Dependencies

  public function addDependency($dependency)
  {
    if( !($dependency instanceof Engine_Package_Manifest_Entity_Dependency) ) {
      $dependency = new Engine_Package_Manifest_Entity_Dependency($dependency);
    }
    
    $this->_dependencies[$dependency->getGuid()] = $dependency;

    return $this;
  }

  public function addDependencies(array $dependencies = null)
  {
    foreach( $dependencies as $dependency ) {
      $this->addDependency($dependency);
    }
    return $this;
  }

  public function clearDependencies()
  {
    $this->_dependencies = array();
    return $this;
  }

  public function getDependency($package)
  {
    $guid = null;
    if( is_string($package) ) {
      $guid = $package;
    } else if( $package instanceof Engine_Package_Manifest_Entity_Package ) {
      $guid = $package->getGuid();
    } else {
      return false; // throw?
    }

    if( isset($this->_dependencies[$guid]) ) {
      return $this->_dependencies[$guid];
    }

    return null;
  }

  public function getDependencies()
  {
    return $this->_dependencies;
  }

  public function setDependency($dependency)
  {
    $this->addDependency($dependency);
    return $this;
  }

  public function setDependencies(array $dependencies = null)
  {
    $this->addDependencies($dependencies);
    return $this;
  }



  // Comparison

  public function compare(array $packages)
  {
    foreach( (array) $this->getDependencies() as $dependency ) {
      $this->_dependecyPackages[$dependency->getGuid()] = @$packages[$dependency->getGuid()];
      $dependency->compare($this->_dependecyPackages[$dependency->getGuid()]);
    }
    return $this;
  }

  public function hasErrors()
  {
    $hasErrors = false;
    foreach( $this->_dependencies as $dependency ) {
      if( $dependency->getStatus() != Engine_Package_Manifest_Entity_Dependency::OKAY ) {
        $hasErrors = true;
      }
    }
    return $hasErrors;
  }
}