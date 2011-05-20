<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7376 2010-09-14 05:58:07Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'activity',
    'version' => '4.0.4',
    'revision' => '$Revision: 7376 $',
    'path' => 'application/modules/Activity',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Activity',
      'description' => 'Activity',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'controllers/AdminSettingsController.php' => 'Fixed problem with disabling or enabling activity feed item types',
          'controllers/NotificationsController.php' => 'Moved pulldown update here',
          'externals/styles/main.css' => 'Improved RTL support',
          'Form/Admin/Settings/General.php' => 'Fixed problem with disabling or enabling activity feed item types',
          'Model/Helper/Body.php' => 'Added container around post body for future improved RTL support',
          'Plugin/Core.php' => 'Fixed issues with privacy in the feed when content is hidden from the public by admin settings',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/_formActivityButton.tpl' => 'Removed deprecated code',
          'views/scripts/notifications/pulldown.tpl' => 'Moved pulldown update here',
          'views/scripts/widget/*' => 'Removing deprecated code',
          'widgets/feed/index.tpl' => 'Added missing translation; fixed smoothbox binding on view more; fixed incorrect inclusion of javascript files',
          '/application/languages/en/activity.csv' => 'Added phrases',
        ),
        '4.0.3' => array(
          'Model/DbTable/NotificationTypes.php' => 'Fixed bug with missing notification emails',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
          '/application/languages/en/activity.csv' => 'Added phrases',
        ),
        '4.0.2p1' => array(
          'Model/Helper/Item.php' => 'Fixed pluralization of updates text',
          'settings/manifest.php' => 'Incremented version',
        ),
        '4.0.2' => array(
          'controllers/IndexController.php' => 'Added missing authorization checks',
          'Model/DbTable/Actions.php' => 'Fixed bad IN clauses in query',
          'Model/Helper/Item.php' => 'Adds translation of item text in update notifications',
          'Plugin/Core.php' => 'Fixed several privacy issues',
          'settings/manifest.php' => 'Incremented version',
          'views/scripts/_activityText.tpl' => 'Added missing authorization checks',
          '/application/languages/en/activity.csv' => 'Adds translation of item text in update notifications',
        ),
        '4.0.1' => array(
          'Model/DbTable/Notifications.php' => 'Fixes problem with notifications from disabled modules',
          'Plugin/Core.php' => 'Fixes problem with properly detecting the page subject and handles items without parents properly',
          'settings/manifest.php' => 'Incremented version',
          'views/scripts/notifications/index.tpl' => 'Fixes problem with notifications from disabled modules',
          'widgets/list-requests/index.tpl' => 'Fixes problem with notifications from disabled modules',
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
       //'enable',
       //'disable',
     ),
    'callback' => array(
      'class' => 'Engine_Package_Installer_Module',
      'priority' => 4000,
    ),
    'directories' => array(
      'application/modules/Activity',
    ),
    'files' => array(
      'application/languages/en/activity.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'getActivity',
      'resource' => 'Activity_Plugin_Core',
    ),
    array(
      'event' => 'addActivity',
      'resource' => 'Activity_Plugin_Core',
    ),
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Activity_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'activity_action',
    'activity_notification',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'activity_admin_settings_general' => array(
      'route' => 'admin/settings/activity/',
      'defaults' => array(
        'module' => 'activity',
        'controller' => 'admin-settings',
        'action' => 'general'
      )
    ),
    'recent_activity' => array(
      'route' => 'activity/notifications/',
      'defaults' => array(
        'module' => 'activity',
        'controller' => 'notifications',
      )
    )
  )
) ?>