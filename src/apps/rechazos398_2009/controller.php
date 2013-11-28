<?php
require_once(__DIR__.'/forms.php');

/**
 * Main App Class
 *
 * @author Cinxgler Mariaca
 */

class Rechazos398_2009App extends myGlueBase {
    /**
        @Get /
    */
    public function form() {
        $form = new RechazoForm();
        $view = new Zend_View();
        $data = array(
            "title" => 'Consultar las solicitudes rechazadas de devolución de valorización del acuerdo 398 del 2009',
            'form' => $form,
            'view' => $view,
        );
        $data = array_merge($data, $this->getFlash());
        echo glue("template")->render("views/search_form.php with views/layout.php", $data);
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
        $form = new RechazoForm();
        $data = array(
            "title" => 'Consultar las solicitudes rechazadas de devolución de valorización del acuerdo 398 del 2009',
            'form' => $form,
            'view' => $view,
            'rechazos' => false,
        );
        $found = false;
        if ($form->isValid($_POST)) {
            try {
                require_once(__DIR__.'/config.inc.php');
                glue("pdo")->addSource("default", array(
                    'dns'       => "$dbtype:host=$dbhost;dbname=$dbname",
                    'user'      => $dbuser,
                    'password'  => $dbpass,
                    'options'   => array()
                ));
                $values = $form->getValues();
                $result = glue("pdo")->src("default")->find(array(
                    'table' => $dbtable,
                    'conditions' => array(array('numero_documento = :num', array('num' => $values['document_number']))),
                ));
                if(!empty($result)) {
                    $data['rechazos'] = $result;
                }
                else {
                    $data['success_message'] = 'Su número de documento no fue encontrado en la base de datos de solicitudes rechazadas';
                }
            }
            catch(Exception $e) {
                $data['error_message'] = 'Ha ocurrido un error a atnciudadano@idu.gov.co. <!-- '.$e->getMessage().' -->';
            }
        } else {
            $data['warning_message'] = 'El formulario no pudó ser validado correctamente, por favor revise los datos ingresados.';
        }
        if(empty($data['rechazos'])) {
            echo glue("template")->render("views/search_form.php with views/layout.php", $data);
        }
        else {
            $tipo = $data['rechazos'][0]['rechazo']['tipo'];
            echo glue("template")->render("views/rechazo_$tipo.php with views/layout.php", $data);
        }
    }
}