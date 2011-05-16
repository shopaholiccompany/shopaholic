<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7393 2010-09-15 21:21:14Z john $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'user',
    'version' => '4.0.4',
    'revision' => '$Revision: 7393 $',
    'path' => 'application/modules/User',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Members',
      'description' => 'Members',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          'controllers/AuthController.php' => 'Added params to lost password email',
          'controllers/SettingsController.php' => 'Fixed checking of permissions for showing delete account tab',
          'controllers/SignupController.php' => 'Added params to resend verification email; improved confirmation redirection handling; improved verification error reporting',
          'externals/styles/main.css' => 'Improved RTL support',
          'Form/Search.php' => 'Fixed javascript error',
          'Form/Settings/Privacy.php' => 'Fixed comment option type; adjusted network privacy label',
          'Form/Settings/Delete.php' => 'Removed deprecated code',
          'Form/Signup/Account.php' => 'Code formatting; fixed bug in invite code checking',
          'Form/Signup/Invite.php' => 'Code formatting; added email validation; removed deprecated code',
          'Form/Signup/Photo.php' => 'Removed deprecated code',
          'Model/User.php' => 'Fixed non-optimal level check; now cleans out fields values on delete; fixed issue with resetting password after migration',
          'Plugin/Menus.php' => 'Fixed checking of permissions for showing delete account tab',
          'Plugin/Signup/Account.php' => 'Added params to signup emails; fixed bug in invite code checking',
          'Plugin/Signup/Invite.php' => 'Code formatting; uses common method',
          'Plugin/Task/Cleanup.php' => 'Added docblock',
          'Plugin/Task/Maintenance/RebuildPrivacy.php' => 'Added to fix migration problems',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.3p1-4.0.4.sql' => 'Added',
          'settings/my.sql' => 'Incremented version',
          'views/scripts/_formButtonCancel.tpl' => 'Removing deprecated code',
          'views/scripts/auth/reset.tpl' => 'Added missing translation',
          'views/scripts/index/browse.tpl' => 'Fixed broken smoothbox binding after searching',
          'views/scripts/signup/confirm.tpl' => 'Fixed incorrect link',
          'views/scripts/signup/index.tpl' => 'Fixed warning message',
          'views/scripts/signup/verify.tpl' => 'Added error messages',
          'widgets/list-online/Controller.php' => 'Fixed locale problems caused by incorrect cache key; now properly excludes members that have opted out',
          'widgets/list-popular/Controller.php' => 'Fixed locale problems caused by incorrect cache key',
          'widgets/list-signups/Controller.php' => 'Fixed locale problems caused by incorrect cache key',
          '/application/languages/en/user.csv' => 'Added phrases',
        ),
        '4.0.3' => array(
          'Api/Core.php' => 'Fixes for empty viewers',
          'controllers/IndexController.php' => 'Disabled members do not show up in browse members page',
          'controllers/ProfileController.php' => 'Admins and moderators can now see disabled member profiles',
          'controllers/SettingsController.php' => 'Proper handling of delete auth; moved notifications to another tab',
          'Form/Settings/General.php' => 'Moved notifications to another tab; added missing translation',
          'Form/Settings/Privacy.php' => 'Respects subject instead of viewer',
          'Model/User.php' => 'Better handling of empty display names',
          'Model/DbTable/Facebook.php' => 'Fixed typo in javascript',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.2-4.0.3.sql' => 'Added',
          'settings/my.sql' => 'Incremented version; moved notifications to another tab',
          'views/scripts/admin-manage/index.tpl' => 'Style tweak',
          'views/scripts/settings/notifications.tpl' => 'Moved notifications to another tab',
          'views/scripts/signup/index.tpl' => 'Fixed missing translation',
          'widgets/list-popular/Controller.php' => 'No longer displays disabled or unverified users',
          'widgets/list-signups/Controller.php' => 'No longer displays disabled or unverified users',
          'widgets/profile-info/index.tpl' => 'Removed link around member type; added missing translation',
          '/application/languages/en/user.csv' => 'Added phrases',
        ),
        '4.0.2' => array(
          'controllers/AdminManageController.php' => 'Added log in as user action',
          'controllers/AuthController.php' => 'Added authentication against SE3 table (if migration tool used)',
          'controllers/EditController.php' => 'Fix for unselected menu',
          'controllers/FriendsController.php' => 'Missing translations',
          'controllers/SettingsController.php' => 'Fixed missing check for invisible networks; fix for unselected menu',
          'externals/scripts/composer_facebook.js' => 'IE compatibility fix',
          'Form/Edit/Photo.php' => 'Removed arbitrary limit on profile photo size',
          'Form/Settings/Privacy.php' => 'Cast element options to array',
          'Form/Signup/Account.php' => 'Fixed a problem with validating the invite code if more than one invite sent to the same email',
          'Model/DbTable/Facebook.php' => 'Facebook ex-user bug fix',
          'Model/User.php' => 'Fixed incorrect photo being used when setting a photo as your profile photo',
          'Plugin/Signup/Photo.php' => 'Fixed notice',
          'settings/manifest.php' => 'Incremented version',
          'settings/my-upgrade-4.0.1-4.0.2.sql' => 'Added',
          'settings/my.sql' => 'Various level settings fixes and enhancements',
          'views/scripts/_formButtonSkipInvite.tpl' => 'Missing translation',
          'views/scripts/_formButtonSkipPhoto.tpl' => 'Missing translation',
          'views/scripts/admin-manage/index.tpl' => 'Added log in as user action',
          'widgets/profile-friends/index.tpl' => 'Fix for orphaned rows in membership table',
          'widgets/profile-info/Controller.php' => 'Fixed missing check for invisible networks',
          'widgets/profile-info/index.tpl' => 'Fixed missing check for invisible networks',
        ),
        '4.0.1' => array(
          'Api/Core.php' => 'Adjustments for trial',
          'controllers/AdminManageController.php' => 'Delete now run in transaction',
          'controllers/AdminSettingsController.php' => 'Fixed problem in level select',
          'controllers/AuthController.php' => 'Facebook fixes; adjustments for trial; faster sending of verification email',
          'controllers/EditController.php' => 'Better cleanup of temporary files and bug fix for making a profile picture',
          'controllers/IndexController.php' => 'Better exception throwing in Facebook module',
          'controllers/SettingsController.php' => 'Fixes forced logout bug when changing email address',
          'controllers/SignupController.php' => 'Fixed bug in resend of verification email',
          'externals/styles/main.css' => 'Style fixes',
          'Form/Login.php' => 'Facebook login bug fixes',
          'Model/User.php' => 'Better cleanup of temporary files',
          'Model/DbTable/Facebook.php' => 'Facebook login refresh bug fix; Facebook wall post fix',
          'Plugin/Signup/Photo.php' => 'Better cleanup of temporary files',
          'settings/manifest.php' => 'Incremented version',
          'widgets/profile-tags/Controller.php' => 'Fixed typos',
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
      'priority' => 3000,
    ),
    'directories' => array(
      'application/modules/User',
    ),
    'files' => array(
      'application/languages/en/user.csv',
    ),
  ),
  // Compose -------------------------------------------------------------------
  'compose' => array(
    array('_composeFacebook.tpl', 'user'),
  ),
  'composer' => array(
    'user' => array(
      'script' => array('_composeFacebook.tpl', 'user'),
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'User_Plugin_Core',
    ),
    array(
      'event' => 'onUserCreateAfter',
      'resource' => 'User_Plugin_Core',
    ),
    array(
      'event' => 'getAdminNotifications',
      'resource' => 'User_Plugin_Core',
    )
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'user',
    'user_list',
    'user_list_item',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // User - General
    'user_extended' => array(
      'route' => 'members/:controller/:action/*',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'index',
        'action' => 'index'
      ),
      'reqs' => array(
        'controller' => '\D+',
        'action' => '\D+',
      )
    ),
    'user_general' => array(
      'route' => 'members/:action/*',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'index',
        'action' => 'browse'
      ),
      'reqs' => array(
        'action' => '(home|browse)',
      )
    ),

    // User - Specific
    'user_profile' => array(
      'route' => 'profile/:id/*',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'profile',
        'action' => 'index'
      )
    ),
    
    'user_login' => array(
      'type' => 'Zend_Controller_Router_Route_Static',
      'route' => '/login',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'auth',
        'action' => 'login'
      )
    ),
    'user_logout' => array(
      'type' => 'Zend_Controller_Router_Route_Static',
      'route' => '/logout',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'auth',
        'action' => 'logout'
      )
    ),
    'user_signup' => array(
      'route' => '/signup/:action/*',
      'defaults' => array(
        'module' => 'user',
        'controller' => 'signup',
        'action' => 'index'
      )
    ),
  )
); ?>