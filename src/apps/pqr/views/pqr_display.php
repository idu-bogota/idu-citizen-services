<h2>Detalles del requerimiento No <?php echo $pqr['id'] ?> </h2>
<?php
    //fieldname - label
    $basic_fields = array(
        'name' => 'Clasificación',
        'channel' => 'Canal de recepción',
        'categ_id' => 'Tipo de requerimiento',
        'create_date' => 'Fecha y hora de creación',
        'state' => 'Estado',
        'description' => 'Descripción',
    );
    display_fields($basic_fields, $pqr);
    if(!empty($pqr['resolution'])) {
        $solution_fields = array(
            'resolution' => 'Respuesta',
            'date_closed' => 'Fecha de cierre',
            'orfeo_id' => 'Número de rádicado en sistema Orfeo'
        );
        display_fields($solution_fields, $pqr);
    }
?>
<a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>/search" class="btn">Consultar otro requerimiento</a>
<?php function display_fields($fields_map, $pqr) { ?>
    <dl>
      <?php foreach($fields_map as $key => $label): ?>
          <?php
            if($key == 'state') {
                $state_map = array(
                  'draft' => 'El requerimiento ha sido recibido pero no es ha iniciado su trámite.',
                  'pending' => 'El requerimiento ha sido recibido pero no es ha iniciado su trámite.',
                  'open' => 'El requerimiento ha sido recibido y está siendo tramitado actualmente por la dependencia correspondiente.',
                  'done' => 'El requerimiento ha sido tramitado por la dependencia correspondiente.',
                );
                $value = $state_map[$pqr['state']];
            }
            else {
                $value = is_array($pqr[$key])? $pqr[$key][1]: $pqr[$key];
                if(empty($value)) continue;
            }
          ?>
          <dt><?php echo $label ?></dt>
          <dd><?php echo $value ?></dd>
      <?php endforeach ?>
    </dl>
<?php } //end function display_fields?>