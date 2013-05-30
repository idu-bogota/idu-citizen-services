<?php

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
                $citizen['document_number'] = $values['document_number'];
            }
            $contact_map = array('email','twitter','facebook','phone','gender');
            foreach($contact_map as $f) {
                if( !empty($values[$f]) ) {
                    $citizen[$f] = $values[$f];
                }
            }
            $attributes['partner_address_id'] = $citizen;
        }

        include(ROOT_PATH.'/config.inc.php');
        $config_map = array('categ_id','classification_id','sub_classification_id','csp_id','channel');
        foreach($config_map as $f) {
            $attributes[$f] = $$f;
        }
        $attributes['orfeo_id'] = '0';
        $attributes['priority'] = 'l';
        $attributes['state'] = 'pending';
        $desc_map = array('description','damage_type_by_citizen', 'damage_width_by_citizen', 'damage_length_by_citizen', 'damage_deep_by_citizen','geo_point');
        foreach($desc_map as $f) {
            $attributes[$f] = $values[$f];
        }
        //No almacenados en los campos de OpenERP ya que las reglas de validación de formato de dirección lo impiden.
        if(!empty($values['address'])) $attributes['description'] .= sprintf("\n\n-- Dirección de contacto: %s",$values['address']);
        if(!empty($values['claim_address'])) $attributes['description'] .= sprintf("\n\n-- Dirección del daño: %s",$values['claim_address']);

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
        $fname_uploaded = $upload->getFileName();
        if(!empty($fname_uploaded)) {
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
