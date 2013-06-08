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
        return $pqr;
    }
}

class PqrSearchForm extends BasePqrForm {
    protected $form_name = 'pqr_search';
}