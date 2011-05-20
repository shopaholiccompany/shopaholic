<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Package
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Abstract.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Filter
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
abstract class Engine_Package_Manager_Operation_Abstract
{
  /**
   * @var Engine_Package_Manager
   */
  protected $_manager;

  /**
   * Package
   * @var Engine_Package_Manifest
   */
  protected $_package;

  /**
   * The previous package
   * @var Engine_Package_Manifest
   */
  protected $_previousPackage;

  /**
   * Generally, APPLICATION_PATH . '/temporary/package/packages/' . $packageKey
   * @var string
   */
  protected $_sourcePath;

  /**
   * Generally, APPLICATION_PATH
   * @var string
   */
  protected $_destinationPath;

  protected $_previousPackageSource;



  // General
  
  public function __construct(Engine_Package_Manager $manager, Engine_Package_Manifest $package, $previousPackage = null, $options = null)
  {
    $this->_manager = $manager;
    $this->_package = $package;

    if( is_array($previousPackage) ) {
      $this->setOptions($previousPackage);
    } else if( $previousPackage instanceof Engine_Package_Manifest ) {
      $this->_previousPackage = $previousPackage;
    }
    if( is_array($options) ) {
      $this->setOptions($options);
    }

    if( null === $this->_destinationPath && defined('APPLICATION_PATH') ) {
      $this->_destinationPath = APPLICATION_PATH;
    }
    if( null === $this->_sourcePath ) {
      $this->_sourcePath = $this->_package->getSourcePath();
    }
    if( null !== $this->_previousPackage ) {
      $this->_previousPackageSource = $this->_previousPackage->getSourcePath();
    }
  }

  public function __sleep()
  {
    return array('_sourcePath', '_destinationPath', '_previousPackageSource', '_package', '_previousPackage');
  }

  public function __wakeup()
  {
    /*
    $this->_package = new Engine_Package_Manifest($this->_sourcePath);
    if( null !== $this->_previousPackageSource ) {
      $this->_previousPackage = new Engine_Package_Manifest($this->_previousPackageSource);
    }
    */
  }

  public function getOperationType()
  {
    return strtolower(ltrim(strrchr(get_class($this), '_'), '_'));
  }

  public function getKey()
  {
    return $this->getOperationType() . '-' .
      $this->getPackage()->getKey() .
      ( $this->getPreviousPackage() ? $this->getPreviousPackage()->getKey() : '' );
  }



  // Manager

  public function getManager()
  {
    if( null === $this->_manager ) {
      throw new Engine_Package_Manager_Operation_Exception('No manager defined');
    }
    return $this->_manager;
  }

  public function setManager(Engine_Package_Manager $manager)
  {
    //if( null !== $this->_manager ) {
    //  throw new Engine_Package_Manager_Operation_Exception('Manager already defined');
    //}
    $this->_manager = $manager;
    return $this;
  }



  // Package

  public function getPackage()
  {
    if( null === $this->_package ) {
      throw new Engine_Package_Manager_Operation_Exception('No package defined');
    }
    return $this->_package;
  }

  public function getPreviousPackage()
  {
    return $this->_previousPackage;
  }

  public function getPackageGuid()
  {
    return $this->getPackage()->getGuid();
  }

  public function getSourcePackage()
  {
    return $this->getPreviousPackage();
  }

  public function getResultantPackage()
  {
    return $this->getPackage();
  }



  // Options
  
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

  public function setSourcePath($path)
  {
    $this->_sourcePath = $path;
    return $this;
  }

  public function getSourcePath()
  {
    if( null === $this->_sourcePath ) {
      throw new Engine_Package_Manager_Operation_Exception('No source path defined');
    }
    return $this->_sourcePath;
  }

  public function setDestinationPath($path)
  {
    $this->_destinationPath = $path;
    return $this;
  }

  public function getDestinationPath()
  {
    if( null === $this->_destinationPath ) {
      throw new Engine_Package_Manager_Operation_Exception('No destination path defined');
    }
    return $this->_destinationPath;
  }



  // Dependencies



  // Tests

  public function getTests()
  {
    // No resultant package
    $resultantPackage = $this->getResultantPackage();
    if( !$resultantPackage ) {
      return false;
    }

    // No tests
    $tests = $resultantPackage->getTests();
    if( empty($tests) ) {
      return false;
    }

    // Make battery
    $battery = new Engine_Sanity(array(
      'name' => $resultantPackage->getKey(),
    ));
    foreach( $tests as $test ) {
      $battery->addTest($test->toArray());
    }

    return $battery;
  }



  // Diff

