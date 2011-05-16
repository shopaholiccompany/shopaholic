<?php
/**
 * SocialEngine
 *
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7322 2010-09-09 05:05:22Z john $
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'core',
    'name' => 'base',
    'version' => '4.0.4',
    'revision' => '$Revision: 7322 $',
    'path' => '/',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Base',
      'description' => 'Base',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.4' => array(
          '.htaccess' => 'Fixed 500 errors on some servers',
          'robots.txt' => 'Fixed query string problems in redirect',
          'application/index.php' => 'Improved SSL support; fixed query string problems in redirect',
          'application/settings/beta1-beta2.sql' => 'Removed',
          'application/settings/beta1-beta2_classifieds.sql' => 'Removed',
          'application/settings/beta2-beta3.sql' => 'Removed',
          'application/settings/beta3-rc1.sql' => 'Removed',
          'application/settings/constants.xml' => 'Added constant theme_pulldown_contents_list_background_color_active',
          'application/settings/manifest.php' => 'Incremented version',
        ),
        '4.0.3' => array(
          '.htaccess' => 'Added better missing file handling',
          'application/css.php' => 'Removed some test code',
          'application/index.php' => 'Missing configuration files handled better',
          'application/settings/cache.php' => 'Removed',
          'application/settings/cache.sample.php' => 'Added',
          'application/settings/general.php' => 'Removed',
          'application/settings/general.sample.php' => 'Added',
          'application/settings/mail.php' => 'Removed',
          'application/settings/mail.sample.php' => 'Added',
          'application/settings/manifest.php' => 'Incremented version',
          'application/settings/session.php' => 'Removed',
          'application/settings/session.sample.php' => 'Added',
        ),
        '4.0.2' => array(
          'application/settings/manifest.php' => 'Incremented version; permissions are set in the installer',
        ),
        '4.0.1' => array(
          'index.php' => 'Added svn:keywords',
          'README.html' => 'Updated readme',
          'application/comet.php' => 'Removed',
          'application/index.php' => 'Removed comet; modification to APPLICATION_ENV handling',
          'application/settings/manifest.php' => 'Incremented version; removed comet; adding theme .htaccess to manifest files',
          'application/settings/my.sql' => 'Regenerated',
          'application/settings/session.php' => 'Default session cookie to not httponly to fix FancyUpload problems'
        ),
      ),
    ),
    'actions' => array(
      'install',
      'upgrade',
      'refresh',
    ),
    'files' => array(
      '.htaccess',
      'README.html',
      'index.php',
      'robots.txt',
      'xd_receiver.htm',
      'application/.htaccess',
      'application/comet.php',
      'application/config.php',
      'application/css.php',
      'application/index.php',
      'application/lite.php',
      'application/maintenance.html',
      'application/mobile.php',
      'application/offline.html',
      'application/libraries/index.html',
      'application/modules/index.html',
      'application/packages/index.html',
      'application/plugins/index.html',
      'application/themes/index.html',
      'application/themes/.htaccess',
      'application/widgets/index.html',
      'externals/index.html',
      'externals/.htaccess',
    ),
    'directories' => array(
      'application/settings',
      'public',
      'temporary/cache',
      'temporary/log',
      'temporary/scaffold',
      'temporary/session',
      'temporary/package',
    ),
    'permissions' => array(
      array(
        'path' => 'application/languages',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'application/packages',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'application/themes',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'application/settings',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'public',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
      array(
        'path' => 'temporary',
        'mode' => 0777,
        'inclusive' => true,
        'recursive' => true,
      ),
    ),
  ),
); ?>