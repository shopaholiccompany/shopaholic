<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: browse.tpl 7292 2010-09-04 23:26:38Z john $
 * @author     Jung
 */
?>

<h2><?php echo $this->translate('Browse Members');?></h2>

<div class='layout_left'>
  <?php echo $this->form->render($this) ?>
</div>

<div class='layout_middle'>
  <div class='browsemembers_results' id='browsemembers_results'>
      <?php echo $this->render('_browseUsers.tpl') ?>
  </div>
</div>

<script type="text/javascript">
  var url = '<?php echo $this->url() ?>';
  var requestActive = false;
  var browseContainer, formElement, page, totalUsers, userCount, currentSearchParams;

  window.addEvent('load', function() {
    formElement = $$('.field_search_criteria')[0];
    browseContainer = $('browsemembers_results');

    // On search
    formElement.addEvent('submit', function(event) {
      event.stop();
      searchMembers();
    });
  });

  var searchMembers = function() {
    if( requestActive ) return;
    requestActive = true;

    currentSearchParams = formElement.toQueryString();

    var param = (currentSearchParams ? currentSearchParams + '&' : '') + 'ajax=1&format=html';

    var request = new Request.HTML({
      url: url,
      onComplete: function(requestTree, requestHTML) {
        requestTree = $$(requestTree);
        browseContainer.empty();
        requestTree.inject(browseContainer);
        requestActive = false;
        Smoothbox.bind();
      }
    });
    request.send(param);
  }

  var browseMembersViewMore = function() {
    if( requestActive ) return;
    $('browsemembers_loading').setStyle('display', '');
    $('browsemembers_viewmore').setStyle('display', 'none');

    var param = (currentSearchParams ? currentSearchParams + '&' : '') + 'ajax=1&format=html&page=' + (parseInt(page) + 1);

    var request = new Request.HTML({
      url: url,
      onComplete: function(requestTree, requestHTML) {
        requestTree = $$(requestTree);
        browseContainer.empty();
        requestTree.inject(browseContainer);
        requestActive = false;
        Smoothbox.bind();
      }
    });
    request.send(param);
  }
</script>
