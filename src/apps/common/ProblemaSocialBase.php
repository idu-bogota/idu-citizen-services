<?php
require_once(EXTERNALS_DIR.'/openerp-php-webservice-client/src/OpenErpProblemaSocial.php');

class ProblemaSocialBaseForm extends BaseForm {
	public $object = null;
	protected $form_name = 'problema_social';
	protected $description_fieldmap = array('descripcion');
	protected $forward_fieldmap = array();
	protected $add_submit_button_on_captcha = true;

	public function init() {
		$config = new Zend_Config_Yaml( __DIR__.'/problema_social_form.yml',$this->form_name);
		$this->setConfig($config);
		$this->setAttrib('enctype', 'multipart/form-data');
		$publickey = glue("config")->read('recaptcha_pubkey');
		$privatekey = glue("config")->read('recaptcha_privkey');
		if(!empty($publickey)) {
			$recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);
			$captcha = new Zend_Form_Element_Captcha('captcha',
					array(
							'captcha'       => 'ReCaptcha',
							'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)
					)
			);
			$this->addElement($captcha);
			$send_group = $this->getDisplayGroup('send');
			if($send_group) {
				$send_group->clearElements();
				$send_group->addElement($captcha);
				if($this->add_submit_button_on_captcha) {
					$send_group->addElement($this->getElement('submit'));
				}
			}
			else {
				$captcha->setOrder(99);
				if($this->add_submit_button_on_captcha) {
					$this->getElement('submit')->setOrder(100);
				}
			}
		}
	}



	public function buildObject(){
		$c = $this->getOpenErpConnection();
		$values = $this->getValues();
		$problema_social_obj = new OpenErpProblemaSocialObject($c);
		$attributes = array();
		if( !empty($values['nombres']) && !empty($values['apellidos']) ) {
			$citizen = array(
					'nombres' => $values['nombres'],
					'apellidos' => $values['apellidos']
			);
			if(!empty($values['document_number'])) {
				$citizen['tipo_documento'] = $values['tipo_documento'];
				$citizen['documento'] = $values['documento'];
			}
			$contact_map = array('email','celular','direccion','telefono_fijo');
			foreach($contact_map as $f) {
				if( !empty($values[$f]) ) {
					$citizen[$f] = $values[$f];
				}
			}
			$attributes['ciudadano'] = $citizen;
		}
		
		$problema_social = array(
				'ubicacion' => $values['ubicacion'],
				'shape' => $values['shape'],
				'tipo_problema'=>$values['tipo_problema'],
				'tipo_problema_movilidad'=>$values['tipo_problema_movilidad'],
				'imagen'=>$values['imagen'],
				'descripcion'=>$values['descripcion']
		);
		
		$attributes['problema_social'] = $problema_social;
		$attributes['ack_message_subject'] = '[IDU-PQR #{0}] Su requerimiento ha sido recibido';
		$attributes['ack_message_body'] = "Su requerimiento ha sido registrado en nuestro Sistema de Gestión de Problematicas Sociales y Cartografía Social con el identificador No {0}\n\nNuestra Oficina de Atención al Ciudadano procederá a atender su solicitud para darle respuesta tan pronto como sea posible.\n\nMuchas gracias por comunicarse con nosotros.\n\n------ Su Requerimiento ------\n{1}\n";
		$problema_social_obj->attributes = $attributes;
		$this->object = $problema_social_obj;
		return $problema_social_obj;
	}
}