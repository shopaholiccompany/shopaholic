<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Encode.php 7354 2010-09-11 05:52:11Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Video_Plugin_Task_Encode extends Core_Plugin_Task_Abstract
{
  public function getTotal()
  {
    $table = Engine_Api::_()->getDbTable('videos', 'video');
    return $table->select()
        ->from($table->info('name'), new Zend_Db_Expr('COUNT(*)'))
        ->where('status = ?', 0)
        ->query()
        ->fetchColumn(0)
        ;
  }

  public function execute()
  {
    $table  = Engine_Api::_()->getDbTable('videos', 'video');

    $rName = $table->info('name');
    $select = $table->select()
                    ->where('status = ?', 2);
    $row = $table->fetchAll($select);

    $jobs = (int) Engine_Api::_()->getApi('settings', 'core')->video_jobs;

    // if there are less than 2 videos being encoded,
    // get the oldest video with status == 0 and start encoding
    if (count($row)<$jobs){
      $rName = $table->info('name');
      $select = $table->select()
                      ->where('status = ?', 0)
                      ->limit(1)
                      ->order( 'creation_date ASC' );
      $row = $table->fetchRow($select);
      if(!empty($row) && isset($row->code)){
        $this->processAction($row->video_id, $row->code);
      }
    }

  }

  public function processAction($video_id, $filetype){
    // Make sure FFMPEG settings are correct
    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
    if( !$ffmpeg_path || !file_exists($ffmpeg_path)) {
      throw new Video_Model_Exception('Ffmpeg not found');
    }
    if( !is_executable($ffmpeg_path) ) {
      throw new Video_Model_Exception('Ffmpeg found, but is not executable');
    }

    // Get the video object
    $video = Engine_Api::_()->getItem('video', $video_id);
    $video->status = 2;
    $video->save();

    $db = Engine_Api::_()->getDbtable('videos', 'video')->getAdapter();
    $db->beginTransaction();

    try
    {
      // Set up correct file names
      $org_location = APPLICATION_PATH.'/temporary/video/'.$video->video_id.".".$filetype;
      $flv_filename = APPLICATION_PATH . '/temporary/video/'.$video->video_id.'_vconverted.flv';
      $img_filename = APPLICATION_PATH . '/temporary/video/'.$video->video_id.'_vthumb.jpg';

      // process conversion
      $command = "$ffmpeg_path -i $org_location -ab 64k -ar 44100 -qscale 5 -vcodec flv -f flv -r 25 -s 480x386 -v 2 -y $flv_filename 2>&1";
      $shell = $org_location.$flv_filename;
      $shell .= shell_exec($command);

      if(APPLICATION_ENV == 'development'){
        // open video log file
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/video.log');
        $logger = new Zend_Log($writer);

        // write to the log
        $logger->info($shell);
      }

      $success = false;

      if (!preg_match("/Unknown format/i", $shell) && !preg_match("/Unsupported codec/i", $shell) && !preg_match("/video:0kB/i", $shell) && !preg_match("/patch welcome/i", $shell)){
        $success = true;
      }

      // If video conversion was a success
      if ($success){
        // Get duration of the video to caculate where to get the thumbnail
        $duration = $shell;
        $search='/Duration: (.*?)[.]/';
        $duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE);
        $duration = $matches[1][0];
        list($hours, $minutes, $seconds) = preg_split('[:]', $duration);
        $duration = ceil($seconds + $minutes*(60) + $hours*(3600));
        $success = false;

        if(APPLICATION_ENV == 'development'){
          $log_filename = APPLICATION_PATH . '/temporary/video/'.$video->video_id."_development_video_log2.txt";
          $f = fopen($log_filename, "w");
          fwrite($f, $shell);
          fclose($f);
        }

        // process thumbnail
        $command = "$ffmpeg_path -i $flv_filename -f image2 -ss 4.00 -v 2 -vframes 1 $img_filename";
        $shell = shell_exec($command);


        // after thumbnail is made store it, the code below is using the wrong file path.
        // need to get the correct address
        $video_params = Array('parent_id'=>$video->video_id, 'parent_type'=>$video->getType(), 'user_id'=>$video->owner_id);
        try
        {
          $videoFile = Engine_Api::_()->storage()->create($flv_filename, $video_params);
        }
        catch (Exception $e)
        {
          $video->status = 7;
          $video->save();
          $db->commit();

          // delete the files from temp dir
          unlink($org_location);
          unlink($flv_filename);
          unlink($img_filename);

          return;
        }
        $image = Engine_Image::factory();
        $image->open($img_filename)
          ->resize(120, 240)
          ->write($img_filename)
          ->destroy();
        try
        {
          $thumbFileRow = Engine_Api::_()->storage()->create($img_filename, $video_params);
        }
        catch (Exception $e)
        {
          $video->status = 7;
          $video->save();
          $db->commit();

          // delete the files from temp dir
          unlink($org_location);
          unlink($flv_filename);
          unlink($img_filename);

          return;
        }


        // Video processing was a success!
        // Save the information
        $video->photo_id = $thumbFileRow->file_id;
        $video->file_id = $videoFile->file_id;
        $video->duration = $duration;
        $video->status = 1;
        $video->save();

        // delete the files from temp dir
        unlink($org_location);
        unlink($flv_filename);
        unlink($img_filename);
      }
      else {
        if (preg_match("/Unsupported codec/i", $shell)){
          $video->status = 3;
        }
        else if (preg_match("/video:0kB/i", $shell)){
          $video->status = 5;
        }
        else $video->status = 3;

        $video->save();

        // output a log file to the temp directory with the filename as the id of the video in question
        //APPLICATION_PATH . '/temporary/video/'.$video->video_id.".".$file_ext;
        $log_filename = APPLICATION_PATH . '/temporary/video/'.$video->video_id."_failed.txt";
        $f = fopen($log_filename, "w");
        fwrite($f, $shell);
        fclose($f);

        // @todo add video failed notification to member
      }
      $db->commit();
    }
    catch( Exception $e )
    {
      $db->rollBack();
      return;
    }

    // insert action in a seperate transaction if video status is a success
    if ($video->status == 1){
      $db->beginTransaction();
      try {
          $owner = $video->getOwner();

          // new action
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($owner, $video, 'video_new');
          if ($action!=null) Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $video);

          // notify the owner
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $owner, $video, 'video_processed');

        $db->commit();
      } catch( Exception $e ) {
        $db->rollBack();
        throw $e;
      }
    }
  }
}