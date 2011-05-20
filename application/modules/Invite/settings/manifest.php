<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Invite
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7341 2010-09-10 03:51:24Z john $
 * @author     Steve
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'invite',
    'version' => '4.0.2',
    'revision' => '$Revision: 7341 $',
    'path' => 'application/modules/Invite',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Invite',
      'description' => 'Invite',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.2' => array(
          'Api/Core.php' => 'Removed deprecated code',
          'controllers/IndexController.php' => 'Uses common method',
          'controllers/SignupController.php' => 'Refactored and improved',
          'Form/Invite.php' => 'Updated email params',
          'Model/DbTable/Invites.php' => 'Added common sendInvites method; fixes incorrect link in invite message',
          'Plugin/Signup.php' => 'Refactored and improved',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          '/application/languages/en/invite.csv' => 'Added missing phrases',
        ),
        '4.0.1' => array(
          'controllers/IndexController.php' => 'Users could send invites even if disabled',
          'settings/manifest.php' => 'Incremented version',
        ),
      ),
    ),
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'version' => '4.0.4',
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
    ),
    'directories' => array(
      'application/modules/Invite',
    ),
    'files' => array(
      'application/languages/en/invite.csv',
    ),
  ),
  // Content -------------------------------------------------------------------
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserCreateAfter',
      'resource' => 'Invite_Plugin_Signup',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'invite'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public
    // User
    'invite' => array(
      'route' => 'invite',
      'defaults' => array(
        'module' => 'invite',
        'controller' => 'index',
        'action' => 'index'
      )
    ),

    // Admin
    'invite_admin_settings' => array(
      'route' => 'admin/invite/settings',
      'defaults' => array(
        'module' => 'invite',
        'controller' => 'admin',
        'action' => 'settings'
      )
    ),
    'invite_admin_stats' => array(
      'route' => 'admin/invite/stats',
      'defaults' => array(
        'module' => 'invite',
        'controller' => 'admin',
        'action' => 'stats'
      )
    ),
  // end routes
  ),
);