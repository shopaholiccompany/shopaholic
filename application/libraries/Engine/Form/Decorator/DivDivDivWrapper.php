<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Form
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: DivDivDivWrapper.php 7371 2010-09-14 03:33:35Z john $
 */

/**
 * @category   Engine
 * @package    Engine_Form
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Engine_Form_Decorator_DivDivDivWrapper extends Zend_Form_Decorator_Abstract
{
    /**
     * Default placement: surround content
     * @var string
     */
    protected $_placement = null;

    /**
     * Render
     *
     * Renders as the following:
     * <dt></dt>
     * <dd>$content</dd>
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $elementName = $this->getElement()->getName();

        return
          '<div id="' . $elementName . '-wrapper" class="form-wrapper">'.
          '<div id="' . $elementName . '-label" class="form-label">&nbsp;</div>' .
          '<div id="' . $elementName . '-element" class="form-element">' . $content . '</div>'.
          '</div>';
    }
}
