<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: error.tpl 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */
?>

<div>

  <h2><?php echo $this->translate('Whoops!') ?></h2>

  <?php echo $this->translate('An error has occurred.') ?>

  <?php if( isset($this->error) ): ?>
    <br />
    <br />
    <pre><?php echo $this->error; ?></pre>
  <?php endif; ?>

</div>