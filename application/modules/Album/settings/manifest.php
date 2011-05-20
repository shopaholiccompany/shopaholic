<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7395 2010-09-15 23:37:11Z john $
 * @author     Jung
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'album',
    'version' => '4.0.4',
    'revision' => '$Revision: 7395 $',
    'path' => 'application/modules/Album',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Albums',
      'description' => 'Albums',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'controllers/AlbumController.php' => 'Tweak for message photos',
          'externals/styles/main.css' => 'Improved RTL support',
          'Model/DbTable/Albums.php' => 'Tweak for message photos',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/_composePhoto.tpl' => 'Added missing translation',
          'views/scripts/_formButtonCancel.tpl' => 'Removing deprecated code',
          'views/scripts/photo/view.tpl' => 'Added missing translation',
        ),
        '4.0.3' => array(
          'controllers/IndexController.php' => 'Quick navigation uses menu system',
          'Plugin/Menus.php' => 'Better auth handling for menus',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.2-4.0.3.sql' => 'Added',
          'settings/my.sql' => 'Added album menus to the menu editor; quick navigation uses menu system; incremented version',
          'views/scripts/admin-manage/index.tpl' => 'Correct locale date formatting; added missing translation',
          'views/scripts/admin-settings/categories.tpl' => 'Added missing translation',
          'views/scripts/admin-settings/index.tpl' => 'Added missing translation',
          'views/scripts/index/browse.tpl' => 'Quick navigation uses menu system',
          'views/scripts/index/manage.tpl' => 'Quick navigation uses menu system',
          '/application/languages/en/album.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'Api/Core.php' => 'Categories ordered by name',
          'controllers/AdminLevelController.php' => 'Various level settings fixes and enhancements',
          'controllers/AlbumController.php' => 'Fixed activity privacy rebinding problem',
          'controllers/IndexController.php' => 'Fixed problem setting the category in browse',
          'Form/Admin/Level.php' => 'Moved',
          'Form/Admin/Settings/Level.php' => 'Various level settings fixes and enhancements',
          'Model/Photo.php' => 'Added missing parent::_postDelete(), could have caused issues with orphaned rows',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
          '/application/languages/en/album.csv' => 'Fix activity feed translations',
        ),
        '4.0.1' => array(
          'Api/Core.php' => 'Cleanup of temporary files; adjustment for trial',
          'controllers/AlbumController.php' => 'Fixed missing level permissions check',
          'controllers/PhotoController.php' => 'Fixed missing level permissions check; added view count support',
          'Form/Admin/Level.php' => 'Source code formatting',
          'Plugin/Core.php' => 'Query optimization',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.0-4.0.1.sql' => 'Added',
          'settings/my.sql' => 'Added missing view_count and comment_count columns to the engine4_album_photos table',
        ),
      ),
    ),
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '4.0.4',
      ),
    ),
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       'enable',
       'disable',
     ),
    'callback' => array(
      'path' => 'application/modules/Album/settings/install.php',
      'class' => 'Album_Installer',
    ),
    'directories' => array(
      'application/modules/Album',
    ),
    'files' => array(
      'application/languages/en/album.csv',
    ),
  ),
  // Compose -------------------------------------------------------------------
  'compose' => array(
    array('_composePhoto.tpl', 'album'),
  ),
  'composer' => array(
    'photo' => array(
      'script' => array('_composePhoto.tpl', 'album'),
      'plugin' => 'Album_Plugin_Composer',
    ),
  ),
  // Content -------------------------------------------------------------------
  'content'=> array(
    'album_profile_albums' => array(
      'type' => 'action',
      'title' => 'Album Profile Tab',
      'route' => array(
        'module' => 'album',
        'controller' => 'widget',
        'action' => 'profile-albums',
      ),
    )
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'album',
    'album_photo',
    'photo'
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Album_Plugin_Core'
    ),
    array(
      'event' => 'onUserProfilePhotoUpload',
      'resource' => 'Album_Plugin_Core'
    ),
    array(
      'event' => 'onUserDeleteAfter',
      'resource' => 'Album_Plugin_Core'
    )
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
     'album_extended' => array(
      'route' => 'albums/:controller/:action/*',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'index',
        'action' => 'index'
      ),
    ),
    'album_specific' => array(
      'route' => 'albums/:action/:album_id/*',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'album',
        'action' => 'view'
      ),
      'reqs' => array(
        'action' => '(compose-upload|delete|edit|editphotos|upload|view)',
      ),
    ),
    'album_general' => array(
      'route' => 'albums/:action/*',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'index',
        'action' => 'browse'
      ),
      'reqs' => array(
        'action' => '(browse|create|list|manage|upload|upload-photo)',
      ),
    ),

    'album_photo_specific' => array(
      'route' => 'albums/photos/:action/:album_id/:photo_id/*',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'photo',
        'action' => 'view'
      ),
      'reqs' => array(
        'action' => '(view)',
      ),
    ),
    // Admin
    /*
    'album_admin_manage_level' => array(
      'route' => 'admin/album/admin-level/:level_id',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'admin-level',
        'action' => 'index',
        'level_id' => 1
      )
    ),
    'album_admin' => array(
      'route' => 'admin/settings/albums',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'admin',
        'action' => 'index'
      )
    ),
    'album_admin_view' => array(
      'route' => 'admin/view/albums/:page',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'admin',
        'action' => 'view',
        'page' => 1
      )
    )
    'album_admin' => array(
      'route' => 'admin/album/:action',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'admin',
        'action' => 'index'
      ),
      'reqs' => array(
        //'action' => '[^(level)]'
      )
    ),
*/
    'album_admin_manage_level' => array(
      'route' => 'admin/album/level/:level_id',
      'defaults' => array(
        'module' => 'album',
        'controller' => 'admin-level',
        'action' => 'index',
        'level_id' => 1
      )
    ),
  ),
);
