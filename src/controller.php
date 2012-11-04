<?php
//************ External libraries ********
define ('EXTERNALS_DIR',__DIR__.'/../externals');
require_once(EXTERNALS_DIR.'/Glue/vendor/glue/glue.php');
require_once(EXTERNALS_DIR.'/openerp-php-webservice-client/src/OpenErpOcs.php');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend/library');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend-form-decorators-bootstrap');
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Twitter');
//************************************

require_once(__DIR__.'/forms.php');

class myOpenErpConnection {
    static $openerp_client = null;

    static function getConnection() {
        if(self::$openerp_client === null) {
            include(__DIR__.'/config.inc.php');
            self::$openerp_client = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);
        }
        return self::$openerp_client;
    }

}

class myGlueBase extends GlueBase {
    public function setFlash($type, $message, $flash_id) {
        $flash = array(
            $type.'_message' => $message,
        );
        $flash_id = md5("flash_id:".uniqid());
        glue("session")->write("flash.$flash_id", json_encode($flash));
        return $flash_id;
    }

    public function getFlash() {
        if(isset($_REQUEST['flash_id'])) {
            $flash_id = $_REQUEST['flash_id'];
            $flash_data = glue('session')->read("flash.$flash_id");
            glue('session')->delete("flash.$flash_id");
            if(!empty($flash_data)) {
                $data = json_decode($flash_data, true);
                return $data;
            }
        }
        return array();
    }
}

/**
 * Main App Class
 *
 * @author Cinxgler Mariaca
 */

class App extends myGlueBase {
    /**
        @Get /new
    */
    public function form() {
        $form = new PqrForm();
        $data = array(
            "title" => 'Reporte sus solicitudes, reclamos y sugerencias al IDU',
            'form' => $form,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("../views/map.php with ../views/layout.php", $data);
    }

    /**
        @Get /submit
    */
    public function submit() {
        header('Location: ./new');
    }

    /**
        @Post /submit
    */
    public function save_post() {
        $form = new PqrForm();
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
        );
        if ($form->isValid($_POST)) {
            try {
                $pqr = $form->buildObject();
                $numero_radicado = $pqr->create();
                $flash_id = $this->setFlash('success', 'PQR registrada exitosamente con número: '.$numero_radicado);
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

    /**
        @Get /list/geojson
    */
    public function list_geojson() {
        header('Content-Type: application/json; charset=utf-8');
        $pqr = new OpenErpPqr($this->getOpenErpConnection());
        $items = $pqr->fetch();
        $features = array();
        foreach ($items as $i) {
            if($feature = $i->getGeoJsonFeature(true)) {
                $features[] = $feature;
            }
        }
        $feature = array(
          'type' => 'FeatureCollection',
          'features' => $features,
        );
        echo json_encode($feature);
    }

    /**
        @Get /
    */
    public function list_html() {
        $data = array(
            "title" => 'Listado de solicitudes, reclamos y sugerencias al IDU',
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("../views/list.php with ../views/layout.php", $data);
    }

    protected function getOpenErpConnection() {
        return myOpenErpConnection::getConnection();
    }
}
