<?php
require_once(EXTERNALS_DIR.'/openerp-php-webservice-client/src/OpenErpOcs.php');

class myOpenErpConnection {
    static $openerp_client = null;

    static function getConnection() {
        if(self::$openerp_client === null) {
            $openerp_server = glue("config")->read('openerp_server');
            $username = glue("config")->read('username');
            $pwd = glue("config")->read('pwd');
            $dbname = glue("config")->read('dbname');
            self::$openerp_client = new OpenErpWebServiceClient($openerp_server, $username, $pwd, $dbname);
        }
        return self::$openerp_client;
    }
}




class myOpenErpPqr extends OpenErpPqr {
    protected $create_operation_name = 'new_from_data';

    protected function processAttributes() {
    }

    public function create() {
        $r = parent::create();
        $this->id = null;
        if(!empty($r['result']['id'])) {
            $this->id = $r['result']['id'];
        }
        return $r;
    }

    public function getFilename() {
        return 'pqr-'.$this->id;
    }

    public function getFullFilename() {
        $path = glue("config")->read('attachment_path');
        return $path.'/'.$this->getFilename();
    }
    public function getFullThumbFilename() {
        $path = glue("config")->read('attachment_path');
        return $path.'/thumb/'.$this->getFilename();
    }

    public function imageExists() {
        return file_exists($this->getFullFilename());
    }

    public function imageThumbExists() {
        return file_exists($this->getFullThumbFilename());
    }

    public function getImageUrl() {
        if($this->imageExists()) {
          $path = glue("config")->read('attachment_base_url');
          return $path.'/'.$this->getFilename();
        }
        return 'http://placehold.it/150&text=Sin+imagen';
    }

    public function getImageThumbUrl() {
        if($this->imageThumbExists()) {
          $path = glue("config")->read('attachment_base_url');
          return $path.'/thumb/'.$this->getFilename();
        }
        return 'http://placehold.it/64&text=Sin+imagen';
    }
}
