<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: type-edit.tpl 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */
?>
<?php if( $this->form ): ?>

  <?php echo $this->form->render($this) ?>

<?php else: ?>

  <div>
    <?php echo $this->translate("Changes saved.") ?>
  </div>

  <script type="text/javascript">
    (function() {
      parent.onTypeEdit(
        <?php echo Zend_Json::encode($this->option) ?>
      );
      parent.Smoothbox.close();
    }).delay(1000);
  </script>

<?php endif; ?>