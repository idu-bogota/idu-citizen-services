idu-citizen-services
====================

Contiene el código de los servicios proveídos a los ciudadanos por el Instituto de Desarrollo Urbano IDU de Bogotá Colombia.

De manera general el aplicativo se encarga de desplegar una interfaz de usuario del sistema de gestión de PQRS desarrollado en OpenERP y públicado en https://github.com/idu-bogota/openerp-idu-addons/ .

La aplicación PHP utiliza el framework minimalista PHP [GLUE](https://github.com/aheinze/Glue). 

En la carpeta *src* se encuentran subdirectorios, cada uno representa un módulo, en cada uno de ellos se encuentra el código que esta compuesto por:

* Una clase controladora *controller.php* donde se definen las URLs a exponerse en la web y el código asociado
* Una clase con la definición de los formularios que se despliegan (ver *forms.php*) desarrollados utilizando el [framework Zend](http://framework.zend.com/manual/1.12/en/zend.form.quickstart.html)
* Las vistas estan creadeas en la subcarpeta *views* 
* Los elementos js, css e imágenes estan en la carpeta *src/web*

Cada módulo es llamado utilizando un frontcontroller que esta publicado en la carpeta *src/web*.

El desarrollo de la interfaz hace uso de:

 * [http://getbootstrap.com/2.3.2/](Framework CSS Bootstrap 2.3) y la extensión [http://jasny.github.io/bootstrap/index.html](Jasny)
 * [http://documentcloud.github.io/backbone/](Framework MVC Javascript)
 * [http://www.openlayers.org/](Framework para webmapping Openlayers)

El módulo geo_pqr utiliza la libreria javascript *src/web/js/wizard.js* para la construcción del asistente, el cual se implementa en el javascript disponible en src/web/js/geo_pqr.js
