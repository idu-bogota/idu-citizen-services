<?php

/**
 * PQR Form
 *
 * @author Andres Ignacio Baewz
 * require_once ROOT_PATH.'/apps/common/PqrForm.php';
 */

require_once ROOT_PATH.'/apps/common/ProblemaSocialBase.php';

class ProblemaSocialForm extends ProblemaSocialBaseForm {
    protected $add_submit_button_on_captcha = false;
    protected $form_name = 'problema_social';
    protected $forward_fieldmap = array('tipo_problema', 'tipo_problema_movilidad', 'imagen', 'shape','descripcion');
    protected $description_fieldmap = array('description','address','claim_address');

    public function buildObject() {	
        $psocial = parent::buildObject();
        include(__DIR__.'/config.inc.php');
        $config_map = array();
        //$config_map = array('categ_id','classification_id','sub_classification_id','csp_id','channel');
        foreach($config_map as $f) {
            $psocial->attributes[$f] = $$f;
        }
        //attach file
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->receive();
        $fname_uploaded = $upload->getFileName();
        if(!empty($fname_uploaded)) {
            $filemd5 = md5_file($fname_uploaded);
            $filesize = filesize ($fname_uploaded);
            $file_info = $upload->getFileInfo();
            $file_encode64 = base64_encode(file_get_contents($fname_uploaded));
            $psocial->attributes['attachment'] = $file_encode64;
            $psocial->attributes['attachment_name'] = $file_info['image']['name'];
        }
        return $psocial;
    }

    /**
    *  Genera el thumbnail de la imagen subida por el usuario
    */
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
