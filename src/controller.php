<?php
require_once(__DIR__.'/config.inc.php');

class App extends GlueBase {
    /**
        @Get /
    */
    public function index_get() {
        $config = new Zend_Config_Yaml( __DIR__.'/forms.yml');
        $form = new Zend_Form($config->pqr);
        $data = array(
            "title" => 'Reporte un daño en la malla vial',
            'form' => $form,
        );
        echo glue("template")->render("../views/map.php with ../views/layout.php", $data);
    }

    /**
        @Post /submit
    */
    public function submit() {
        
    }

    /**
        @Get /test/:name/:last
    */
    public function test($params) {
        echo $params['name'];
        echo $params['last'];
    }
}
