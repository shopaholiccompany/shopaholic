<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: manage.tpl 7281 2010-09-03 03:46:33Z john $
 * @author     Steve
 */
?>

<div class="headline">
  <h2>
    <?php echo $this->translate('Polls');?>
  </h2>
  <div class="tabs">
    <?php
      // Render the menu
      echo $this->navigation()
        ->menu()
        ->setContainer($this->navigation)
        ->render();
    ?>
  </div>
</div>
<div class='layout_middle'>
  <?php if (0 == count($this->paginator) ): ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('There are no polls yet.') ?>
        <?php if (TRUE): // @todo check if user is allowed to create a poll ?>
        <?php echo $this->translate('Why don\'t you %1$screate one%2$s', '<a href="'.$this->url(array(), 'poll_create').'">', '</a>') ?>
        <?php endif; ?>
      </span>
    </div>
  <?php else: // $this->polls is NOT empty ?>
    <ul class="polls_browse">
      <?php foreach ($this->paginator as $poll): ?>
      <li id="poll-item-<?php echo $poll->poll_id ?>">
        <?php echo $this->htmlLink($poll->getHref(), $this->itemPhoto($this->owner, 'thumb.icon'), array('class' => 'polls_browse_photo')) ?>
        <div class="polls_browse_options">
          <?php echo $this->htmlLink(array('route' => 'poll_delete', 'poll_id' => $poll->poll_id), $this->translate('Delete Poll'), array(
            'class'=>'buttonlink smoothbox icon_poll_delete'
           )) ?>
          <a href='<?php echo $this->url(array('poll_id' => $poll->poll_id), 'poll_edit', true) ?>' class='buttonlink icon_poll_edit'>
            <?php echo $this->translate('Edit Privacy');?>
          </a>
        </div>
        <div class="polls_browse_info">
          <?php echo $this->htmlLink($poll->getHref(), $poll->getTitle()) ?>
          <div class="polls_browse_info_date">
              <?php echo $this->translate('Posted by %s', $this->htmlLink($this->users[$poll->user_id], $this->users[$poll->user_id]->getTitle())) ?>
              <?php echo $this->timestamp($poll->creation_date) ?>
              -
              <?php echo $this->translate(array('%s vote', '%s votes', $this->pollVotes[$poll->poll_id]), $this->locale()->toNumber($this->pollVotes[$poll->poll_id])) ?>
              -
              <?php echo $this->translate(array('%s view', '%s views', $poll->views), $this->locale()->toNumber($poll->views)) ?>
          </div>
          <?php if (!empty($poll->description)): ?>
            <div class="polls_browse_info_desc">
              <?php echo $poll->description ?>
            </div>
          <?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; // $this->polls is NOT empty ?>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>