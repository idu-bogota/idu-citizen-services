<?php
class myGlueBase extends GlueBase {
    public function __construct() {
        parent::__construct();
        include(__DIR__.'/../config.inc.php');
        glue("config")->write('openerp_server', $openerp_server);
        glue("config")->write('username', $username);
        glue("config")->write('pwd', $pwd);
        glue("config")->write('dbname', $dbname);
        glue("config")->write('attachment_base_url', $attachment_base_url);
        glue("config")->write('attachment_path', $attachment_path);
        glue("config")->write('geocoding_service_url', $geocoding_service_url);
        glue("config")->write('recaptcha_pubkey', $recaptcha_pubkey);
        glue("config")->write('recaptcha_privkey', $recaptcha_privkey);
    }

    protected function getOpenErpConnection() {
        return myOpenErpConnection::getConnection();
    }

    public function setFlash($type, $message, $flash_id = null) {
        $flash = array(
            $type.'_message' => $message,
        );
        if($flash_id === null) {
            $flash_id = md5("flash_id:".uniqid());
        }
        glue("session")->write("flash.$flash_id", json_encode($flash));
        return $flash_id;
    }

    public function getFlash() {
        if(isset($_REQUEST['flash_id'])) {
            $flash_id = $_REQUEST['flash_id'];
            $flash_data = glue('session')->read("flash.$flash_id");
            glue('session')->delete("flash.$flash_id");
            if(!empty($flash_data)) {
                $data = json_decode($flash_data, true);
                return $data;
            }
        }
        return array();
    }
}