  public function getDiff()
  {
    $leftFilesRaw = $this->getDiffMasterFiles();
    $rightFilesRaw = $this->getDiffResultantFiles();
    $originalFilesRaw = $this->getDiffSourceFiles();

    $leftFiles = array();
    $rightFiles = array();
    $originalFiles = array();

    $leftPath = $this->getManager()->getBasePath();
    $rightPath = null !== $this->getResultantPackage() ? $this->getResultantPackage()->getBasePath() : null;
    $originalPath = null !== $this->getSourcePackage() ? $this->getSourcePackage()->getBasePath() : null;

    foreach( $leftFilesRaw as $file ) {

      // Skip directories

      // Format left
      $left = $file;

      // Format right
      $right = null;
      if( isset($rightFilesRaw[$file]) ) {
        if( isset($rightFilesRaw[$file]['dir']) && $rightFilesRaw[$file]['dir'] ) continue;
        $right = $rightFilesRaw[$file];
        //$right = $this->_formatFileData($rightFilesRaw[$file], $rightPath, $file);
      }

      // Format original
      $original = null;
      if( isset($originalFilesRaw[$file]) ) {
        if( isset($originalFilesRaw[$file]['dir']) && $originalFilesRaw[$file]['dir'] ) continue;
        $original = $originalFilesRaw[$file];
        //$original = $this->_formatFileData($originalFilesRaw[$file], $originalPath, $file);
      }

      $leftFiles[] = $this->_formatFileData($left, $leftPath, $file);
      $rightFiles[] = $this->_formatFileData($right, $rightPath, $file);
      $originalFiles[] = $this->_formatFileData($original, $originalPath, $file);
    }

    // Skip the three-way (since we don't have any source info)
    if( empty($originalFilesRaw) ) {
      $originalFiles = null;
    }

    $diff = Engine_File_Diff_Batch::factory($leftFiles, $rightFiles, $originalFiles);
    
    $diff->package = $this->getResultantPackage();
    $diff->previousPackage = $this->getSourcePackage();

    if( $diff->package ) {
      $diff->packageKey = $diff->package->getKey();
    } else if( $diff->previousPackage ) {
      $diff->packageKey = $diff->previousPackage->getKey();
    }

    return $diff;
  }
  
  public function getDiffSourceFiles()
  {
    $sourcePackage = $this->getSourcePackage();
    if( !$sourcePackage ) {
      return array();
    }

    // Get files
    $files = $this->_getPackageFiles($sourcePackage);

    // Add package file?
    $packageFile = 'application/packages/' . $sourcePackage->getKey() . '.json';
    $files[$packageFile] = Engine_File_Diff_File::build($sourcePackage->getBasePath() . DIRECTORY_SEPARATOR . $packageFile);
    $files[$packageFile]['path'] = $packageFile;
    
    return $files;
  }

  public function getDiffResultantFiles()
  {
    $resultantPackage = $this->getResultantPackage();
    if( !$resultantPackage ) {
      return array();
    }
    
    // Get files
    $files = $this->_getPackageFiles($resultantPackage);

    // Add package file?
    $packageFile = 'application/packages/' . $resultantPackage->getKey() . '.json';
    $files[$packageFile] = Engine_File_Diff_File::build($resultantPackage->getBasePath() . DIRECTORY_SEPARATOR . $packageFile);
    $files[$packageFile]['path'] = $packageFile;

    return $files;
  }

  public function getDiffMasterFiles()
  {
    return array_unique(array_merge(
      array_keys($this->getDiffSourceFiles()),
      array_keys($this->getDiffResultantFiles())
    ));
  }



  // Cleanup

  public function cleanup()
  {
    $manager = $this->getManager();
    $basePath = $manager->getBasePath();
    $tempVfs = Engine_Vfs::factory('system', array(
      'path' => $basePath,
    ));

    $archivesPath = $manager->getAbsPath(Engine_Package_Manager::PATH_ARCHIVES);
    $packagesPath = $manager->getAbsPath(Engine_Package_Manager::PATH_PACKAGES);

    $sourcePackage = $this->getResultantPackage();
    if( $sourcePackage ) {
      // Key-based
      $archivePath = $archivesPath . '/' . $sourcePackage->getKey() . '.tar';
      if( $tempVfs->exists($archivePath) ) {
        $tempVfs->unlink($archivePath);
      }
      $extractedPath = $packagesPath . '/' . $sourcePackage->getKey();
      if( $tempVfs->exists($extractedPath) ) {
        $tempVfs->removeDirectory($extractedPath, true);
      }
      // Source-based
      $sourcePath = $sourcePackage->getSourcePath();
      if( $sourcePath && strpos($sourcePath, dirname($archivesPath)) !== false ) {
        $extractedPath = dirname($sourcePath);
        $archivePath = $archivesPath . '/' . basename($extractedPath) . '.tar';
        if( $tempVfs->exists($extractedPath) ) {
          try {
            $tempVfs->removeDirectory($extractedPath, true);
          } catch( Exception $e ) {
            // Silence
          }
        }
        if( $tempVfs->exists($archivePath) ) {
          try {
            $tempVfs->unlink($archivePath);
          } catch( Exception $e ) {
            // Silence
          }
        }
      }
    }

    $resultantPackage = $this->getResultantPackage();
    if( $resultantPackage ) {
      $archivePath = $archivesPath . '/' . $resultantPackage->getKey() . '.tar';
      if( $tempVfs->exists($archivePath) ) {
        $tempVfs->unlink($archivePath);
      }
      $extractedPath = $packagesPath . '/' . $resultantPackage->getKey();
      if( $tempVfs->exists($extractedPath) ) {
        $tempVfs->removeDirectory($extractedPath, true);
      }
    }

    return $this;
  }


  
  // Utility

  protected function _getPackageFiles(Engine_Package_Manifest_Entity_Package $package = null)
  {
    if( null === $package ) {
      return array();
    }
    $files = $package->getFileStructure(true);
    return $files;
  }

  protected function _formatFileData($file, $basePath, $filePath)
  {
    if( is_string($file) ) {
      return $basePath . DIRECTORY_SEPARATOR . $file;
    } else if( is_array($file) && isset($file['path']) ) {
      $file['path'] = $filePath;
      $file['exists'] = true;
      if( isset($file['sha1']) && !isset($file['hash']) ) {
        $file['hash'] = $file['sha1'];
      }
      return $file;
    } else if( is_array($file) ) {
      $file['path'] = $filePath;
      $file['exists'] = false;
      return $file;
    } else if( null === $file ) {
      return array(
        'path' => $filePath,
        'exists' => false,
      );
    } else {
      return $file; // wth
    }
  }
}