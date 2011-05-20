<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Authorization
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7305 2010-09-07 06:49:55Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'authorization',
    'version' => '4.0.4',
    'revision' => '$Revision: 7305 $',
    'path' => 'application/modules/Authorization',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Authorization',
      'description' => 'Authorization',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added to purge levels from search index',
          'settings/my.sql' => 'Incremented version',
        ),
        '4.0.3' => array(
          'Model/Level.php' => 'Code optimizations; fixed nested transaction error with pdo_mysql',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
        ),
        '4.0.2' => array(
          'controllers/AdminLevelController.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level/Abstract.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level/Create.php' => 'Various level settings fixes and enhancements; added level type',
          'Form/Admin/Level/Edit.php' => 'Various level settings fixes and enhancements',
          'Model/DbTable/Allow.php' => 'Added auth type for members invited to a group or event',
          'Model/DbTable/Permissions.php' => 'Fixes issue when an empty array is passed to getAllowed()',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
          'views/scripts/admin-level/index.tpl' => 'Added column for level type; added missing translation',
        ),
        '4.0.1' => array(
          'Form/Admin/Level/Edit.php' => 'Storage quotas are now level-based',
          'settings/manifest.php' => 'Incremented version',
        ),
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
      'path' => 'application/modules/Authorization/settings/install.php',
      'class' => 'Authorization_Install',
      'priority' => 5000,
    ),
    'directories' => array(
      'application/modules/Authorization',
    ),
    'files' => array(
      'application/languages/en/authorization.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Authorization_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'authorization_level'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'authorization_admin_levels' => array(
      'route' => '/admin/levels/:action/*',
      'defaults' => array(
        'module' => 'authorization',
        'controller' => 'admin-level',
        'action' => 'index',
        //'id' => 0
      )
    )
  )
) ?>