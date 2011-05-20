<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7381 2010-09-14 21:00:44Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'group',
    'version' => '4.0.4',
    'revision' => '$Revision: 7381 $',
    'path' => 'application/modules/Group',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Groups',
      'description' => 'Groups',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'Api/Core.php' => 'Added category ordering',
          'controllers/TopicController.php' => 'Improving auth checking',
          'externals/styles/main.css' => 'Improved RTL support',
          'Form/Post/Delete.php' => 'Style tweak',
          'Form/Post/Edit.php' => 'Style tweak',
          'Form/Topic/Delete.php' => 'Style tweak',
          'Form/Topic/Rename.php' => 'Style tweak',
          'Plugin/Core.php' => 'Fixed issues with privacy in the feed when content is hidden from the public by admin settings',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/install.php' => 'Group page should not be considered a custom page',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added; fixed typo in permissions table leftover from several versions ago; fixed custom page setting',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/photo/view.tpl' => 'Added missing translation',
          'widgets/profile-discussions/*' => 'Added correct auth checking for showing link',
          'widgets/profile-events/index.tpl' => 'Style tweak',
          '/application/languages/en/group.csv' => 'Added phrases',
        ),
        '4.0.3' => array(
          'controllers/GroupController.php' => 'Fixed bug in privacy for officers',
          'Model/Photo.php' => 'Fixed errors when deleting a photo with a missing file',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/admin-manage/index.tpl' => 'Added correct locale date format',
          'widgets/profile-groups/index.tpl' => 'Added missing translation',
          'widgets/profile-info/index.tpl' => 'Added missing translation',
          '/application/languages/en/group.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'Api/Core.php' => 'Categories ordered by name',
          'controllers/AdminSettingsController.php' => 'Various level settings fixes and enhancements',
          'controllers/EventController.php' => 'Remove, no longer used',
          'controllers/GroupController.php' => 'Various level settings fixes and enhancements',
          'controllers/IndexController.php' => 'Various level settings fixes and enhancements',
          'externals/styles/main.css' => 'Styled the group events profile tab',
          'Form/Create.php' => 'Various level settings fixes and enhancements',
          'Form/Edit.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level.php' => 'Moved',
          'Form/Admin/Settings/Level.php' => 'Various level settings fixes and enhancements',
          'Plugin/Core.php' => 'Added activity stream index type',
          'Plugin/Menus.php' => 'Fixed problem that would prevent the invite link from showing for non-officers when allowed',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
          'widgets/profile-events/Controller.php' => 'Tab now hides properly when the event plugin is not enabled',
          'widgets/profile-events/index.tpl' => 'Cleanup',
          'widgets/profile-groups/Controller.php' => 'Tab now shows properly when the event plugin is not enabled',
          'widgets/profile-members/index.tpl' => 'Search input text and owner/officer now translate',
        ),
        '4.0.1' => array(
          'Api/Core.php' => 'Better cleanup of temporary files',
          'controllers/AdminSettingsController.php' => 'Fixed problem in level select',
          'controllers/GroupController.php' => 'Added level permission for styles',
          'controllers/IndexController.php' => 'Added menu for group manage page',
          'controllers/PhotoController.php' => 'Added view count support',
          'controllers/TopicController.php' => 'Added view count support',
          'Form/Admin/Level.php' => 'Added level permission for styles',
          'Model/Group.php' => 'Better cleanup of temporary files',
          'Plugin/Core.php' => 'Query optimization',
          'Plugin/Menus.php' => 'Added level permission for styles',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.0-4.0.1.sql' => 'Added',
          'settings/my.sql' => 'Added view_count and comment_count columns to engine4_group_photos table; added view_count column to engine4_group_topics table; added default permissions for style permission',
          'widgets/profile-info/index.tpl' => 'Fixed possible bug when owner title is not set',
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
      'path' => 'application/modules/Group/settings/install.php',
      'class' => 'Group_Installer',
    ),
    'directories' => array(
      'application/modules/Group',
    ),
    'files' => array(
      'application/languages/en/group.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Group_Plugin_Core'
    ),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Group_Plugin_Core',
    ),
    array(
      'event' => 'getActivity',
      'resource' => 'Group_Plugin_Core',
    ),
    array(
      'event' => 'addActivity',
      'resource' => 'Group_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'group',
    'group_album',
    'group_list',
    'group_list_item',
    'group_photo',
    'group_post',
    'group_topic',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'group_extended' => array(
      'route' => 'groups/:controller/:action/*',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'controller' => '\D+',
        'action' => '\D+',
      )
    ),
    'group_general' => array(
      'route' => 'groups/:action/*',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'index',
        'action' => 'browse',
      ),
      'reqs' => array(
        'action' => '(browse|create|list|manage)',
      )
    ),
    'group_specific' => array(
      'route' => 'groups/:action/:group_id/*',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'group',
        'action' => 'index',
      ),
      'reqs' => array(
        'action' => '(edit|delete|join|leave|cancel|accept|invite|style)',
        'group_id' => '\d+',
      )
    ),
    'group_profile' => array(
      'route' => 'group/:id/*',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'profile',
        'action' => 'index',
      ),
      'reqs' => array(
        'id' => '\d+',
      )
    ),
    'group_browse' => array(
      'route' => 'group/browse',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'index',
        'action' => 'browse'
      )
    ),
    'group_admin_manage_level' => array(
      'route' => 'admin/group/level/:level_id',
      'defaults' => array(
        'module' => 'group',
        'controller' => 'admin-settings',
        'action' => 'level',
        'level_id' => 1
      )
    )    
  )
) ?>