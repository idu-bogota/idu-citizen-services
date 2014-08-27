<?php

/**
 * Busqueda de rechazos por documento de identidad Form
 *
 * @author Cinxgler Mariaca
 */

class RechazoForm extends Zend_Form {
    public function init() {
        $config = new Zend_Config_Yaml( __DIR__.'/forms.yml','rechazo');
        $this->setConfig($config);
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
            $captcha->setOrder(99);
            $this->getElement('submit')->setOrder(100);
        }
    }
}
 
