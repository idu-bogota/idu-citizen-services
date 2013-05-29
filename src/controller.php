<?php
//************ External libraries ********
define ('EXTERNALS_DIR',__DIR__.'/../externals');
require_once(EXTERNALS_DIR.'/Glue/vendor/glue/glue.php');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend/library');
ini_set('include_path', get_include_path().PATH_SEPARATOR.EXTERNALS_DIR.'/zend-form-decorators-bootstrap');
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Twitter');
//************************************

require_once(__DIR__.'/forms.php');
require_once(__DIR__.'/includes/openerp.php');
require_once(__DIR__.'/includes/helper.php');
require_once(__DIR__.'/includes/myGlue.php');

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
                $result = $pqr->create();
                if($result['status'] == 'success') {
                    $numero_radicado = $result['result']['id'];
                }
                else {
                    throw new Exception($result['message']);
                }
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

    /**
        @Get /geocode
    */
    public function geocode() {
        header('Content-Type: application/json; charset=utf-8');
        $service_url = glue("config")->read('geocoding_service_url');
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'Street' => $_GET['address'],
            'Zone' => '11001000',
            'f' => 'pjson',
            'outSR' => '4326'
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($curl_response, true);
        $result = array();
        if(sizeof($response['candidates'])) {
            $result = array(
                'address' => $response['candidates'][0]['address'],
                'position' => array(
                    'coords' => array(
                        'longitude' => $response['candidates'][0]['location']['x'],
                        'latitude' => $response['candidates'][0]['location']['y'],
                    )
                )
            );
        }
        echo json_encode($result);
    }
}
