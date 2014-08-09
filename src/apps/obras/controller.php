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
        $result = null;
        $informe_obra = null;
        try
        {
            $oe_informe = new OpenErpInformeObra($this->getOpenErpConnection());
            $result = $oe_informe->findByFrenteId((int)$_GET['frente_id']);
            if(!empty($result) && $result['status'] == 'success')
            {
                $informe_obra = $result['result'];
                $title = $informe_obra['name'];
                $data = array(
                    "title" => $title,
                    'view' => $view,
                    'informe_obra' => $informe_obra
                );
                $data = array_merge($data, $this->getFlash());
                echo glue("template")->render("views/informe_obra.php with views/layout.php", $data);
            }
            else
            {
                error_log($result['message']);
                echo glue("template")->render("views/informe_obra_error.php with views/layout.php", array());
            }
        }
        catch(Exception $e)
        {
            error_log($e->getMessage());
            echo glue("template")->render("views/informe_obra_error.php with views/layout.php", array());
        }

    }
}