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
        $form = new PqrForm();
        $view = new Zend_View();
        $data = array(
            "title" => 'Envíe un requerimiento al IDU',
            'menu_item' => 'new',
            'form' => $form,
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/new_form.php with views/layout.php", $data);
    }

    /**
        @Get /submit
    */
    public function submit() {
        header('Location: ./');
    }

    /**
        @Post /submit
    */
    public function save_post() {
        $view = new Zend_View();
        $form = new PqrForm();
        $data = array(
            "title" => 'Envíe un requerimiento al IDU',
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
                $flash_id = $this->setFlash('success', 'Requerimiento registrado exitosamente con número: '.$numero_radicado);
                header('Location: ./search?flash_id='.$flash_id);
                return;
            }
            catch(Exception $e) {
                $data['error_message'] = 'Ha ocurrido un error al guardar su PQR, por favor intentelo de nuevo o envie un email a atnciudadano@idu.gov.co. <!-- '.$e->getMessage().' -->';
            }
        } else {
            $data['warning_message'] = 'El formulario no pudó ser validado correctamente, por favor revise los datos ingresados.';
        }
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