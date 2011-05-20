<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: CompareController.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class CompareController extends Zend_Controller_Action
{
  /**
   * @var Engine_Package_Manager
   */
  protected $_packageManager;

  /**
   * @var Zend_Session_Namespace
   */
  protected $_session;

  /**
   * @var Zend_Cache_Core
   */
  protected $_cache;

  /**
   * @var string
   */
  protected $_oldPath;
  
  public function init()
  {
    // Check if already logged in
    if( !Zend_Registry::get('Zend_Auth')->getIdentity() ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }

    // Get manager
    $this->_packageManager = Zend_Registry::get('Engine_Package_Manager');

    // Get session
    $this->_session = new Zend_Session_Namespace('InstallCompareController');

    // Get cache
    if( !Zend_Registry::isRegistered('Cache') ) {
      throw new Engine_Exception('Cache could not be initialized. Please try setting full permissions on temporary/cache');
    }
    $this->_cache = Zend_Registry::get('Cache');

    // Get old path
    $this->_oldPath = APPLICATION_PATH . '/temporary/package/old';
  }

  public function indexAction()
  {
    if( $this->_getParam('clear') ) {
      $this->_flushCache();
      return $this->_helper->redirector->gotoRoute(array());
    }

    // Get file diffs
    $cacheIndex = $this->_getCacheIndex();
    $changed = array();
    $packageIndex = array();

    foreach( $cacheIndex as $packageKey ) {
      $batch = $this->_getCachePackage($packageKey);
      foreach( $batch->getDiffs() as $diff ) {
        if( in_array($diff->getCode(), array('identical')) ) {
          continue;
        }

        $path = $diff->getLeft()->getPath();
        $path = ltrim(str_replace(APPLICATION_PATH, '', $path), '/\\');
        $changed[$packageKey][$diff->getCode()][] = $path;
        $packageIndex[$path] = $packageKey;
      }
    }

    // Get old packages
    $oldPackages = array();
    $it = new DirectoryIterator($this->_oldPath);
    foreach( $it as $child ) {
      if( $it->isDot() || $it->isFile() || !$it->isDir() ) {
        continue;
      }
      $oldPackages[] = $child->getBasename();
    }

    $this->view->changed = $changed;
    $this->view->packageIndex = $packageIndex;
    $this->view->oldPackages = $oldPackages;
  }
  
  public function uploadAction()
  {
    if( !$this->getRequest()->isPost() ) return;
    if( empty($_FILES['Filedata']) ) return;


    if( $this->_getParam('reset', false) ) {
      unset($this->_session->comparePath);
    }

    // Get tmp folder
    $tmpFolder = $this->_oldPath;

    // Make tmp folder
    if( !is_dir($tmpFolder) && !mkdir($tmpFolder, 0777, true) ) {
      throw new Engine_Exception('Unable to make directory: ' . $tmpFolder);
    }

    // Check archive extension
    $packageFile = $_FILES['Filedata']['name'];
    $packageArchive = $tmpFolder . DIRECTORY_SEPARATOR . $packageFile;
    if( strtolower(substr($packageArchive, -4)) !== '.tar' ) {
      throw new Engine_Exception('Not a TAR archive: ' . $_FILES['Filedata']['name']);
    }

    // Try to remove if it already exists
    if( file_exists($packageArchive) && !unlink($packageArchive) ) {
      throw new Engine_Exception('Unable to remove: ' . $_FILES['Filedata']['name']);
    }

    // Move package archive
    if( !move_uploaded_file($_FILES['Filedata']['tmp_name'], $packageArchive) ) {
      throw new Engine_Exception('Unable to move uploaded file: ' . $packageArchive);
    }

    // Extract package archive
    $extractedPath = Engine_Package_Archive::inflate($packageArchive, $tmpFolder);


    // Flush cache
    $this->_flushCache();
  }

  public function diffAction()
  {
    $this->view->layout()->hideIdentifiers = true;
    
    $extractedPath = $this->_oldPath . DIRECTORY_SEPARATOR . $this->_getParam('package');
    $packageObject = new Engine_Package_Manifest($extractedPath);
    
    $file = $this->_getParam('file');
    $sourceFile = $packageObject->getBasePath() . /* . '/' . $packageObject->getPath() .*/ '/' . $file;
    $targetFile = APPLICATION_PATH . /* . '/' . $packageObject->getPath() .*/ '/' . $file;

    include_once 'Text/Diff.php';
    include_once 'Text/Diff/Renderer.php';
    include_once 'Text/Diff/Renderer/context.php';
    include_once 'Text/Diff/Renderer/inline.php';
    include_once 'Text/Diff/Renderer/unified.php';

    $this->view->file = $targetFile;
    $this->view->textDiff = $textDiff = new Text_Diff('auto', array(file($sourceFile, FILE_IGNORE_NEW_LINES), file($targetFile, FILE_IGNORE_NEW_LINES)));
    return;
    var_dump('-------------------------------------');
    var_dump($textDiff->getDiff());
    var_dump('-------------------------------------');

    $textDiffRenderer = new Text_Diff_Renderer_context();
    var_dump($textDiffRenderer->render($textDiff));
    var_dump('-------------------------------------');

    $textDiffRenderer = new Text_Diff_Renderer_inline();
    var_dump($textDiffRenderer->render($textDiff));
    var_dump('-------------------------------------');

    $textDiffRenderer = new Text_Diff_Renderer_unified();
    var_dump($textDiffRenderer->render($textDiff));
    var_dump('-------------------------------------');
  }




  // Utility

  protected function _getCacheIndex()
  {
    $data = $this->_cache->load('compare_index');
    if( !$data || !is_array($data) ) {
      $this->_buildCache();
      $data = $this->_cache->load('compare_index');
      if( !$data || !is_array($data) ) {
        throw new Engine_Exception('Unable to build cache data or index.');
      }
    }
    return $data;
  }

  protected function _getCachePackage($package)
  {
    $index = $this->_getCacheIndex();
    if( !$index || !is_array($index) ) {
      $this->_buildCache();
      $index = $this->_getCacheIndex();
      if( !$index || !is_array($index) ) {
        throw new Engine_Exception('Unable to build cache data or index.');
      }
    }

    if( !in_array($package, $index) ) {
      return false;
    } else {
      $data = $this->_cache->load('compare_package_' . $this->_cleanCacheId($package));
      if( !$data || !is_object($data) ) {
        throw new Engine_Exception('Unable to retreive cache data.');
      }
      return $data;
    }
  }

  protected function _buildCache()
  {
    $cache = $this->_cache;
    $packages = $this->_packageManager->listInstalledPackages();
    $indexData = array();

    foreach( $packages as $package ) {

      // Build diff
      $leftFiles = array();
      $rightFiles = array();
      
      $key = $package->getKey();

      foreach( $package->getFileStructure(true) as $i => $tmpRightFile ) {
        if( $tmpRightFile['dir'] ) continue;
        $leftFiles[] = APPLICATION_PATH . '/' . $i;

        $tmpRightFile['exists'] = true;
        $tmpRightFile['hash'] = $tmpRightFile['sha1'];
        $rightFiles[] = $tmpRightFile;
      }

      $batch = new Engine_File_Diff_Batch($leftFiles, $rightFiles);
      $batch->execute();

      // Save cache
      $cache->save($batch, 'compare_package_' . $this->_cleanCacheId($key));

      // Build index data
      $indexData[] = $key;

      // Unset unneeded data
      unset($leftFiles);
      unset($rightFiles);
      unset($i);
      unset($tmpRightFile);
      unset($batch);
      unset($key);
    }

    // Save index data
    $cache->save($indexData, 'compare_index');
  }

  protected function _flushCache()
  {
    $this->_cache->clean();
    return $this;
  }

  protected function _cleanCacheId($string)
  {
    return preg_replace('/[^a-zA-Z0-9_]/', '_', $string);
  }
}