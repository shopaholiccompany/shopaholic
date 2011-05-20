<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: browse.tpl 7244 2010-09-01 01:49:53Z john $
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

<div class='layout_right'>
  <?php echo $this->search_form->render($this) ?>

  <?php if($this->can_create):?>
    <div class="quicklinks">
      <ul>
        <li>
          <a href='<?php echo $this->url(array(), 'poll_create') ?>' class='buttonlink icon_poll_new'>
            <?php echo $this->translate('Create New Poll') ?>
          </a>
        </li>
      </ul>
    </div>
  <?php endif;?>

  <script type="text/javascript">
  //<![CDATA[
    $('browse_polls_by').addEvent('change', function(){
      $(this).getParent('form').submit();
    });
  //]]>
  </script>
</div>
<div class='layout_middle'>
  <?php if (0 == count($this->paginator) ): ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('There are no polls yet.') ?>
        <?php if ($this->can_create): ?>
        <?php echo $this->translate('Why don\'t you %1$screate one%2$s', '<a href="'.$this->url(array(), 'poll_create').'">', '</a>') ?>
        <?php endif; ?>
      </span>
    </div>
  <?php else: // $this->polls is NOT empty ?>
    <ul class="polls_browse">
      <?php foreach ($this->paginator as $poll): ?>
      <li id="poll-item-<?php echo $poll->poll_id ?>">
        <?php echo $this->htmlLink(
                      $poll->getHref(),
                      $this->itemPhoto($poll->getOwner(), 'thumb.icon', $poll->getOwner()->username),
                      array('class' => 'polls_browse_photo')
        ) ?>
        <div class="polls_browse_info">
          <h3>
            <?php echo $this->htmlLink($poll->getHref(), $poll->getTitle()) ?>
          </h3>
          <div class="polls_browse_info_date">
            <?php echo $this->translate('Posted by %s', $this->htmlLink($poll->getOwner(), $poll->getOwner()->getTitle())) ?>
            <?php echo $this->timestamp($poll->creation_date) ?>
            -
            <?php echo $this->translate(array('%s vote', '%s votes', $poll->voteCount()), $this->locale()->toNumber($poll->voteCount())) ?>
            -
            <?php echo $this->translate(array('%s view', '%s views', $poll->views), $this->locale()->toNumber($poll->views)) ?>
          </div>
          <?php if (!empty($poll->description)): ?>
            <div class="polls_browse_info_desc">
              <?php  echo $poll->description ?>
            </div>
          <?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; // $this->polls is NOT empty ?>
  <?php echo $this->paginationControl($this->paginator, null, null, null, array('poll_search'=>$this->search)); ?>
</div>
