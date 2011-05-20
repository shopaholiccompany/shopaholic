<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7380 2010-09-14 20:41:10Z john $
 * @author     Sami
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'event',
    'version' => '4.0.4',
    'revision' => '$Revision: 7380 $',
    'path' => 'application/modules/Event',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Events',
      'description' => 'Events',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'controllers/EventController.php' => 'Fixes timezone issues; fixed category ordering',
          'controllers/IndexController.php' => 'Fixes timezone issues; fixed category ordering',
          'controllers/MemberController.php' => 'Added RSVP select when joining an event',
          'externals/styles/main.css' => 'Improved RTL support',
          'Form/Rsvp.php' => 'Code cleanup',
          'Form/Member/*' => 'Fixes smoothbox problems on failed form validation',
          'Model/Event.php' => 'Added awaiting reply RSVP type',
          'Plugin/Core.php' => 'Fixed issues with privacy in the feed when content is hidden from the public by admin settings',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/install.php' => 'Group page should not be considered a custom page',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added; fixed custom page setting',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/widget' => 'Removed deprecated code',
          'views/scripts/index/browse.tpl' => 'Improved localization support; fixes hiding of filter form when options return no results',
          'views/scripts/index/create.tpl' => 'Removed deprecated code',
          'views/scripts/index/manage.tpl' => 'Improved localization support',
          'views/scripts/photo/view.tpl' => 'Added missing translation',
          'widgets/profile-info/index.tpl' => 'Improved localization support',
          'widgets/profile-members/index.tpl' => 'Added awaiting reply RSVP type',
          '/application/languages/en/event.csv' => 'Added missing phrases',
        ),
        '4.0.3' => array(
          'controllers/AdminSettingsController.php' => 'Tweak for public level',
          'controllers/IndexController.php' => 'Ordering categories by name',
          'externals/styles/main.css' => 'Styles for RSVP in member list',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/admin-manage/index.tpl' => 'Added correct locale date format',
          'widgets/profile-members/index.tpl' => 'Added display of member RSVP',
          '/application/languages/en/event.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'Api/Core.php' => 'Categories ordered by name',
          'controllers/AdminSettingsController.php' => 'Various level settings fixes and enhancements',
          'controllers/EventController.php' => 'Various level settings fixes and enhancements',
          'controllers/IndexController.php' => 'Various level settings fixes and enhancements',
          'Form/Create.php' => 'Various level settings fixes and enhancements',
          'Form/Edit.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level.php' => 'Moved',
          'Form/Admin/Settings/Level.php' => 'Various level settings fixes and enhancements',
          'Plugin/Core.php' => 'Added activity stream index type',
          'settings/content.php' => 'Added configuration options for Upcoming Events widget',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
          'widgets/home-upcoming/Controller.php' => 'Added configuration options for Upcoming Events widget',
          'widgets/profile-events/Controller.php' => 'Added parent check',
        ),
        '4.0.1' => array(
          'Api/Core.php' => 'Better cleanup of temporary files',
          'controllers/AdminSettingsController.php' => 'Fixed problem in level select',
          'controllers/EventController.php' => 'Added level permission for styles',
          'controllers/PhotoController.php' => 'Added view count support',
          'controllers/TopicController.php' => 'Added view count support',
          'Form/Admin/Level.php' => 'Added level permission for styles',
          'Model/Event.php' => 'Better cleanup of temporary files',
          'Plugin/Core.php' => 'Query optimization; fixed typo that would cause problem on user deletion',
          'Plugin/Menus.php' => 'Added level permission for styles',
          'settings/manifest.php' => 'Incremented version; fixed typo',
          'settings/my-upgrade-4.0.0-4.0.1.sql' => 'Added',
          'settings/my.sql' => 'Added view_count and comment_count columns to engine4_event_photos table; added view_count column to engine4_event_topics table; added default permissions for style permission',
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
      'path' => 'application/modules/Event/settings/install.php',
      'class' => 'Event_Installer',
    ),
    'directories' => array(
      'application/modules/Event',
    ),
    'files' => array(
      'application/languages/en/event.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Event_Plugin_Core'
    ),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Event_Plugin_Core',
    ),
    array(
      'event' => 'getActivity',
      'resource' => 'Event_Plugin_Core',
    ),
    array(
      'event' => 'addActivity',
      'resource' => 'Event_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'event',
    'event_album',
    'event_photo',
    'event_post',
    'event_topic',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'event_extended' => array(
      'route' => 'events/:controller/:action/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'controller' => '\D+',
        'action' => '\D+',
      )
    ),
    'event_general' => array(
      'route' => 'events/:action/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'index',
        'action' => 'browse',
      ),
      'reqs' => array(
        'action' => '(index|browse|create|delete|list|manage|edit)',
      )
    ),
    'event_specific' => array(
      'route' => 'events/:action/:event_id/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'event',
        'action' => 'index',
      ),
      'reqs' => array(
        'action' => '(edit|delete|join|leave|invite|accept|style|reject)',
        'event_id' => '\d+',
      )
    ),
    'event_profile' => array(
      'route' => 'event/:id/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'profile',
        'action' => 'index',
			  ),

      'reqs' => array(
        'id' => '\d+',
		      )),
    'event_upcoming' => array(
      'route' => 'events/upcoming/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'index',
        'action' => 'browse',
         'filter' => 'future'
			    )),
    'event_past' => array(
      'route' => 'events/past/*',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'index',
       'action' => 'browse',
       'filter' => 'past'
			    )),

    'event_admin_manage_level' => array(
      'route' => 'admin/event/level/:level_id',
      'defaults' => array(
        'module' => 'event',
        'controller' => 'admin-level',
        'action' => 'index',
        'level_id' => 1
      )
    ),
  ));
 ?>