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
            $openerp_server = glue("config")->read('openerp_server');
            $username = glue("config")->read('username');
            $pwd = glue("config")->read('pwd');
            $dbname = glue("config")->read('dbname');
            self::$openerp_client = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);
        }
        return self::$openerp_client;
    }
}

class myOpenErpPqr extends OpenErpPqr {
    public function getFilename() {
        return 'pqr-'.$this->id;
    }

    public function getFullFilename() {
        $path = glue("config")->read('attachment_path');
        return $path.'/'.$this->getFilename();
    }
    public function getFullThumbFilename() {
        $path = glue("config")->read('attachment_path');
        return $path.'/thumb/'.$this->getFilename();
    }

    public function imageExists() {
        return file_exists($this->getFullFilename());
    }

    public function imageThumbExists() {
        return file_exists($this->getFullThumbFilename());
    }

    public function getImageUrl() {
        if($this->imageExists()) {
          $path = glue("config")->read('attachment_base_url');
          return $path.'/'.$this->getFilename();
        }
        return 'http://placehold.it/150&text=Sin+imagen';
    }

    public function getImageThumbUrl() {
        if($this->imageThumbExists()) {
          $path = glue("config")->read('attachment_base_url');
          return $path.'/thumb/'.$this->getFilename();
        }
        return 'http://placehold.it/64&text=Sin+imagen';
    }
}

class myGlueBase extends GlueBase {
    public function __construct() {
        parent::__construct();
        include(__DIR__.'/config.inc.php');
        glue("config")->write('openerp_server', $openerp_server);
        glue("config")->write('username', $username);
        glue("config")->write('pwd', $pwd);
        glue("config")->write('dbname', $dbname);
        glue("config")->write('attachment_base_url', $attachment_base_url);
        glue("config")->write('attachment_path', $attachment_path);
    }

    public function setFlash($type, $message, $flash_id = null) {
        $flash = array(
            $type.'_message' => $message,
        );
        if($flash_id === null) {
            $flash_id = md5("flash_id:".uniqid());
        }
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
        @Get /simple
    */
    public function form() {
        $form = new PqrForm();
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
            'menu_item' => 'new',
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("../views/map.php with ../views/layout.php", $data);
    }
    /**
        @Get /new
    */
    public function wizard() {
        $view = new Zend_View();
        $form = new PqrForm(array('view' => $view));
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
            'menu_item' => 'new',
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("../views/wizard.php with ../views/layout.php", $data);
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
        $view = new Zend_View();
        $form = new PqrForm();
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
            'menu_item' => 'new',
            'view' => $view,
        );
        if ($form->isValid($_POST)) {
            try {
                $pqr = $form->buildObject();
                $numero_radicado = $pqr->create();
                $flash_id = $this->setFlash('success', 'PQR registrada exitosamente con número: '.$numero_radicado);
                try {
                    $form->saveImage();
                }
                catch (Exception $e) {
                    $flash_id = $this->setFlash('error', 'El archivo anexo no pudo ser almacenado '.$e->getMessage(), $flash_id);
                }
                header('Location: ./?flash_id='.$flash_id);
                return;
            }
            catch(Exception $e) {
                $data['error_message'] = 'Ha ocurrido un error al guardar su PQR, por favor intentelo de nuevo o envie un email a atnciudadano@idu.gov.co. <!-- '.$e->getMessage().' -->';
            }
        } else {
            $data['warning_message'] = 'El formulario no pudó ser validado correctamente, por favor revise los datos ingresados.';
        }
        echo glue("template")->render("../views/wizard.php with ../views/layout.php", $data);
    }

    /**
        @Get /list.geojson
    */
    public function list_geojson() {
        header('Content-Type: application/json; charset=utf-8');
        $pqr = new myOpenErpPqr($this->getOpenErpConnection());
        $items = $pqr->fetch(array(),0,100);
        $features = array();
        foreach ($items as $i) {
            if($feature = $i->getGeoJsonFeature(true)) {
                $feature['properties']['image_url'] = $i->getImageUrl();
                $feature['properties']['thumb_image_url'] = $i->getImageThumbUrl();
                $features[] = $feature;
            }
        }
        $feature = array(
          'type' => 'FeatureCollection',
          'features' => $features
        );
        echo json_encode($feature);
    }

    /**
        @Get /
    */
    public function list_html() {
        $data = array(
            "title" => 'Listado de reportes de daño de la malla vial y el espacio público',
            'menu_item' => 'list',
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("../views/list.php with ../views/layout.php", $data);
    }

    protected function getOpenErpConnection() {
        return myOpenErpConnection::getConnection();
    }
}
