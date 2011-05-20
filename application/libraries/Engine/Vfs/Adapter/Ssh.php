<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Ssh.php 7244 2010-09-01 01:49:53Z john $
 * @author     John Boehr <j@webligo.com>
 */

//require_once 'Engine/Vfs/Adapter/Abstract.php';
//require_once 'Engine/Vfs/Adapter/Exception.php';
//require_once 'Engine/Vfs/Directory/Standard.php';
//require_once 'Engine/Vfs/Info/Ssh.php';
//require_once 'Engine/Vfs/Object/Ssh.php';

/**
 * @category   Engine
 * @package    Engine_Vfs
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John Boehr <j@webligo.com>
 */
class Engine_Vfs_Adapter_Ssh extends Engine_Vfs_Adapter_Abstract
{
  protected $_host;

  protected $_port;
  
  protected $_timeout = 90;

  protected $_username;

  protected $_password;

  protected $_publicKey;

  protected $_privateKey;

  protected $_hostKey;

  protected $_sftpResource;

  protected $_remoteUid;

  protected $_remoteGid;

  protected $_lastError;



  // General

  public function __construct(array $config = null)
  {
    if( !extension_loaded('ssh2') ) {
      throw new Engine_Vfs_Adapter_Exception('The ssh2 extension is not installed, unable to initialize SSH-VFS');
    }
    parent::__construct($config);
  }

  public function __destruct()
  {
    $this->disconnect();
  }

  public function __sleep()
  {
    return array_merge(parent::__sleep(), array('_host', '_port', '_timeout', '_username', '_password', '_privateKey', '_publicKey', '_hostKey', '_remoteUid', '_remoteGid'));
  }
  
  public function getResource()
  {
    if( null === $this->_resource ) {
      $this->connect();
      $this->login();
    }

    return $this->_resource;
  }

  public function getSftpResource()
  {
    if( null === $this->_sftpResource ) {
      $this->_sftpResource = @ssh2_sftp($this->getResource());
      if( null === $this->_sftpResource ) {
        throw new Engine_Vfs_Adapter_Exception('Unable to get sftp resource');
      }
    }
    return $this->_sftpResource;
  }

  public function setHost($host)
  {
    if( strpos($host, ':') !== false ) {
      $urlInfo = parse_url($host);
      if( !empty($urlInfo['host']) ) {
        $host = $urlInfo['host'];
      }
      if( !empty($urlInfo['port']) ) {
        $this->setPort($urlInfo['port']);
      }
      if( !empty($urlInfo['user']) ) {
        $this->setUsername($urlInfo['user']);
      }
      if( !empty($urlInfo['pass']) ) {
        $this->setPassword($urlInfo['pass']);
      }
    }
    $this->_host = $host;
    return $this;
  }

  public function getHost()
  {
    return $this->_host;
  }

  public function setPort($port)
  {
    $this->_port = $port;
    return $this;
  }

  public function getPort()
  {
    return $this->_port;
  }

  public function setTimeout($timeout)
  {
    $this->_timeout = (int) $timeout;
    return $this;
  }

  public function getTimeout()
  {
    return $this->_timeout;
  }

  public function setUsername($username)
  {
    $this->_username = $username;
    return $this;
  }

  public function getUsername()
  {
    return $this->_username;
  }

  public function setPassword($password)
  {
    $this->_password = $password;
    return $this;
  }

  public function getPassword()
  {
    return $this->_password;
  }

  public function setPublicKey($publicKey)
  {
    $this->_publicKey = $publicKey;
    return $this;
  }

  public function getPublicKey()
  {
    return $this->_publicKey;
  }

  public function setPrivateKey($privateKey)
  {
    $this->_privateKey = $privateKey;
    return $this;
  }

  public function getPrivateKey()
  {
    return $this->_privateKey;
  }

  public function setHostKey($hostKey)
  {
    $this->_hostKey = $hostKey;
    return $this;
  }

  public function getHostKey()
  {
    if( null === $this->_hostKey ) {
      $this->_hostKey = 'ssh-rsa';
    }
    return $this->_hostKey;
  }



  // Connection

  public function connect()
  {
    $publicKey = $this->getPublicKey();
    $privateKey = $this->getPrivateKey();
    $hostKey = $this->getHostKey();

    // Connect with keys
    if( ($publicKey && $privateKey && $hostKey) ) {
      $resource = @ssh2_connect($this->getHost(), $this->getPort(), array(
        'hostkey' => $this->getHostKey(),
      ), array(
        'disconnect' => array($this, 'onDisconnect'),
      ));
    }

    // Connect without keys
    else {
      $resource = @ssh2_connect($this->getHost(), $this->getPort(), array(
        
      ), array(
        'disconnect' => array($this, 'onDisconnect'),
      ));
    }
    
    if( !$resource ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to connect to "%s"', $this->getHost()));
    }

    $this->_resource = $resource;

    return $this;
  }

