<div class="row-fluid" data-toggle="buttons-radio">
  <?php echo $form->damage_element_by_citizen->render($view) ?>
  <ul class="thumbnails">
    <?php
        $options = array(
            'via' => array(
                'label' => 'via',
                'img' => glue("route")->url('/img/via.jpg'),
                'desc' => '<p>Consiste en la fractura de la capa del pavimento o concreto.</p>',
            ),
            'anden' => array(
                'label' => 'anden',
                'img' => glue("route")->url('/img/anden.jpg'),
                'desc' => '<p>Es una depresión en la superficie de la vía generada por el crecimiento de fisuras causadas e intensificadas por el tráfico y/o el clima.</p>',
            ),
            'cicloruta' => array(
                'label' => 'cicloruta',
                'img' => glue("route")->url('/img/cicloruta.jpg'),
                'desc' => '<p>Es una depresión pronunciada generada principalmente por la erosión de los materiales que soportan la estructura de la via (ejemplo. tubos rotos).</p>',
            ),
            'puente-peatonal' => array(
                'label' => 'puente peatonal',
                'img' => glue("route")->url('/img/puente-peatonal.jpg'),
                'desc' => '<p>Es una depresión pronunciada generada principalmente por la erosión de los materiales que soportan la estructura de la via (ejemplo. tubos rotos).</p>',
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
              <p><button type="button" class="btn tipo_elemento" data-toggle="button" value="<?php echo $key ?>">Seleccionar</button></p>
            </div>
          </div>
        </li>
    <?php endforeach ?>
  </ul>
</div> 
