<div class="row-fluid" data-toggle="buttons-radio">
    <?php echo $form->damage_type_by_citizen->render($view) ?>
    <?php
        $options = array(
            'via-fisura' => array(
                'label' => 'fisura',
                'img' => glue("route")->url('/img/fisura.jpg'),
                'desc' => '<p>Consiste en la fractura de la capa del pavimento o concreto.</p>',
            ),
            'via-hueco' => array(
                'label' => 'hueco',
                'img' => glue("route")->url('/img/hueco.jpg'),
                'desc' => '<p>Es una depresión en la superficie de la vía generada por el crecimiento de fisuras causadas e intensificadas por el tráfico y/o el clima.</p>',
            ),
            'via-hundimiento-canalizacion' => array(
                'label' => 'hundimiento',
                'img' => glue("route")->url('/img/hundimiento.jpg'),
                'desc' => '<p>Es una depresión pronunciada generada principalmente por la erosión de los materiales que soportan la estructura de la via (ejemplo. tubos rotos).</p>',
            ),
            //**** Anden
            'anden-hueco' => array(
                'label' => 'hueco',
                'img' => glue("route")->url('/img/anden-hueco.jpg'),
                'desc' => '<p>Es una depresión en la superficie generada por el crecimiento de fisuras causadas e intensificadas por el tráfico y/o el clima</p>',
            ),
            'anden-desnivel' => array(
                'label' => 'desnivel',
                'img' => glue("route")->url('/img/anden-desnivel.jpg'),
                'desc' => '<p>El Anden presenta hundimientos o levantamientos.</p>',
            ),
            'anden-accesibilidad' => array(
                'label' => 'accesibilidad',
                'img' => glue("route")->url('/img/anden-accesibilidad.jpg'),
                'desc' => '<p>El andén no cumple con la normatividad de accesibilidad.</p>',
            ),
            //**** Cicloruta
            'cicloruta-hueco' => array(
                'label' => 'hueco',
                'img' => glue("route")->url('/img/cicloruta-hueco.jpg'),
                'desc' => '<p>Es una depresión en la superficie de la vía generada por el crecimiento de fisuras causadas e intensificadas por el tráfico y/o el clima</p>',
            ),
            'cicloruta-obstruccion' => array(
                'label' => 'obstrucción',
                'img' => glue("route")->url('/img/cicloruta-poste.jpg'),
                'desc' => '<p>Cualquier elemento que se encuentre en la vía que evite la libre circulación.</p>',
            ),
            'cicloruta-segnal' => array(
                'label' => 'señalización',
                'img' => glue("route")->url('/img/cicloruta-segnales.jpg'),
                'desc' => '<p>La falta o daño en elementos de señalización.</p>',
            ),
            //**** Puente Peatonal
            'puente-peatonal-grieta' => array(
                'label' => 'Grietas',
                'img' => glue("route")->url('/img/puente-peatonal-grieta.jpg'),
                'desc' => '<p>Consiste en la fractura de la capa del pavimento, concreto o laminas que hacen parte de la superficie de transito del puente peatonal.</p>',
            ),
            'puente-peatonal-laminas' => array(
                'label' => 'Láminas dañadas o robadas',
                'img' => glue("route")->url('/img/puente-peatonal-laminas.jpg'),
                'desc' => '<p>Las láminas del puente peatonal se encuentran rotas o han sido robadas.</p>',
            ),
            'puente-peatonal-accesibilidad' => array(
                'label' => 'Acceso al puente peatonal',
                'img' => glue("route")->url('/img/puente-peatonal-escalones.jpg'),
                'desc' => '<p>Hay algún daño que evita el acceso al puente peatonal.</p>',
            ),
        );
        $elements_map = array(
            'via' => array('via-fisura','via-hueco','via-hundimiento-canalizacion'),
            'anden' => array('anden-desnivel','anden-hueco','anden-accesibilidad'),
            'cicloruta' => array('cicloruta-hueco','cicloruta-obstruccion','cicloruta-segnal'),
            'puente_peatonal' => array('puente-peatonal-grieta','puente-peatonal-laminas','puente-peatonal-accesibilidad'),
        );
    ?>
    <?php foreach($elements_map as $element => $damage_type_keys): ?>
        <ul class="thumbnails element_type_<?php echo $element ?>" style="display:none">
            <?php foreach($damage_type_keys as $type_key): ?>
                <?php $option = $options[$type_key] ?>
                <li class="span4">
                    <div class="thumbnail">
                        <img class="img-rounded" src="<?php echo $option['img']?>"/>
                        <div class="caption">
                        <h3 class="text-info"><?php echo ucwords($option['label']) ?></h3>
                        <?php echo $option['desc'] ?>
                        <p><button type="button" class="btn damage_type" data-toggle="button" value="<?php echo $type_key ?>">Seleccionar</button></p>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endforeach ?>
</div>