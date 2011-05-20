<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Core.php 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Storage_Plugin_Core
{
  public function onItemDeleteBefore($event)
  {
    $item = $event->getPayload();

    if( $item->getType() !== 'storage_file' ) {
      $table = Engine_Api::_()->getItemTable('storage_file');
      $select = $table->select()
        ->where('parent_type = ?', $item->getType())
        ->where('parent_id = ?', $item->getIdentity());

      foreach( $table->fetchAll($select) as $file ) {
        $file->delete();
      }
    }
  }
}