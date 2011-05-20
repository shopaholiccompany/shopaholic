<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7242 2010-09-01 01:21:10Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'storage',
    'version' => '4.0.3',
    'revision' => '$Revision: 7242 $',
    'path' => 'application/modules/Storage',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Storage',
      'description' => 'Storage',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.3' => array(
          'Api/Storage/php' => 'Fixed bug with quota handling',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
        ),
        '4.0.2' => array(
          'Api/Storage.php' => 'Typecasting storage quota values',
          'Service/Abstract.php' => 'Silencing notices in chmod',
          'settings/manifest.php' => 'Incremented version',
        ),
        '4.0.1' => array(
          'Api/Storage.php' => 'Storage quotas are now configured by member level',
          'settings/manifest.php' => 'Incremented version',
          'views/scripts/upload/upload.tpl' => 'Fixed IE JS bug',
        ),
      ),
    ),
    'tests' => array(
      array(
        'type' => 'MysqlEngine',
        'name' => 'MySQL MyISAM Storage Engine',
        'engine' => 'myisam',
      ),
    ),
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       //'enable',
       //'disable',
     ),
    'callback' => array(
      'class' => 'Engine_Package_Installer_Module',
      'priority' => 5000,
    ),
    'directories' => array(
      'application/modules/Storage',
    ),
    'files' => array(
      'application/languages/en/storage.csv',
    ),
  ),
  // Content -------------------------------------------------------------------
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Storage_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'storage_file',
  )
  // Routes --------------------------------------------------------------------
) ?>