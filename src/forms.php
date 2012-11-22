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
 * PQR Form
 *
 * @author Cinxgler Mariaca
 */

class PqrForm extends BaseForm {
    public $object = null;

    public function init() {
        $config = new Zend_Config_Yaml( __DIR__.'/forms.yml');
        $this->setConfig($config->pqr);
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->getElement('category')->addMultiOptions($this->retrieveCategoryOptions());
        $this->getElement('classification')->addMultiOptions($this->retrieveClassificationOptions());
        $document_type = array(
            'C' => 'Cédula de ciudadanía',
            'T' => 'Tarjeta de identidad',
            'P' => 'Pasaporte',
            'E' => 'Cédula de extranjería'
        );
        $this->getElement('document_type')->addMultiOptions($document_type);
    }

    protected function retrieveCategoryOptions(){
        $c = $this->getOpenErpConnection();
        $oerp = new OpenErpOcsCategory($c);
        $items = $oerp->fetch(array(array('active','=',True)));
        $options = array();
        foreach($items as $obj) {
            $atts = $obj->attributes;
            $options[$atts['id']] = $atts['name'];
        }
        return $options;
    }

    protected function retrieveClassificationOptions(){
        $items = $this->retrieveClassificationObjects();
        $options = array();
        foreach($items as $obj) {
            $atts = $obj->attributes;
            $options[$atts['id']] = $atts['name'];
        }
        return $options;
    }

    protected function retrieveClassificationObjects($in_array = false) {
        $c = $this->getOpenErpConnection();
        $oerp = new OpenErpOcsClassification($c);
        $objects = $oerp->fetch(array(array('is_portal_visible','=',True)));
        if(!$in_array) return $objects;
        $data = array();
        foreach($objects as $obj) {
            $data[$obj->id] = $obj->attributes;
        }
        return $data;
    }

    public function buildObject(){
        $c = $this->getOpenErpConnection();
        $values = $this->getValues();
        $pqr = new myOpenErpPqr($c);
        $attributes = array();
        if( !empty($values['name']) && !empty($values['lastname']) ) {
            $citizen = array(
                'name' => $values['name'],
                'last_name' => $values['lastname']
            );
            if(!empty($values['document_number'])) {
                $citizen['document_type'] = $values['document_type'];
                $citizen['document_number'] = $values['document_number']; #FIXME: cambiar a document_number en OpenErp
            }
            $contact_map = array('email','twitter','facebook','phone','address');
            foreach($contact_map as $f) {
                if( !empty($values[$f]) ) {
                    $citizen[$f] = $values[$f];
                }
            }
            $attributes['partner_address_id'] = $citizen;
        }

        $classification_id = 1; //fallback
        $classifications = $this->retrieveClassificationObjects(true);
        if( is_array($classifications[$values['classification']]['parent_id']) ) {
            $classification_id = $classifications[$values['classification']]['parent_id'][0];
        }
        $attributes['categ_id'] = $values['category'];
        $attributes['classification_id'] = $classification_id;
        $attributes['sub_classification_id'] = $values['classification'];
        $attributes['csp_id'] = 1;//FIXME: definir cual es el csp_id
        $attributes['external_dms_id'] = '0';
        $attributes['priority'] = 'l';
        $attributes['description'] = $values['description'];
        $attributes['state'] = 'pending';
        $attributes['channel'] = 1;//FIXME: definir cual es el channel
        $attributes['geo_point'] = $values['geo_point'];
        if(!empty($values['email'])) $attributes['email_from'] = $values['email'];
        if(!empty($values['phone'])) $attributes['partner_phone'] = $values['phone'];
        $pqr->attributes = $attributes;
        $this->object = $pqr;
        return $pqr;
    }

    public function saveImage() {
        require_once(EXTERNALS_DIR.'/phpthumb/src/ThumbLib.inc.php');
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->receive();
        if( $upload->isUploaded() ) {
            $filename = $this->object->getFullFilename();
            $thumb_fname = $this->object->getFullThumbFilename();
            $filter_rename = new Zend_Filter_File_Rename(array('target' => $filename, 'overwrite' => true));
            $filter_rename->filter($upload->getFileName());
            $thumb = PhpThumbFactory::create($filename);
            $thumb->adaptiveResize(64, 64);
            $thumb->save($thumb_fname);
        }
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