<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 7305 2010-09-07 06:49:55Z john $
 * @author     John Boehr <j@webligo.com>
 */
return array(
  'package' => array(
    'type' => 'library',
    'name' => 'engine',
    'version' => '4.0.4',
    'revision' => '$Revision: 7305 $',
    'path' => 'application/libraries/Engine',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Engine',
      'author' => 'Webligo Developments',
      'license' => 'http://www.socialengine.net/license/',
      'changeLog' => array(
        '4.0.4' => array(
          'Form/Element/CalendarDateTime.php' => 'Improved localization support',
          'View/Helper/DateTime.php' => 'Deprecated, now forwards to locale helper',
          'View/Helper/FormCalendarDateTime.php' => 'Improved localization support',
          'View/Helper/FormTime.php' => 'Added',
          'View/Helper/FormTinyMce.php' => 'Added RTL support',
          'View/Helper/HeadTranslate.php' => 'Added view helper for loading translations into javascript',
          'View/Helper/Timestamp.php' => 'Now loads translations automatically',
          'View/Helper/TinyMce.php' => 'Added RTL support',
          'manifest.php' => 'Incremented version',
        ),
        '4.0.3' => array(
          'Loader.php' => 'Removed some test code',
          'Content/Controller/Action/Helper.php' => 'Fixes for embedding pages in content system',
          'Db/Adapter/Mysql.php' => 'Fixed missing connection specification, prevents problems in migration',
          'Db/Statement/Mysql.php' => 'Fixed missing connection specification, prevents problems in migration',
          'Form/Decorator/FormErrors.php' => 'Fixed missing translation of element labels',
          'Form/Decorator/FormErrorsSimple.php' => 'Fixed missing translation of element labels',
          'Vfs/Object/Ftp.php' => 'Removed some test code',
          'View/Helper/HighlightText.php' => 'Added',
          'View/Helper/Timestamp.php' => 'Fixes errors with invalid dates',
          'manifest.php' => 'Incremented version',
        ),
        '4.0.2' => array(
          'Filter/Html.php' => 'Added allowed html attributes',
          'Form/Element/CalendarDateTime.php' => 'Fixed am/pm problem',
          'Image/Adapter/Gd.php' => 'Improved exception verbosity',
          'Package/Manager.php' => 'Fixed bug in message logging',
          'Package/Installer/Abstract.php' => 'Fixed bug in message logging',
          'Package/Manifest/Entity/Package.php' => 'Added permission changing for installer; added revision tracking',
          'Package/Manifest/Entity/Permission.php' => 'Added permission changing for installer',
          'Validate/AtLeast.php' => 'Added',
          'Vfs/Adapter/Ssh.php' => 'Fixed bug that would cause error when no error had occurred',
          'View/Helper/FormDate.php' => 'Fixed localization bug for Spanish',
          'View/Helper/Locale.php' => 'Fixes problems with empty dates',
          'View/Helper/Timestamp.php' => 'Fixed several localization problems',
          'manifest.php' => 'Incremented version',
        ),
        '4.0.1' => array(
          'Comet.php' => 'Disabled, pending restructuring',
          'Loader.php' => 'DS to DIRECTORY_SEPARATOR',
          'manifest.php' => 'Incremented version',
          'Comet/*' => 'Disabled, pending restructuring',
          'Db/Mysql.php' => 'Several bug fixes',
          'Observer/Callback.php' => 'Added svn:keywords',
          'Observer/Exception.php' => 'Added svn:keywords',
          'Package/Archive.php' => 'Increased exception verbosity',
          'Package/Utilities.php' => 'Switched sql split method from pcre- to position-based; increased exception verbosity',
          'Package/Installer/Module.php' => 'Fixed problem with installing/upgrading when there is no corresponding sql file',
          'Package/Manifest/Entity/Meta.php' => 'Added svn:keywords',
          'Stream/*' => 'Several bug fixes',
          'Translate/Writer/Csv.php' => 'Added chmod to prevent permissions problems',
          'Vfs/Adapter/Abstract.php' => 'Fixed problem caused by checking arguments in the wrong order',
          'Vfs/Adapter/Exception.php' => 'Added svn:keywords',
          'Vfs/Adapter/Ftp.php' => 'Fixed BSD detection problem; fixed directory listing parsing problem on some FTP servers; added mkdir to put method; modified changeDirectory to work without an active FTP connection',
          'Vfs/Adapter/Ssh.php' => 'Fixed BSD detection problem',
          'Vfs/Adapter/System.php' => 'Fixed BSD detection problem',
          'Vfs/Directory/Standard.php' => 'Added svn:keywords',
          'Vfs/Info/*' => 'Added svn:keywords',
          'Vfs/Object/*' => 'Added svn:keywords',
          'Vfs/Object/Ssh.php' => 'Fixed typo',
          'Vfs/Stream/*' => 'Added svn:keywords',
          'View/Helper/FormMultiCheckbox.php' => 'Fixed problem where required attribute would not work properly in the field system for multi select fields',
          'View/Helper/FormTinyMce.php' => 'Mobile browsers will now fall back to a textarea',
        ),
      ),
    ),
    'directories' => array(
      'application/libraries/Engine',
    )
  )
) ?>