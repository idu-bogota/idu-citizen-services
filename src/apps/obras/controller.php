<?php
/**
 * Main App Class
 *
 * @author Cinxgler Mariaca
 */

class ObrasApp extends myGlueBase {
    /**
        @Get /
    */
    public function map() {
        $view = new Zend_View();
        $data = array(
            "title" => 'Mapa de obras del IDU',
            'menu_item' => 'map',
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/map.php with views/layout.php", $data);
    }

    /**
        @Get /contrato_grupo.json
    */
    public function contrato_grupo_geojson() {
        header('Content-Type: application/json; charset=utf-8');
        $data = array();
        $url = "";
        $client = new SoapClient($url, array("trace" => 0, "exception" => 0));
        $result = $client->__soapCall("METODO", $parametros);
        echo json_encode($data);
    }
}