<?php
require_once(EXTERNALS_DIR.'/openerp-php-webservice-client/src/OpenErpWebServiceClient.php');
/*
 * This file contains classes required to connect to OpenERP-OCS module via werservices
*/

class OpenErpProblemaSocialObject extends OpenErpObject {
	protected function getClassName() {
		return 'ocs_mapidu.problema_social';
	}

	protected function getAttributesMetadata() {
		return array (
				'nombres' => array('compulsory' => 1, 'references' => FALSE),
				'apellidos' => array('compulsory' => 1, 'references' => FALSE),
				'tipo_documento' => array('compulsory' => 1, 'references' => FALSE),
				'documento' => array('compulsory' => 1, 'references' => FALSE),
				'email' => array('compulsory' => 0, 'references' => FALSE),
				'telefono_fijo' => array('compulsory' => 0, 'references' => FALSE),
				'celular' => array('compulsory' => 1, 'references' => FALSE),
				'direccion' => array('compulsory' => 1, 'references' => FALSE),				
				'tipo_problema' => array('compulsory' => 1, 'references' => FALSE),
				'tipo_problema_movilidad' => array('compulsory' => 1, 'references' => FALSE),
				'ubicacion' => array('compulsory' => 0, 'references' => FALSE),
				'image' => array('compulsory' => 0, 'references' => FALSE),
				'descripcion' => array('compulsory' => 1, 'references' => FALSE),
				'shape' => array('compulsory' => 1, 'references' => FALSE),
		);
	}
	protected function processAttributes() {
	}
	
	protected $create_operation_name = 'crear_peticion';
	
	
	public function create() {
		$r = parent::create();
		$this->id = null;
		if(!empty($r['result']['id'])) {
			$this->id = $r['result']['id'];
		}
		return $r;
	}
	
	public function getFilename() {
		return 'problema-social-'.$this->id;
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

