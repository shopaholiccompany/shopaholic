<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: index.tpl 7309 2010-09-07 08:41:02Z john $
 * @access	   John
 */
?>

<h3><?php echo $this->translate('Upcoming Events');?></h3>
<ul id="events-upcoming">
  <?php foreach( $this->paginator as $event ): ?>
    <li>
      <?php echo $event->__toString() ?>
      <div class="events-upcoming-date">
        <?php echo $this->timestamp($event->starttime, array('class'=>'eventtime')) ?>
      </div>
    </li>
  <?php endforeach; ?>
</ul>