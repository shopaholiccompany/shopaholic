<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: Search.php 7373 2010-09-14 04:49:43Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class User_Form_Search extends Fields_Form_Search
{
  public function init()
  {
    parent::init();

    $this->loadDefaultDecorators();

    $this->getDecorator('HtmlTag')->setOption('class', 'browsemembers_criteria');

    // Generate
    $this->generate();

    // Add custom elements
    $this->getMemberTypeElement();
    $this->getDisplayNameElement();
    $this->getAdditionalOptionsElement();
  }

  public function getMemberTypeElement()
  {
    $multiOptions = array('' => ' ');
    $profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias($this->_fieldType, 'profile_type');
    if( count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']) ) return;

    $options = $profileTypeFields['profile_type']->getOptions();
    if( count($options) <= 1 ) {
      return;
    }

    foreach( $options as $option ) {
      $multiOptions[$option->option_id] = $option->label;
    }

    $this->addElement('Select', 'profile_type', array(
      'label' => 'Member Type',
      'order' => -1000001,
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => 'span')),
        array('HtmlTag', array('tag' => 'li'))
      ),
      'multiOptions' => $multiOptions,
    ));
    return $this->profile_type;
  }

  public function getDisplayNameElement()
  {
    $this->addElement('Text', 'displayname', array(
      'label' => 'Name',
      'order' => -1000000,
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => 'span')),
        array('HtmlTag', array('tag' => 'li'))
      ),
      //'onkeypress' => 'return submitEnter(event)',
    ));
    return $this->displayname;
  }

  public function getAdditionalOptionsElement()
  {
    $subform = new Zend_Form_SubForm(array(
      'name' => 'extra',
      'order' => 1000000,
      'decorators' => array(
        'FormElements',
      )
    ));
    Engine_Form::enableForm($subform);

    $subform->addElement('Checkbox', 'has_photo', array(
      'label' => 'Only Members With Photos',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
        array('HtmlTag', array('tag' => 'li'))
      ),
    ));

    $subform->addElement('Checkbox', 'is_online', array(
      'label' => 'Only Online Members',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
        array('HtmlTag', array('tag' => 'li'))
      ),
    ));

    $subform->addElement('Button', 'done', array(
      'label' => 'Search',
      'onclick' => 'javascript:searchMembers()',
      'ignore' => true,
    ));

    $this->addSubForm($subform, $subform->getName());

    return $this;
  }
}