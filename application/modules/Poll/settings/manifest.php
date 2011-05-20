<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7318 2010-09-08 05:30:40Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'poll',
    'version' => '4.0.4',
    'revision' => '$Revision: 7318 $',
    'path' => 'application/modules/Poll',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Polls',
      'description' => 'Polls',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'externals/styles/main.css' => 'Improved RTL support',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix privacy issues in the feed',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3-4.0.4.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/index/manage.tpl' => 'Added missing translation',
          '/application/languages/en/poll.csv' => 'Added missing phrases',
        ),
        '4.0.3' => array(
          'controllers/IndexController.php' => 'Fixed activity privacy bug',
          'Form/Create.php' => 'Added',
          'Form/Edit.php' => 'Added',
          'Form/Index/Create.php' => 'Moved',
          'Form/Index/Edit.php' => 'Moved',
          'Model/Poll.php' => 'Fixed search indexing bug',
          'settings/manifest.php' => 'Incremented version',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/admin-manage/index.tpl' => 'Added correct locale date format',
          'views/scripts/index/create.tpl' => 'Added re-population of options on failed validation',
          '/application/languages/en/poll.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'controllers/AdminSettingsController.php' => 'Various level settings fixes and enhancements',
          'Form/Admin/Level.php' => 'Moved',
          'Form/Admin/Settings/Level.php' => 'Various level settings fixes and enhancements',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
        ),
        '4.0.1' => array(
          'controllers/AdminSettingsController.php' => 'Fixed problem in level select',
          'controllers/IndexController.php' => 'Fixed public permissions',
          'Plugin/Core.php' => 'Query optimization',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.0-4.0.1.sql' => 'Added',
          'settings/my.sql' => 'Added missing search, view_count, comment_count columns to the engine4_poll_polls table',
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
      'path' => 'application/modules/Poll/settings/install.php',
      'class' => 'Poll_Installer',
    ),
    'directories' => array(
      'application/modules/Poll',
    ),
    'files' => array(
      'application/languages/en/poll.csv',
    ),
  ),
  // Content -------------------------------------------------------------------
  'content'=> array(
    'poll_profile_polls' => array(
      'type' => 'action',
      'title' => 'Polls Profile Tabs',
      'route' => array(
        'module' => 'poll',
        'controller' => 'widget',
        'action' => 'profile-polls',
      ),
    )
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Poll_Plugin_Core'
    ),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Poll_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'poll'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public
    'poll_browse' => array(
      'route' => 'polls/:page/:sort/*',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'browse',
        'page' => 1,
        'sort' => 'recent',
      ),
      'reqs' => array(
        'page' => '\d+',
      )
    ),
    'poll_list' => array(
      'route' => 'polls/list/:user_id/:page/:sort/*',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'list',
        'page' => 1,
        'sort' => 'recent',
      ),
      'reqs' => array(
        'user_id' => '\d+',
        'poll_id' => '\d+',
      )
    ),
    'poll_search' => array(
      'route' => 'polls/search/:page/:sort',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'search',
        'page' => 1,
        'sort' => 'recent',
      )
    ),
    'poll_view' => array(
      'route' => 'polls/view/:poll_id/:slug',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'view',
        'slug' => '',
      ),
      'reqs' => array(
        'poll_id' => '\d+'
      )
    ),
    'poll_vote' => array(
      'route' => 'polls/vote',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'vote'
      )
    ),
    // User
    'poll_create' => array(
      'route' => 'polls/create',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'create'
      )
    ),
    'poll_delete' => array(
      'route' => 'polls/delete/:poll_id',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'delete'
      ),
      'reqs' => array(
        'poll_id' => '\d+'
      )
    ),
    'poll_edit' => array(
      'route' => 'polls/edit/:poll_id',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'edit'
      ),
      'reqs' => array(
        'poll_id' => '\d+'
      )
    ),
    'poll_manage' => array(
      'route' => 'polls/manage',
      'defaults' => array(
        'module' => 'poll',
        'controller' => 'index',
        'action' => 'manage'
      )
    ),
  ),
);
