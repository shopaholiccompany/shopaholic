<?php
/**
 * SocialEngine
 *
 * @category   Application_Widget
 * @package    Widget
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @author     John
 */

return array(
  'package' => array(
    'type' => 'widget',
    'name' => 'weather',
    'version' => '4.0.0',
    'revision' => '$Revision: 6973 $',
    'path' => 'application/widgets/weather',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Weather',
      'description' => 'Displays the weather.',
      'author' => 'Webligo Developments',
    ),
    'directories' => array(
      'application/widgets/weather',
    ),
  ),

  // Backwards compatibility
  'type' => 'widget',
  'name' => 'weather',
  'version' => '4.0.0',
  'revision' => '$Revision: 6973 $',
  'title' => 'Weather',
  'description' => 'Displays the weather.',
  'category' => 'Widgets',
) ?>