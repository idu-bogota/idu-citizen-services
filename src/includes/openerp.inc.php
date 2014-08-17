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

class OpenErpInformeObra extends OpenErpObject {
    protected $fetch_operation_name = 'search_ultimo_informe_publicado';
    protected function getClassName() {
        return 'reporte_obras.informe';
    }

    protected function getAttributesMetadata() {
        return array (
            'name' => array('compulsory' => 1, 'references' => FALSE),
            'informe_id' => array('compulsory' => 1, 'references' => FALSE),
            'actividad_semana' => array('compulsory' => 1, 'references' => FALSE),
        );
    }

    protected function processAttributes() {}

    public function findByFrenteId($frente_id) {
        $cache = glue("config")->read('cache_manager')->getCache('cache');
        $cache_key = 'reporte_obra_frente_id_'.$frente_id;
        $result = $cache->load($cache_key);
        if($result === false) {
            $result = $this->client->execute($this->getClassName(), 'get_data_para_frente_id', $frente_id);
            if(!empty($result) && $result['status'] == 'success') {
                if(isset($result['result']['attachment'])) {
                    $path = glue("config")->read('attachment_path');
                    $url_base = glue("config")->read('attachment_base_url');
                    $filename = $path.'reporte_obras/'.$result['result']['attachment']['name'];
                    if(!file_exists($filename))
                    {
                        $attachment = $this->client->execute($this->getClassName(), 'get_attachment_para_frente_id', $frente_id);
                        base64_to_file($attachment['result']['datas'], $filename);
                    }
                    $result['result']['attachment']['url'] = $url_base."reporte_obras/".$result['result']['attachment']['name'];
                }
                $cache->save($result, $cache_key);
            }
        }
        return $result;
    }
}

function base64_to_file($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");
    fwrite($ifp, base64_decode($base64_string));
    fclose($ifp);
    return($output_file);
}