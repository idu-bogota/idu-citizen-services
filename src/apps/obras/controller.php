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
    public function informe_obra() {
        $view = new Zend_View();
        $oe_informe = new OpenErpInformeObra($this->getOpenErpConnection());
        $informe_obra = $oe_informe->findByFrenteId((int)$_GET['frente_id']);
        $title = var_export($informe_obra);
        $data = array(
            "title" => $title,
            'menu_item' => 'map',
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/map.php with views/layout.php", $data);
    }
}