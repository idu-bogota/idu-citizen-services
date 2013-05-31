<div class="row-fluid" data-toggle="buttons-radio">
  <?php echo $form->damage_type_by_citizen->render($view) ?>
  <ul class="thumbnails">
    <?php
        $options = array(
            'fisura' => array(
                'key' => 'fisura',
                'img' => RELATIVE_ROOT_URI.'/img/fisura.jpg',
                'desc' => '<p>Consiste en la fractura de la capa del pavimento o concreto.</p>',
            ),
            'hueco' => array(
                'key' => 'hueco',
                'img' => RELATIVE_ROOT_URI.'/img/hueco.jpg',
                'desc' => '<p>Es una depresión en la superficie de la vía generada por el crecimiento de fisuras causadas e intensificadas por el tráfico y/o el clima.</p>',
            ),
            'hundimiento' => array(
                'key' => 'hundimiento',
                'img' => RELATIVE_ROOT_URI.'/img/hundimiento.jpg',
                'desc' => '<p>Es una depresión pronunciada generada principalmente por la erosión de los materiales que soportan la estructura de la via (ejemplo. tubos rotos).</p>',
            ),
        );
    ?>
    <?php foreach($options as $key => $option): ?>
        <li class="span4">
          <div class="thumbnail">
            <img class="img-rounded" src="<?php echo $option['img']?>"/>
            <div class="caption">
              <h3 class="text-info"><?php echo ucwords($key) ?></h3>
              <?php echo $option['desc'] ?>
              <p><button type="button" class="btn tipo_hueco" data-toggle="button" value="<?php echo $option['key'] ?>">Seleccionar</button></p>
            </div>
          </div>
        </li>
    <?php endforeach ?>
  </ul>
</div>