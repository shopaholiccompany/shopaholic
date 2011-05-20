<?php
/**
 * SocialEngine
 *
 * @category   Application_Theme
 * @package    Bamboo
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manifest.php 6973 2010-08-05 22:08:17Z john $
 * @author     Bryan
 */

return array(
  'package' => array(
    'type' => 'theme',
    'name' => 'bamboo',
    'version' => '4.0.1',
    'revision' => '$Revision: 6973 $',
    'path' => 'application/themes/bamboo',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Bamboo Theme',
      'thumb' => 'bamboo_theme.jpg',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.1' => array(
          'manifest.php' => 'Incremented version',
          'theme.css' => 'Uses fixed relative URL support in Scaffold',
        ),
      ),
    ),
    'actions' => array(
      'install',
      'upgrade',
      'refresh',
      'remove',
    ),
    'callback' => array(
      'class' => 'Engine_Package_Installer_Theme',
    ),
    'directories' => array(
      'application/themes/bamboo',
    ),
  ),
  'files' => array(
    'theme.css',
    'constants.css',
  )
) ?>