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
                $obfuscated = myObfuscator::obfuscate($numero_radicado);
                $flash_id = $this->setFlash('success', 'Requerimiento registrado exitosamente con número: '.$numero_radicado.' la contraseña para consultarlo es:'.$obfuscated);
                header("Location: ".$_SERVER['SCRIPT_NAME']."/requerimiento/$obfuscated?flash_id=$flash_id");
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
            "form" => new PqrSearchForm(),
            "title" => 'Consulte el estado de su requerimiento',
            'menu_item' => 'search',
            'view' => new Zend_View(),
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/search_form.php with views/layout.php", $data);
    }

    /**
        @Post /search
    */
    public function search_post() {
        #FIXME: this isn't a strong method to authenticate
        $id = myObfuscator::deobfuscate($_POST['password']);
        if($id == $_POST['number']) {
            header("Location: ".$_SERVER['SCRIPT_NAME']."/requerimiento/".$_POST['password']);
        }
        else {
            $flash_id = $this->setFlash('error', 'Los datos ingresados son erroneos');
            header("Location: ".$_SERVER['SCRIPT_NAME']."/search?flash_id=$flash_id");
        }
    }

    /**
        @Get /requerimiento/:id
    */
    public function load($params) {
        $c = $this->getOpenErpConnection();
        $pqr = new OpenErpPqr($c);
        $id = myObfuscator::deobfuscate($params['id']);
        $pqr->loadOne($id);
        $data = array(
            "title" => 'Detalles de su requerimiento',
            'menu_item' => 'search',
            'pqr' => $pqr,
        );
        if($pqr->attributes == false) {
            header('HTTP/1.0 404 Not Found');
            echo glue("template")->render("views/pqr_not_found.php with views/layout.php", $data);
            return;
        }
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/pqr_display.php with views/layout.php", $data);
    }
}