  public function disconnect()
  {
    // @todo do something with the output
    $return = $this->command('exit')
      // Meh
      || true;

    //$return = fclose($this->getResource());
    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception('Disconnect failed.');
    }
    $this->_resource = null;

    return $this;
  }

  public function login()
  {
    $username = $this->getUsername();
    $password = $this->getPassword();
    $publicKey = $this->getPublicKey();
    $privateKey = $this->getPrivateKey();
    $hostKey = $this->getHostKey();

    // Auth using keys
    if( $publicKey && $privateKey && $hostKey ) {
      $return = @ssh2_auth_pubkey_file($this->getResource(), $username, $publicKey, $privateKey, $password);
    }

    // Auth using username/password only
    else if( $username && $password ) {
      $return = @ssh2_auth_password($this->getResource(), $username, $password);
    }

    // Auth using none
    else {
      $return = @ssh2_auth_none($this->getResource(), $username);
    }

    // Failure
    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception('Login failed.');
    }

    return $this;
  }

  public function command($command)
  {
    $stream = @ssh2_exec($this->getResource(), $command);
    if( !$stream ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to execute command "%s"', $command));
    }
    $errorStream = @ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

    stream_set_blocking($stream, true);
    stream_set_timeout($stream, $this->getTimeout());
    if( $errorStream ) {
      stream_set_blocking($errorStream, true);
      stream_set_timeout($errorStream, $this->getTimeout());
    }

    $data = stream_get_contents($stream);
    $error = '';
    if( $errorStream ) {
      $error = stream_get_contents($errorStream);
    }
    
    fclose($stream);
    if( $errorStream ) {
      fclose($errorStream);
    }
    
    $this->_lastError = $error;

    return trim($data);
    /*
    if( is_bool($data) ) {
      return $data;
    } else if( '' == ($data = trim($data)) ) {
      return false;
    } else {
      return $data;
    }
     * 
     */
  }



  // Events

  public function onDisconnect()
  {
    // @todo more fun stuff
    throw new Engine_Vfs_Adapter_Exception('Disconnected from server');
  }



  // Informational

  public function exists($path)
  {
    $path = $this->path($path);

    return file_exists('ssh2.sftp://' . $this->getSftpResource() . $path);
  }

  public function isDirectory($path)
  {
    $path = $this->path($path);

    return is_dir('ssh2.sftp://' . $this->getSftpResource() . $path);
  }

  public function isFile($path)
  {
    $path = $this->path($path);

    return is_file('ssh2.sftp://' . $this->getSftpResource() . $path);
  }

  public function getSystemType()
  {
    if( null === $this->_systemType ) {
      if( substr($this->printDirectory(), 1, 2) == ':\\' ) {
        $this->_systemType = self::SYS_WIN;
      } else {
        $systype = $this->command('uname');
        if( !$systype ) {
          // Shall we throw or just return linux (since it's not windows at least)
          throw new Engine_Vfs_Adapter_Exception(sprintf('Unknown remote system type'));
          //return self::SYS_LIN;
        }
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
      foreach( scandir('ssh2.sftp://' . $this->getSftpResource() . $path) as $child ) {
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

    if( null === $info ) {
      $info = @ssh2_sftp_stat($this->getSftpResource(), $path);
      if( is_array($info) ) {
        $info['is_dir'] = $this->isDirectory($path);
        $info['is_file'] = $this->isFile($path);
        $info['rights'] = substr(sprintf('%o', @fileperms('ssh2.sftp://' . $this->getSftpResource() . $path)), -4);;
      } else {
        $info = null;
      }
    }

    $class = $this->getClass('Info');
    $instance = new $class($this, $path, $info);
    return $instance;
  }



  // General

  public function copy($sourcePath, $destPath)
  {
    $sourcePath = $this->path($sourcePath);
    $destPath = $this->path($destPath);

    $tmpFile = tempnam('/tmp', 'engine_vfs') . basename($sourcePath);

    try {
      $this->get($tmpFile, $sourcePath);
      $this->put($destPath, $tmpFile);

      // Set umask permission
      try {
        $this->mode($destPath, $this->getUmask(0666));
      } catch( Exception $e ) {
        // Silence
      }

      $return = true;
    } catch( Exception $e ) {
      $return = false;
    }
    
    @unlink($tmpFile);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to copy "%s" to "%s"', $sourcePath, $destPath));
    }

    return true;
  }

  public function get($local, $path)
  {
    $path = $this->path($path);

    // @todo implement nb?
    $return = @ssh2_scp_recv($this->getResource(), $path, $local);
    
    // Error
    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to get "%s" to "%s"', $path, $local));
    }

    return true;
  }

  public function getContents($path)
  {
    $path = $this->path($path);

    $contents = file_get_contents('ssh2.sftp://' . $this->getSftpResource() . $path);

    if( !$contents ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to get contents of "%s"', $path));
    }

    return $contents;
  }

  public function mode($path, $mode, $recursive = false)
  {
    $path = $this->path($path);

    if( !$this->exists($path) ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change mode on "%s"; it does not exist', $path));
    }

    $return = $this->command(sprintf('chmod ' . ($recursive ? '-R ' : ''). ' %o %s', $this->_processMode($mode), escapeshellarg($path)));
    if( '' != $return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change mode on "%s" - %s', $path, $return));
    }

    return true;
  }

  public function move($oldPath, $newPath)
  {
    $oldPath = $this->path($oldPath);
    $newPath = $this->path($newPath);

    $return = @ssh2_sftp_rename($this->getSftpResource(), $oldPath, $newPath);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to rename "%s" to "%s"', $oldPath, $newPath));
    }

    return true;
  }

  public function put($path, $local)
  {
    $path = $this->path($path);

    // @todo implement nb?
    $return = @ssh2_scp_send($this->getResource(), $local, $path, $this->getUmask(0666));
    
    // Error
    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to put "%s" to "%s"', $local, $path));
    }
    
    return true;
  }

  public function putContents($path, $data)
  {
    $path = $this->path($path);

    $return = file_put_contents('ssh2.sftp://' . $this->getSftpResource() . $path, $data);

    // Set umask permission
    try {
      $this->mode($path, $this->getUmask(0666));
    } catch( Exception $e ) {
      // Silence
    }

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to put contents to "%s"', $path));
    }

    return true;
  }

  public function unlink($path)
  {
    $path = $this->path($path);

    $return = @ssh2_sftp_unlink($this->getSftpResource(), $path);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to unlink "%s"', $path));
    }

    return true;
  }



  // Directories

  public function changeDirectory($directory)
  {
    $directory = $this->path($directory);
    
    if( !$this->isDirectory($directory) ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change directory to "%s", target is not a directory', $directory));
    }

    // Note: this is totally not working
    $ret = $this->command('cd ' . $directory);
    
    /*
    $curDir = $this->command('pwd');

    // Check against specified directory
    if( $directory != $curDir ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change directory to "%s", new directory did not match (%s)', $directory, $curDir));
    }


    if( !$return && false ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to change directory to "%s"', $directory));
    }
    */
    
    $this->_path = $directory;
    return true;
  }

  public function makeDirectory($directory, $recursive = false)
  {
    $directory = $this->path($directory);

    if( $this->isDirectory($directory) ) {
      return true;
    }

    $return = @ssh2_sftp_mkdir($this->getSftpResource(), $directory, $this->getUmask(0777), $recursive);
    
    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to make directory "%s"', $directory));
    }

    return $return;
  }

  public function printDirectory()
  {
    if( null === $this->_path ) {
      $pwd = $this->command('pwd');
      if( !$pwd ) {
        throw new Engine_Vfs_Adapter_Exception('Unable to get working directory');
      }
      $this->_path = $pwd;
    }
    return $this->_path;
  }

  public function removeDirectory($directory, $recursive = false)
  {
    $directory = $this->path($directory);

    if( $recursive ) {
      foreach( $this->directory($directory) as $child ) {
        if( $child->isDirectory() ) {
          $this->removeDirectory($child->getPath(), true);
        } else {
          $this->unlink($child->getPath());
        }
      }
    }

    // Normal
    $return = @ssh2_sftp_rmdir($this->getSftpResource(), $directory);

    if( !$return ) {
      throw new Engine_Vfs_Adapter_Exception(sprintf('Unable to remove directory "%s"', $directory));
    }

    return true;
  }



  // Utility

  public function getRemoteUid()
  {
    if( null === $this->_remoteUid ) {
      $ret = $this->command('echo $UID');
      if( $ret === '0' ) {
        $this->_remoteUid = 0;
      } else if( !$ret || $ret == '$UID' ) {
        $this->_remoteUid = false;
      } else {
        $this->_remoteUid = (int) $ret;
        // Cannot be zero
        if( $this->_remoteUid == 0 ) {
          $this->_remoteUid = false;
        }
      }
    }
    return $this->_remoteUid;
  }

  public function getRemoteGid()
  {
    if( null === $this->_remoteGid ) {
      $ret = $this->command('echo $GROUPS');
      if( $ret === '0' ) {
        $this->_remoteGid = 0;
      } else if( !$ret || $ret == '$GROUPS' ) {
        $this->_remoteGid = false;
      } else {
        $this->_remoteGid = (int) $ret;
        // Cannot be zero
        if( $this->_remoteGid == 0 ) {
          $this->_remoteGid = false;
        }
      }
    }
    return $this->_remoteGid;
  }
}