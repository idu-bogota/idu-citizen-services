<?php

/**
 * PQR Form
 *
 * @author Cinxgler Mariaca
 */
require_once ROOT_PATH.'/apps/common/PqrForm.php';

class PqrForm extends BasePqrForm {
    protected $form_name = 'pqr';

    public function buildObject() {
        $pqr = parent::buildObject();
        include(__DIR__.'/config.inc.php');
        $config_map = array('sub_classification_id','csp_id','channel');
        foreach($config_map as $f) {
            $pqr->attributes[$f] = $$f;
        }
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->receive();
        $fname_uploaded = $upload->getFileName();
        if(!empty($fname_uploaded)) {
            $filemd5 = md5_file($fname_uploaded);
            $filesize = filesize ($fname_uploaded);
            $file_info = $upload->getFileInfo();
            $file_encode64 = base64_encode(file_get_contents($fname_uploaded));
            $pqr->attributes['attachment'] = $file_encode64;
            $pqr->attributes['attachment_name'] = $file_info['attachment']['name'];
            $pqr->attributes['description'] .= sprintf("\n\n------\nSe adjunta archivo: %s\n - Tamaño en bytes: %s\n - MD5 Hash: %s\n",
                $pqr->attributes['attachment_name'], $filesize, $filemd5
            );
        }
        $values = $this->getValues();
        $pqr->attributes['description'] .= sprintf("\n\n------\nCanal de respuesta seleccionado: %s\n", $values['canal_respuesta']);
        return $pqr;
    }
}

class PqrSearchForm extends BasePqrForm {
    protected $form_name = 'pqr_search';
}