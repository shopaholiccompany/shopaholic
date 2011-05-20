<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Package
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Dependency.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Filter
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Package_Manifest_Entity_Dependency extends Engine_Package_Manifest_Entity_Abstract
{
  const REV_MISSING = -3;
  const REV_HIGH = -2;
  const REV_LOW = -1;
  const OKAY = 0;
  const LOW = 1;
  const HIGH = 2;
  const MISSING = 3;

  protected $_type;

  protected $_name;

  protected $_minVersion;

  protected $_maxVersion;

  protected $_required = true;
  
  protected $_props = array(
    'type',
    'name',
    'guid',
    'minVersion',
    'maxVersion',
  );
  
  public function __construct($spec, $options = null)
  {
    if( is_array($options) ) {
      $this->setOptions($options);
    }
    if( is_array($spec) ) {
      $this->setOptions($spec);
    }
    if( is_string($spec) ) {
      if( strpos($spec, '-') !== false ) {
        $parts = explode('-', $spec, 2);
        $this->setType($parts[0]);
        $this->setName($parts[1]);
      } else {
        $this->setName($spec);
      }
    }
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

  public function getGuid()
  {
    return sprintf('%s-%s', $this->getType(), $this->getName());
  }

  public function getMinVersion()
  {
    return $this->_minVersion;
  }

  public function setMinVersion($minVersion)
  {
    $this->_minVersion = (string) $minVersion;
    return $this;
  }

  public function getMaxVersion()
  {
    return $this->_maxVersion;
  }

  public function setMaxVersion($maxVersion)
  {
    $this->_maxVersion = (string) $maxVersion;
    return $this;
  }

  public function getRequired()
  {
    return (bool) $this->_required;
  }

  public function setRequired($flag)
  {
    $this->_required = (bool) $flag;
    return $this;
  }

  public function setStatus($status)
  {
    $this->_status = $status;
    return $this;
  }

  public function getStatus()
  {
    return $this->_status;
  }



  // Manager

  public function compare($package)
  {
    if( null === $package ) {
      $this->setStatus(self::MISSING);
      return $this;
    }

    if( !($package instanceof Engine_Package_Manifest_Entity_Package) ) {
      throw new Engine_Package_Manifest_Exception('Not a package');
    } else if( $package->getGuid() != $this->getGuid() ) {
      throw new Engine_Package_Manifest_Exception(sprintf('Given invalid package %s, mine is %s', $package->getGuid(), $this->getGuid()));
    }

    if( false != ($minVersion = $this->getMinVersion()) && !version_compare($package->getVersion(), $minVersion, '>=') ) {
      $this->setStatus(self::LOW);
    } else if( false != ($maxVersion = $this->getMaxVersion()) && !version_compare($package->getVersion(), $maxVersion, '<=') ) {
      $this->setStatus(self::HIGH);
    } else {
      $this->setStatus(self::OKAY);
    }
    
    return $this;
  }
}
