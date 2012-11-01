<?php
define ('EXTERNALS_DIR',__DIR__.'/../externals');
require_once(EXTERNALS_DIR.'/Glue/vendor/glue/glue.php');
require_once(EXTERNALS_DIR.'/openerp-php-webservice-client/src/OpenErpOcs.php');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend/library');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend-form-decorators-bootstrap');
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Twitter');


class BaseForm extends Twitter_Bootstrap_Form_Horizontal {}
class PqrForm extends BaseForm {
    public function init() {
        $config = new Zend_Config_Yaml( __DIR__.'/forms.yml');
        $this->setConfig($config->pqr);
        include(__DIR__.'/config.inc.php');
        $c = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);

        $oerp = new OpenErpOcsCategory($c);
        $items = $oerp->fetch(array(array('active','=',True)));
        $options = array();
        foreach($items as $obj) {
            $atts = $obj->attributes;
            $options[$atts['id']] = $atts['name'];
        }
        $this->getElement('category')->addMultiOptions($options);

        $oerp = new OpenErpOcsClassification($c);
        $items = $oerp->fetch(array(array('is_portal_visible','=',True)));
        $options = array();
        foreach($items as $obj) {
            $atts = $obj->attributes;
            $options[$atts['id']] = $atts['name'];
        }
        $this->getElement('classification')->addMultiOptions($options);
    }
}

class App extends GlueBase {
    /**
        @Get /
    */
    public function index() {
        $form = new PqrForm();
        $data = array(
            "title" => 'Reporte de daño en la malla vial',
            'form' => $form,
        );
        if(isset($_REQUEST['flash_id'])) {
            $flash_id = $_REQUEST['flash_id'];
            $flash_data = glue('session')->read("flash.$flash_id");
            if(!empty($flash_data)) {
                $data = array_merge($data,json_decode($flash_data, true));
            }
            glue('session')->delete("flash.$flash_id");
        }
        echo glue("template")->render("../views/map.php with ../views/layout.php", $data);
    }

    /**
        @Post /submit
    */
    public function submit() {
        include(__DIR__.'/config.inc.php');
        $config = new Zend_Config_Yaml( __DIR__.'/forms.yml');
        $form = new BaseForm($config->pqr);
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
        );
        if ($form->isValid($_POST)) {
            $values = $form->getValues();
            $c = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);
            $pqr = new OpenErpPqr($c);
            $pqr->attributes = array(
                'partner_address_id' =>  array(
                    'name' => $values['name'],
                    'document_type' => $values['document_type'],
                    'document_id' => $values['document_number'],
                    'last_name' => $values['lastname'],
                    'email' => $values['email'],
                ),
                'categ_id' => array('name' => $values['category']),
                'classification_id' => array('name' => $values['classification']),
                'sub_classification_id' => array('name' => $values['classification']),
                'csp_id' => array('name' => 'none'),
                'external_dms_id' => '0',
                'priority' => 'l',
                'description' => $values['description'],
                'state' => 'pending',
                'channel' => array('name' => 'web'),
                'geo_point' => $values['geo_point'],
            );
            try {
                $numero_radicado = $pqr->create();
                $flash = array(
                    'success_message' => 'PQR registrada exitosamente con número: '.$numero_radicado,
                );
                $flash_id = md5("flash_id:$numero_radicado");
                glue("session")->write("flash.$flash_id", json_encode($flash));
                header('Location: ./?flash_id='.$flash_id);
                return;
            }
            catch(Exception $e) {
                $data['error_message'] = 'Ha ocurrido un error al guardar su PQR, por favor intentelo de nuevo o envie un email a atnciudadano@idu.gov.co. <!-- '.$e->getMessage().' -->';
            }
        } else {
            $data['warning_message'] = 'El formulario no pudó ser validado correctamente, por favor revise los datos ingresados.';
        }
        echo glue("template")->render("../views/map.php with ../views/layout.php", $data);
    }
}