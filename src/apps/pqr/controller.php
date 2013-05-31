<?php
require_once(__DIR__.'/forms.php');

/**
 * Main App Class
 *
 * @author Cinxgler Mariaca
 */

class PqrApp extends myGlueBase {
    /**
        @Get /
    */
    public function form() {
        $data = array(
            "title" => 'Envíe un requerimiento al IDU',
            'menu_item' => 'new',
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/new_form.php with views/layout.php", $data);
    }
    /**
        @Get /search
    */
    public function search() {
        $data = array(
            "title" => 'Consulte el estado de su requerimiento',
            'menu_item' => 'search',
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/search_form.php with views/layout.php", $data);
    }
}