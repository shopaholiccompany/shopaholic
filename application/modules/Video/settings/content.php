<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: content.php 7244 2010-09-01 01:49:53Z john $
 * @author		 John
 */
return array(
  array(
    'title' => 'Profile Videos',
    'description' => 'Displays a member\'s videos on their profile.',
    'category' => 'Videos',
    'type' => 'widget',
    'name' => 'video.profile-videos',
  ),
  array(
    'title' => 'Recent Videos',
    'description' => 'Displays a list of recently uploaded videos.',
    'category' => 'Videos',
    'type' => 'widget',
    'name' => 'video.list-recent-videos',
    'defaultParams' => array(
      'title' => 'Recent Videos',
    ),
  ),
  array(
    'title' => 'Popular Videos',
    'description' => 'Displays a list of most viewed videos.',
    'category' => 'Videos',
    'type' => 'widget',
    'name' => 'video.list-popular-videos',
    'defaultParams' => array(
      'title' => 'Popular Videos',
    ),
  ),
) ?>