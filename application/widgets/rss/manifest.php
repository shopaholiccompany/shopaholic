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
    'name' => 'rss',
    'version' => '4.0.2',
    'revision' => '$Revision: 7129 $',
    'path' => 'application/widgets/rss',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'RSS Feed',
      'description' => 'Displays an RSS feed.',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.2' => array(
          'index.tpl' => 'Added styles',
          'manifest.php' => 'Incremented version',
        ),
      ),
    ),
    'directories' => array(
      'application/widgets/rss',
    ),
  ),

  // Backwards compatibility
  'type' => 'widget',
  'name' => 'rss',
  'version' => '4.0.2',
  'revision' => '$Revision: 7129 $',
  'title' => 'RSS',
  'description' => 'Displays an RSS feed.',
  'category' => 'Widgets',
  'adminForm' => array(
    'elements' => array(
      array(
        'Text',
        'title',
        array(
          'label' => 'Title'
        )
      ),
      array(
        'Text',
        'url',
        array(
          'label' => 'URL'
        )
      ),
    ),
  ),
) ?>