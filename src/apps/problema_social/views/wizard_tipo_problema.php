<div class="row-fluid" data-toggle="buttons-radio">
  <?php echo $form->tipo_problema->render($view) ?>
  <ul class="thumbnails">
    <?php
        $options = array(
            'social' => array(
                'label' => 'Social',
                'img' => glue("route")->url('/img/problema_social.jpg'),
                'desc' => 'Describe aspectos de inseguridad por falta de iluminación o constantes hurtos',
            ),
            'economico' => array(
                'label' => 'Económico',
                'img' => glue("route")->url('/img/problema_economico.jpg'),
                'desc' => 'Aumento o reducción de ventas por la ejecución de la obra',
            ),
            'ambiental' => array(
                'label' => 'Ambiental',
                'img' => glue("route")->url('/img/problema_ambiental.jpg'),
                'desc' => 'Contaminación del medio ambiente',
            ),
            'movilidad' => array(
                'label' => 'Movilidad',
                'img' => glue("route")->url('/img/problema_movilidad.jpg'),
                'desc' => 'Describe aspectos como: congestión vehicular, carencia de semáforos, Puentes peatonales, señalización, deficit de transporte',
            ),
        );
    ?>
    <?php foreach($options as $key => $option): ?>
        <li class="span3">
          <div class="thumbnail">
            <img class="img-rounded" src="<?php echo $option['img']?>"/>
            <div class="caption">
              <h3 class="text-info"><?php echo ucwords($option['label']) ?></h3>
              <?php echo $option['desc'] ?>
              <p><button type="button" class="btn tipo_problema" data-toggle="button" value="<?php echo $key ?>">Seleccionar</button></p>
            </div>
          </div>
        </li>
    <?php endforeach ?>
  </ul>
</div>
