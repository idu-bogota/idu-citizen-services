<?php

/**
 * PQR Form
 *
 * @author Cinxgler Mariaca
 */
require_once ROOT_PATH.'/apps/common/PqrForm.php';

class GeoPqrForm extends BasePqrForm {
    protected $form_name = 'geo_pqr';
    protected $description_fieldmap = array('description','address','claim_address','damage_type_by_citizen', 'damage_width_by_citizen', 'damage_length_by_citizen', 'damage_deep_by_citizen','geo_point');

    public function buildObject() {
        $pqr = parent::buildObject();
        include(__DIR__.'/config.inc.php');
        $config_map = array('categ_id','classification_id','sub_classification_id','csp_id','channel');
        foreach($config_map as $f) {
            $pqr->attributes[$f] = $$f;
        }
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
