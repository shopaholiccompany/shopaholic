<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Abstract.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
abstract class Storage_Service_Abstract implements Storage_Service_Interface
{
  protected $_scheme;

  protected $_schemeClass = 'Storage_Service_Scheme_Extended';
  //protected $_schemeClass = 'Storage_Service_Scheme_Standard';



  // Scheme
  
  public function getScheme()
  {
    if( null === $this->_scheme )
    {
      $class = $this->_schemeClass;
      $this->_scheme = new $class();
    }

    return $this->_scheme;
  }

  public function setScheme(Storage_Service_Scheme_Interface $scheme)
  {
    $this->_scheme = $scheme;
    return $this;
  }


  // Transaction

  public function inTransaction()
  {
    return Engine_Api::_()->storage()->inTransaction();
  }

  public function rollBack()
  {
    // @todo
  }

  public function commit()
  {
    // @todo
  }



  
  /* Utility */
  
  public function getBaseUrl()
  {
    return $this->_removeScriptName(Zend_Controller_Front::getInstance()->getBaseUrl());
  }

  public function fileInfo($file)
  {
    // $file is an instance of Zend_Form_Element_File
    if( $file instanceof Zend_Form_Element_File )
    {
      $info = $file->getFileInfo();
      $info = current($info);
    }

    // $file is a key of $_FILES
    else if( is_array($file) )
    {
      $info = $file;
    }

    // $file is a string
    else if( is_string($file) )
    {
      $info = array(
        'tmp_name' => $file,
        'name' => basename($file),
        'type' => 'unknown/unknown', // @todo
        'size' => filesize($file)
      );

      // Try to get image info
      if( function_exists('getimagesize') && ($imageinfo = getimagesize($file)) )
      {
        $info['type'] = $imageinfo['mime'];
      }
    }

    // $file is an unknown type
    else
    {
      throw new Storage_Service_Exception('Unknown file type specified');
    }

    // Check to make sure file exists and not security problem
    self::_checkFile($info['tmp_name']);

    // Do some other stuff
    $mime_parts = explode('/', $info['type'], 2);
    $info['mime_major'] = $mime_parts[0];
    $info['mime_minor'] = $mime_parts[1];
    $info['hash'] = md5_file($info['tmp_name']);
    $info['extension'] = ltrim(strrchr($info['name'], '.'), '.');
    unset($info['type']);
    
    return $info;
  }

  protected function _removeScriptName($url)
  {
    if (!isset($_SERVER['SCRIPT_NAME'])) {
      // We can't do much now can we? (Well, we could parse out by ".")
      return $url;
    }

    if (($pos = strripos($url, basename($_SERVER['SCRIPT_NAME']))) !== false) {
      $url = substr($url, 0, $pos);
    }

    return $url;
  }

  protected function _checkFile($file, $mode = 06)
  {
    // @todo This is fubared, fix up later
    //if( preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $file) )
    //if( preg_match('/[^a-z0-9 \\/\\\\_.:-]/i', $file) )
    //{
      //throw new Storage_Service_Exception(sprintf('Security check: Illegal character in filename: %s', $file));
    //}

    if( $mode && !file_exists($file) )
    {
      throw new Storage_Service_Exception('File does not exist: '.$file);
    }
    
    if( ($mode & 04) && (!is_readable($file)) )
    {
      throw new Storage_Service_Exception('File not readable: '.$file);
    }

    if( ($mode & 02) && (!is_writable($file)) )
    {
      throw new Storage_Service_Exception('File not writeable: '.$file);
    }

    if( ($mode & 01) && (!is_executable($file)) )
    {
      throw new Storage_Service_Exception('File not executable: '.$file);
    }
  }
  
  protected function _mkdir($path, $mode = 0777)
  {
    if( is_dir($path) )
    {
      @chmod($path, $mode);
      return;
    }

    if( !@mkdir($path, $mode, true) )
    {
      throw new Storage_Service_Exception("Could not create folder: ".$path);
    }
  }
  
  protected function _move($from, $to)
  {
    if( !@chmod($from, 0777) || !@rename($from, $to) )
    {
      throw new Storage_Service_Exception('Unable to move file ('.$from.') -> ('.$to.')');
    }
  }

  protected function _delete($file)
  {
    if( !@chmod($file, 0777) || !@unlink($file) )
    {
      throw new Storage_Service_Exception('Unable to delete file: '.$file);
    }
  }

  protected function _copy($from, $to)
  {
    if( !@copy($from, $to) )
    {
      throw new Storage_Service_Exception('Unable to copy file ('.$from.') -> ('.$to.')');
    }
  }

  protected function _write($file, $data)
  {
    if( !@file_put_contents($file, $data) )
    {
      throw new Storage_Service_Exception('Unable to write to file: '.$file);
    }
  }

  protected function _read($file)
  {
    if( !@file_get_contents($file) )
    {
      throw new Storage_Service_Exception('Unable to read file: '.$file);
    }
  }

}