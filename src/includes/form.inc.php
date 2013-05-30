<?php
/**
 * Base Form for the php script it contain methods to connect to openerp to retrieve data
 *
 * @author Cinxgler Mariaca
 */

class BaseForm extends Zend_Form  { //Twitter_Bootstrap_Form_Vertical //Twitter_Bootstrap_Form //Zend_Form
    protected function getOpenErpConnection() {
        return myOpenErpConnection::getConnection();
    }
}

/**
 * Form note element
 *
 * @author Ruslan Zavackiy <ruslan.zavackiy@gmail.com>
 * @package elements
 */
class Zend_Form_Element_Note extends Zend_Form_Element_Xhtml
{
    public $helper = 'formNote';
}