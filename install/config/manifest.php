<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7393 2010-09-15 21:21:14Z john $
 * @author     John
 */

return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'core',
    'name' => 'install',
    'version' => '4.0.6',
    'revision' => '$Revision: 7393 $',
    'path' => '/',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Package Manager',
      'description' => 'Package Manager',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.6' => array(
          'install/config/manifest.php' => 'Incremented version',
          'install/controllers/ImportController.php' => 'Increased cache lifetime; added new configuration options to migration; added email on completion of migration; added even better error handling',
          'install/controllers/ManageController.php' => 'Fixed weird problem with displaying log messages in query step',
          'install/forms/Import/Version3.php' => 'Added lots of new configuration options to migration',
          'install/import/Abstract.php' => 'Added new configuration options to migration',
          'install/import/DbAbstract.php' => 'Fixed memory problems when importing extremely large sites; added timeout detection',
          'install/import/Version3/AbstractFields.php' => 'Now utilizes batch mode',
          'install/import/Version3/MessagesConversations.php' => 'Added conversation title support; fixed recipient count',
          'install/import/Version3/MessagesMessages.php' => 'Removed untranslated "Re:" from message titles',
          'install/import/Version3/UserAdmins.php' => 'Fixed possible bug in batch mode',
          'install/layouts/scripts/default.tpl' => 'Updated mootools',
          'install/views/scripts/import/version3-split.tpl' => 'Improved progress reporting; got rid of that annoying javascript alert box on completion',
          'install/views/scripts/manage/complete.tpl' => 'Fixed incorrect admin link',
        ),
        '4.0.5' => array(
          'install/.htaccess' => 'Better handling of missing files',
          'install/config/manifest.php' => 'Incremented version',
          'install/controllers/ImportController.php' => 'Version 3 import supports splitting up into separate requests',
          'install/externals/styles/styles.css' => 'Style tweak',
          'install/forms/Import/Version3.php' => 'Various enhancements',
          'install/import/*' => 'Various enhancements; added search indexing; improved privacy import; better exception handling; supports splitting up into separate requests',
          'install/views/scripts/import/version3-split.tpl' => 'Version 3 import supports splitting up into separate requests',
          'install/views/scripts/manage/prepare.tpl' => 'Style tweak',
        ),
        '4.0.4' => array(
          'install/controllers/AuthController.php' => 'Removed var_dump on failed login',
          'install/controllers/ImportController.php' => 'Added missing authentication check',
          'install/controllers/SdkController.php' => 'Added missing authentication check',
          'install/controllers/UtilityController.php' => 'Added missing authentication check',
          'install/externals/images/package.png' => 'Added',
          'install/externals/styles/styles.css' => 'Style tweaks for importers',
          'install/import/Abstract.php' => 'Fixed logging bug',
          'install/import/Version3/AbstractComments.php' => 'Fixed incorrect type for self comments',
          'install/views/scripts/install/db-sanity.tpl' => 'Style tweak',
          'install/views/scripts/install/sanity.tpl' => 'Style tweak',
          'install/views/scripts/manage/prepare.tpl' => 'Style tweak',
        ),
        '4.0.3' => array(
          'install/config/manifest.php' => 'Incremented version',
          'install/controller/ImportController.php' => 'Added SE3 Importer',
          'install/controller/ManagerController.php' => 'Added backwards compatibility for unreleased library-engine-4.0.2 features',
          'install/externals/styles/*' => 'Added styles for importer',
          'install/import/Version3/*' => 'Added SE3 Importer',
        ),
        '4.0.2' => array(
          'install/Bootstrap.php' => 'Added main navigation',
          'install/config/manifest.php' => 'Incremented',
          'install/controllers/AuthController.php' => 'Logout now redirects to return url even if already logged out',
          'install/controllers/ImportController.php' => 'Added Ning Import Tool',
          'install/controllers/InstallController.php' => 'Added placeholder',
          'install/controllers/ManageController.php' => 'Moved main navigation',
          'install/data/skeletons/module/Bootstrap.php.template' => 'Fixed bug in skeleton module generation',
          'install/externals/styles/*' => 'Added styles for main navigation and import tools',
          'install/forms/Import/*' => 'Added Ning Import Tool',
          'install/import/*' => 'Added Ning Import Tool',
          'install/layouts/scripts/default.tpl' => 'Added main navigation',
          'install/views/scripts/_managerMenu.tpl' => 'Added main navigation',
          'install/views/scripts/import/*' => 'Added Ning Import Tool',
          'install/views/scripts/manage/*' => 'Moved main navigation to layout',
          'install/views/scripts/sdk/*' => 'Moved main navigation to layout',
          'install/views/scripts/settings/*' => 'Moved main navigation to layout',
        ),
        '4.0.1' => array(
          'install/.htaccess' => 'Added php_value for memory_limit and max_execution_time',
          'install/Bootstrap.php' => 'Added cache, logging, routes for SDK, (future) Ning importer, and (future) v3 migration',
          'install/index.php' => 'Modified handling of APPLICATION_ENV',
          'install/data/' => 'Data files for the SDK skeleton generator',
          'install/import/' => 'Importer classes for (future) Ning importer and (future) v3 migrator',
          'install/config/manifest.php' => 'Incremented version',
          'install/controllers/AuthController.php' => 'Fixed typo',
          'install/controllers/BackupController.php' => '(n/a)',
          'install/controllers/CompareController.php' => '(n/a)',
          'install/controllers/ImportController.php' => '(n/a)',
          'install/controllers/InstallController.php' => 'Optimized execution of sql queries',
          'install/controllers/MigrateController.php' => '(n/a)',
          'install/controllers/SdkController.php' => 'Added the Developer SDK',
          'install/controllers/UtilityController.php' => 'Added',
          'install/controllers/VfsController.php' => 'Added',
          'install/externals/*' => 'Added images and styles for the Developer SDK',
          'install/forms/Backup/*' => '(n/a)',
          'install/forms/Migrate/*' => '(n/a)',
          'install/forms/Sdk/*' => 'Added Developer SDK',
          'install/forms/VfsInfo.php' => 'Added return url parameter',
          'install/layouts/scripts/default.tpl' => 'Added Developer SDK',
          'install/views/helpers/PackageSelect.php' => 'Fixed bug on deleting mis-named packages',
          'install/views/scripts/backup/*' => '(n/a)',
          'install/views/scripts/compare/*' => '(n/a)',
          'install/views/scripts/install/license.tpl' => 'Incorrect link to client area',
          'install/views/scripts/install/sanity.tpl' => 'Added force',
          'install/views/scripts/migrate/*' => '(n/a)',
          'install/views/scripts/sdk/*' => 'Added Developer SDK',
          'install/views/scripts/utility/*' => '(n/a)',
          'install/views/scripts/vfs/*' => '(n/a)',
          'install/views/scripts/_rawError.tpl' => 'Error page for bootstrap errors',

          '/temporary/package/compare/' => '(n/a)',
          '/temporary/package/sdk/' => 'Added Developer SDK',
        ),
        '4.0.2' => array(
          'install/layouts/scripts/default.tpl' => 'Fixed SDK link',
        ),
      ),
    ),
    'actions' => array(
      'install',
      'upgrade',
      'refresh',
    ),
    'directories' => array(
      'install',
    ),
    'permissions' => array(
      array(
        'path' => 'install/config',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'temporary/package',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
    ),
  ),
); ?>