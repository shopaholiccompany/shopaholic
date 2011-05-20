<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Poll
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: view.tpl 7244 2010-09-01 01:49:53Z john $
 * @author     Steve
 */
?>

<?php if (empty($this->poll)): ?>
  <?php echo $this->translate('The poll you are looking for does not exist or has been deleted.') ?>
<?php return; endif; ?>
<h2>
  <?php echo $this->translate('%s\'s Polls', $this->htmlLink($this->owner, $this->owner->getTitle())) ?>
</h2>

<div class="layout_middle">
<div class='polls_view'>

  <form action="<?php echo $this->url() ?>" method="POST" onsubmit="return false;">
    <h3>
      <?php echo $this->poll->title ?>
    </h3>
    <div class="poll_desc">
      <?php echo $this->poll->description ?>
    </div>
    <ul id="poll_options" class="poll_options">
      <?php foreach ($this->pollOptions as $i => $option): ?>
      <li id="poll_item_option_<?php echo $option->poll_option_id ?>">
        <div class="poll_has_voted" <?php echo ($this->hasVoted?'':'style="display:none;"') ?>>
          <div class="poll_option">
            <?php echo $option->poll_option ?>
          </div>
          <?php $pct = $this->votes
                     ? floor(100*($option->votes/$this->votes))
                     : 0;
                if (!$pct)
                  $pct = 1;
                // NOTE: poll-answer graph & text is actually rendered via
                // javascript.  The following HTML is there as placeholders
                // and for javascript backwards compatibility (though
                // javascript is required for voting).
           ?>
          <div id="poll-answer-<?php echo $option->poll_option_id ?>" class='poll_answer poll-answer-<?php echo (($i%8)+1) ?>' style='width: <?php echo .7*$pct; // set width to 70% of its real size to as to fit text label too ?>%;'>
            &nbsp;
          </div>
          <div class="poll_answer_total">
            <?php echo $this->translate(array('%s vote', '%s votes', $option->votes), $this->locale()->toNumber($option->votes)) ?>
            (<?php echo $this->locale()->toNumber($option->votes ? $pct : 0) ?>%)
          </div>
        </div>
        <div class="poll_not_voted" <?php echo ($this->hasVoted?'style="display:none;"':'') ?> >
          <div class="poll_radio" id="poll_radio_<?php echo $option->poll_option_id ?>">
            <input id="poll_option_<?php echo $option->poll_option_id ?>" 
                   type="radio" name="poll_options" value="<?php echo $option->poll_option_id ?>"
                   <?php if ($this->hasVoted == $option->poll_option_id): ?>checked="true"<?php endif; ?>
                   <?php if ($this->hasVoted && !$this->canChangeVote): ?>disabled="true"<?php endif; ?>
                   />
          </div>
          <label for="poll_option_<?php echo $option->poll_option_id ?>">
            <?php echo $option->poll_option ?>
          </label>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <div class="poll_stats">
      <a href='javascript:void(0);' onClick='togglePollResults(); this.blur();' id="poll_toggleResultsLink">
        <?php echo $this->translate($this->hasVoted ? 'Show Questions' : 'Show Results' ) ?></a>
        &nbsp;|&nbsp; <?php echo $this->htmlLink(Array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'poll', 'id'=>$this->poll->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox')); ?>
        &nbsp;|&nbsp; <?php echo $this->htmlLink(Array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=>$this->poll->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox')); ?>
        &nbsp;|&nbsp; <span id="poll_vote_total"><?php echo $this->translate(array('%s vote', '%s votes', $this->votes), $this->locale()->toNumber($this->votes)) ?></span>
        &nbsp;|&nbsp; <?php echo $this->translate(array('%s view', '%s views', $this->poll->views), $this->locale()->toNumber($this->poll->views)) ?>
    </div>
  </form>

  <?php echo $this->action("list", "comment", "core", array("type"=>"poll", "id"=>$this->poll->poll_id)) ?>

</div>
</div>


<script type="text/javascript">
//<![CDATA[
var togglePollResults = function() {
  if ('none' == $$('#poll_options div.poll_has_voted')[0].getStyle('display')) {
    $$('#poll_options div.poll_has_voted').show();
    $$('#poll_options div.poll_not_voted').hide();
    $('poll_toggleResultsLink').set('text', '<?php echo $this->translate('Show Questions') ?>');
  } else {
    $$('#poll_options div.poll_has_voted').hide();
    $$('#poll_options div.poll_not_voted').show();
    $('poll_toggleResultsLink').set('text', '<?php echo $this->translate('Show Results') ?>')
  }
}
var renderPollResults = function (pollAnswers, poll_votes_total) {
    if (pollAnswers && 'array' == $type(pollAnswers)) {
        pollAnswers.each(function(option) {
            var div = $('poll-answer-'+option.poll_option_id);
            var pct = poll_votes_total > 0
                    ? Math.floor(100*(option.votes / poll_votes_total))
                    : 1;
            if (pct < 1)
                pct = 1;
            // set width to 70% of actual width to fit text on same line
            div.style.width = (.7*pct)+'%';
            div.getNext('div.poll_answer_total')
               .set('text',  option.votesTranslated + ' ('+(option.votes?pct:'0')+'%)');
            <?php if (!$this->canChangeVote && $this->hasVoted): ?>
              $$('.poll_radio input').set('disabled', true);
            <?php endif ?>
        });
    }
}
<?php if ($this->viewer_id): ?>
var pollVote = function(el) {
  var url = '<?php echo $this->url(array(), 'poll_vote') ?>';
  var poll_id = '<?php echo $this->poll->getIdentity() ?>';
  new Request.JSON({
    url: url,
    method: 'post',
    onRequest: function() {
      $('poll_radio_' + el.value).toggleClass('poll_radio_loading');
    },
    onComplete: function(responseJSON) {
      $('poll_radio_' + el.value).toggleClass('poll_radio_loading');
      if ($type(responseJSON)=='object' && responseJSON.error) {
        Smoothbox.open( new Element('div', {
          'html': responseJSON.error + '<br /><br /><?php echo $this->formButton('Close', 'Close', array('onclick'=>'parent.Smoothbox.close()')) ?>'
        }));
      } else {
        $('poll_vote_total').set('text', responseJSON.votes_total+' vote'+(responseJSON.votes_total==1?'':'s'));
        renderPollResults(responseJSON.pollOptions, responseJSON.votes_total);        
        togglePollResults();
      }
      <?php if (!$this->canChangeVote): ?>$$('.poll_radio input').set('disabled', true);<?php endif; ?>
    }
  }).send('format=json&poll_id='+poll_id+'&option_id='+el.value)
}

<?php else: ?>
var pollVote = function(el) {
  window.location.href = '<?php echo $this->url(array(), 'user_login') ?>';
}
<?php endif; ?>

en4.core.runonce.add(function(){
  $$('.poll_radio input').addEvent('click', function(){ pollVote(this); });
});

//]]>
</script>