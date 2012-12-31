<div class="row-fluid" data-toggle="buttons-radio">
  <ul class="thumbnails">
    <?php
        $options = array(
            'bache' => array(
                'img' => 'http://placehold.it/400x200',
                'desc' => '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>',
            ),
            'hueco' => array(
                'img' => 'http://placehold.it/400x200',
                'desc' => '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>',
            ),
            'hundimiento' => array(
                'img' => 'http://placehold.it/400x200',
                'desc' => '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>',
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
              <p><button type="button" class="btn tipo_hueco" data-toggle="button">Seleccionar</button></p>
            </div>
          </div>
        </li>
    <?php endforeach ?>
  </ul>
</div>