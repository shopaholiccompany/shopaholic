<?php
/**
 * @package     Engine_Core
 * @version     $Id: index.php 7244 2010-09-01 01:49:53Z john $
 * @copyright   Copyright (c) 2008 Webligo Developments
 * @license     See license.html
 */
define('_ENGINE_R_BASE', dirname($_SERVER['SCRIPT_NAME']));
define('_ENGINE_R_FILE', $_SERVER['SCRIPT_NAME']);
define('_ENGINE_R_REL', 'application');
define('_ENGINE_R_TARG', 'index.php');

include dirname(__FILE__) . DIRECTORY_SEPARATOR
  . _ENGINE_R_REL . DIRECTORY_SEPARATOR
  . _ENGINE_R_TARG;
