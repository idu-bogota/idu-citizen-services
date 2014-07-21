<div class="row-fluid" data-toggle="buttons-radio">
  <?php echo $form->damage_element_by_citizen->render($view) ?>
  <ul class="thumbnails">
    <?php
        $options = array(
            'via' => array(
                'label' => 'vía',
                'img' => glue("route")->url('/img/via.jpg'),
                'desc' => '',
            ),
            'anden' => array(
                'label' => 'anden',
                'img' => glue("route")->url('/img/anden.jpg'),
                'desc' => '',
            ),
            'cicloruta' => array(
                'label' => 'cicloruta',
                'img' => glue("route")->url('/img/cicloruta.jpg'),
                'desc' => '',
            ),
            'puente_peatonal' => array(
                'label' => 'puente peatonal',
                'img' => glue("route")->url('/img/puente-peatonal.jpg'),
                'desc' => '',
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
