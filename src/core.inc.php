<?php
//************ CONSTANTS ********
define ('EXTERNALS_DIR',__DIR__.'/../externals');
define ('ROOT_PATH',__DIR__);
//************ External libraries ********
require_once(EXTERNALS_DIR.'/Glue/vendor/glue/glue.php');
ini_set('include_path', get_include_path().':'.EXTERNALS_DIR.'/zend/library');
ini_set('include_path', get_include_path().':'.EXTERNALS_DIR.'/zend-form-decorators-bootstrap');
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Twitter');

//************ Internal libraries ********
require_once(ROOT_PATH.'/includes/openerp.inc.php');
require_once(ROOT_PATH.'/includes/form.inc.php');
require_once(ROOT_PATH.'/includes/helper.inc.php');
require_once(ROOT_PATH.'/includes/myGlue.inc.php');

//************ Run App ********
define ('APP_ROOT_PATH',ROOT_PATH.'/apps/'.APP_FOLDER_NAME);
ini_set('include_path', get_include_path().':'.APP_ROOT_PATH);
require_once(APP_ROOT_PATH.'/controller.php');
glue("session")->init();
