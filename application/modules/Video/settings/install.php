<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: install.php 7244 2010-09-01 01:49:53Z john $
 * @author     Stephen
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Video_Installer extends Engine_Package_Installer_Module
{
  function onInstall()
  {
    //
    // install content areas
    //
    $db     = $this->getDb();
    $select = new Zend_Db_Select($db);

    // profile page
    $select
      ->from('engine4_core_pages')
      ->where('name = ?', 'user_profile_index')
      ->limit(1);
    $page_id = $select->query()->fetchObject()->page_id;


    // video.profile-videos

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $select
      ->from('engine4_core_content')
      ->where('page_id = ?', $page_id)
      ->where('type = ?', 'widget')
      ->where('name = ?', 'video.profile-videos')
      ;
    $info = $select->query()->fetch();

    if( empty($info) ) {

      // container_id (will always be there)
      $select = new Zend_Db_Select($db);
      $select
        ->from('engine4_core_content')
        ->where('page_id = ?', $page_id)
        ->where('type = ?', 'container')
        ->limit(1);
      $container_id = $select->query()->fetchObject()->content_id;

      // middle_id (will always be there)
      $select = new Zend_Db_Select($db);
      $select
        ->from('engine4_core_content')
        ->where('parent_content_id = ?', $container_id)
        ->where('type = ?', 'container')
        ->where('name = ?', 'middle')
        ->limit(1);
      $middle_id = $select->query()->fetchObject()->content_id;

      // tab_id (tab container) may not always be there
      $select
        ->reset('where')
        ->where('type = ?', 'widget')
        ->where('name = ?', 'core.container-tabs')
        ->where('page_id = ?', $page_id)
        ->limit(1);
      $tab_id = $select->query()->fetchObject();
      if( $tab_id && @$tab_id->content_id ) {
          $tab_id = $tab_id->content_id;
      } else {
        $tab_id = null;
      }

      // tab on profile
      $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type'    => 'widget',
        'name'    => 'video.profile-videos',
        'parent_content_id' => ($tab_id ? $tab_id : $middle_id),
        'order'   => 12,
        'params'  => '{"title":"Videos","titleCount":true}',
      ));

    }


    // Api is not available
    //$ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
    
    $ffmpeg_path = $db->select()
      ->from('engine4_core_settings', 'value')
      ->where('name = ?', 'video.ffmpeg.path')
      ->limit(1)
      ->query()
      ->fetchColumn(0)
      ;
    
    if ((empty($ffmpeg_path) || !@file_exists($ffmpeg_path)) && '/' == substr($_SERVER['SCRIPT_FILENAME'], 0, 1)) {
        // this is a linux/unix/MacOS installation, so we may be able to auto-guess ffmpeg location using "which"
        @exec('which ffmpeg', $res, $ret);
        // Api not available
        //Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path = $res[0];
        if( $ret === 0 && !empty($res) ) {
          $count = $db->update('engine4_core_settings', array(
            'value' => $res[0],
          ), array(
            'name = ?' => 'video.ffmpeg.path',
          ));
          if( $count === 0 ) {
            try {
              $db->insert('engine4_core_settings', array(
                'value' => $res[0],
                'name' => 'video.ffmpeg.path',
              ));
            } catch( Exception $e ) {
              
            }
          }
        }
    }
    
    parent::onInstall();
  }
}
?>