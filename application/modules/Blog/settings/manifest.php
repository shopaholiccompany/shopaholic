<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7371 2010-09-14 03:33:35Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'blog',
    'version' => '4.0.4',
    'revision' => '$Revision: 7371 $',
    'path' => 'application/modules/Blog',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Blogs',
      'description' => 'Blogs',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'controllers/IndexController.php' => 'Added localization to the archive list',
          'externals/styles/main.css' => 'Improved RTL support',
          'Form/Style.php' => 'Style tweak',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Fixed incorrect admin permissions for commenting',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/index/index.tpl' => 'Fixes error in blog when filtered by category',
          'views/scripts/index/view.tpl' => 'Fixes style problems in navigation',
          'widgets/list-recent-blogs/index.tpl' => 'Styled',
          'widgets/list-popular-blogs/index.tpl' => 'Styled',
          '/application/languages/en/blog.csv' => 'Added phrases',
        ),
        '4.0.3' => array(
          'controllers/IndexController.php' => 'Menus now use menu system; fixes for auth to allow moderation',
          'Plugin/Menus.php' => 'Menus now use menu system',
          'settings/content.php' => 'Fixed typo in popular blog name which prevented display',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.2-4.0.3.sql' => 'Added',
          'settings/my.sql' => 'Menus now use menu system; incremented version',
          'views/scripts/admin-level/index.tpl' => 'Added missing translation',
          'views/scripts/admin-manage/index.tpl' => 'Fixed view link to use slug; added missing translation; added correct locale date format',
          'views/scripts/admin-settings/categories.tpl' => 'Added missing translation',
          'views/scripts/admin-settings/index.tpl' => 'Added missing translation',
          'views/scripts/index/index.tpl' => 'Menus now use menu system',
          'views/scripts/index/list.tpl' => 'Menus now use menu system',
          'views/scripts/index/manage.tpl' => 'Menus now use menu system; added missing translation',
          'views/scripts/index/view/tpl' => 'Menus now use menu system',
          '/application/languages/en/blog.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'Api/Core.php' => 'Categories ordered by name',
          'controllers/AdminLevelController.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level.php' => 'Moved',
          'Form/Admin/Settings/Level.php' => 'Various level settings fixes and enhancements',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
        ),
        '4.0.1' => array(
          'Api/Core.php' => 'Adjustment for trial',
          'controllers/AdminLevelController.php' => 'Fix for public level',
          'controllers/AdminSettingsController.php' => 'Source code formatting',
          'controllers/IndexController.php' => 'Fixed public permissions',
          'Form/Admin/Settings/Global.php' => 'Moved public permissions to public level settings',
          'Form/Admin/Settings/Level.php' => 'Moved public permissions to public level settings',
          'Plugin/Core.php' => 'Query optimization',
          'settings/manifest.php' => 'Incremented version',
          'views/scripts/admin-level/index.tpl' => 'Level change fix',
          'widgets/list-popular-blogs/Controller.php' => 'Fixed typo',
          'widgets/list-recent-blogs/Controller.php' => 'Fixed typo',
          'widgets/list-recent-blogs/index.tpl' => 'Styled',
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
      'path' => 'application/modules/Blog/settings/install.php',
      'class' => 'Blog_Installer',
    ),
    'directories' => array(
      'application/modules/Blog',
    ),
    'files' => array(
      'application/languages/en/blog.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Blog_Plugin_Core'
    ),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Blog_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'blog'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public
    'blog_specific' => array(
      'route' => 'blogs/:action/:blog_id/*',
      'defaults' => array(
        'module' => 'blog',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'blog_id' => '\d+',
        'action' => '(delete|edit)',
      ),
    ),
    'blog_general' => array(
      'route' => 'blogs/:action/*',
      'defaults' => array(
        'module' => 'blog',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'action' => '(index|create|manage|style|tag)',
      ),
    ),
    'blog_view' => array(
      'route' => 'blogs/:user_id/*',
      'defaults' => array(
        'module' => 'blog',
        'controller' => 'index',
        'action' => 'list',
      ),
      'reqs' => array(
        'user_id' => '\d+',
      ),
    ),
    'blog_entry_view' => array(
      'route' => 'blogs/:user_id/:blog_id/:slug',
      'defaults' => array(
        'module' => 'blog',
        'controller' => 'index',
        'action' => 'view',
        'slug' => '',
      ),
      'reqs' => array(
        'user_id' => '\d+',
        'blog_id' => '\d+'
      ),
    ),
  ),
);
