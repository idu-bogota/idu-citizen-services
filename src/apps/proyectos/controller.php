<?php
/**
 * Main App Class
 *
 * @author Cinxgler Mariaca
 */

class ProyectosApp extends myGlueBase {
    /**
        @Get /
    */
    public function map() {
        $view = new Zend_View();
        $data = array(
            "title" => 'Mapa de proyectos del IDU',
            'menu_item' => 'map',
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/map.php with views/layout.php", $data);
    }
}