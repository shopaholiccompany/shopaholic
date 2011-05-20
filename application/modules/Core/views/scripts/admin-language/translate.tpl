<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: translate.tpl 7351 2010-09-10 23:40:10Z john $
 * @author     John
 */
?>

<?php if( $this->form ): ?>

  <script type="text/javascript">
    var url = '<?php echo $this->url(array('action' => 'translate-phrase')) ?>';
    var testTranslation = function() {
      (new Request.JSON({
        url : url,
        data : {
          format: 'json',
          source : $('source').value,
          target : $('target').value,
          text : $('test').value
        },
        onComplete : function(responseJSON) {
          if( $('test-translation') ) {
            $('test-translation').set('html', responseJSON.targetPhrase);
          } else {
            (new Element('p', {
              'id' : 'test-translation',
              'html' : responseJSON.targetPhrase
            })).inject($('test'), 'after');
          }

          console.log(responseJSON);
        }
      })).send();
    }
    window.addEvent('domready', function() {
      (new Element('a', {
        'href' : 'javascript:void(0);',
        'html' : 'Translate',
        'events' : {
          'click' : function() {
            testTranslation();
          }
        }
      })).inject($('test-element').getElement('p').empty());
      
    });
  </script>

  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>

<?php else: ?>

  <div>
    <h1>Source Locale:</h1>
    <?php echo $this->source ?>
  </div>
  <hr />

  <div>
    <h1>Target Locale:</h1>
    <?php echo $this->target ?>
  </div>
  <hr />

  <div>
    <h1>Files:</h1>
    <ul>
      <?php foreach( $this->files as $file ): ?>
        <li>
          <?php echo $file ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <hr />

  <div>
    <h1>Translated:</h1>
    <ul>
      <?php foreach( $this->successfullyTranslated as $key => $value ):
        $value = join('<hr />', (array) $value);
        ?>
        <li>
          <?php echo $key ?>
          <hr />
          <?php echo $value ?>
          <hr />
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <hr />

  <div>
    <h1>Failed Translation:</h1>
    <ul>
      <?php foreach( $this->unsuccessfullyTranslated as $key => $value ):
        $value = join('<hr />', (array) $value);
        ?>
        <li>
          <?php echo $key ?>
          <hr />
          <?php echo $value ?>
          <hr />
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <hr />

  <div>
    <h1>Previously Untranslated:</h1>
    <ul>
      <?php foreach( $this->previouslyUntranslated as $key => $value ):
        $value = join('<hr />', (array) $value);
        ?>
        <li>
          <?php echo htmlspecialchars($key) ?>
          <hr />
          <?php echo htmlspecialchars($value) ?>
          <hr />
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <hr />

<?php endif; ?>