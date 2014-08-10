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
				'imagen' => array('compulsory' => 0, 'references' => FALSE),
				'descripcion' => array('compulsory' => 1, 'references' => FALSE),
				'shape' => array('compulsory' => 1, 'references' => FALSE),
				'create_date' => array('compulsory'=>0,'references'=>FALSE ),
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
	
	public function getGeoJsonFeature($as_array = false) {
		// { "type": "Feature",
		//   "geometry": {"type": "Point", "coordinates": [102.0, 0.5]},
		//   "properties": {"prop0": "value0"}
		// }
		if(empty($this->attributes['shape'])) return null;
		$map = array('tipo_problema','tipo_problema_movilidad','ubicacion','create_date','descripcion');
		$feature = array(
				'type' => 'Feature',
				'geometry' => json_decode($this->attributes['shape']),
				'properties' => array(
						'problema_id' => $this->id,
						'tipo_problema' => $this->attributes['tipo_problema'][1],
						'tipo_problema_movilidad' => $this->attributes['tipo_problema_movilidad'][1],
				)
		);
		foreach($map as $field) {
			$feature['properties'][$field] = $this->attributes[$field];
		}
		if($as_array) {
			return $feature;
		}
		return json_encode($feature);
	}
}

