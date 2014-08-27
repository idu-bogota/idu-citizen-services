<div class="details">
    <div class="title">Coordinador IDU:<br>
        <span style="font-size:13px;font-weight:bold;"><?php echo $informe_obra['proyecto_id']['coordinador_id'][1] ?><br>
            <span style="font-size:10px;">IDU calle 22#6-27<br>Telefono: 3386660 Ext:6182</span>
        </span>
    </div>
    <div class="download">
        <a href="<?php echo $informe_obra['attachment']['url']?>">
            <img src="images/boton-descarga.png">
        </a>
    </div>
    <hr>
    <div class="avanceObra">
        <span style="margin-left:72px;">Avance de Obra: </span>
        <span class="avance_amarillo"><?php echo $informe_obra['obra_ejecutada_acum'] ?>%</span>
        <br>Última actualización:
        <span class="avance"><?php echo $informe_obra['fecha_final_sem'] ?></span>
    </div>
    <div class="midtitleDiv">
        <div class="midtitleText">
            <?php echo $informe_obra['proyecto_id']['nombre'] ?>
        </div>
        <div class="pictureFrame">
            <img src="<?php echo $informe_obra['frente_id']['link_photo1']?$informe_obra['frente_id']['link_photo1']:'http://placehold.it/264x170/000000/ffffff&text=Foto+1' ?>" />
        </div>
        <div class="pictureFrame">
            <img src="<?php echo $informe_obra['frente_id']['link_photo2']?$informe_obra['frente_id']['link_photo2']:'http://placehold.it/264x170/000000/ffffff&text=Foto+2' ?>" />
        </div>
    </div>
    <hr style="color: #0095FF; background-color: #0095FF; height: 3px;">
    <div class="leftDiv">
        <p></p>
        <div class="block" style="background-color: #7190C9;">
            <br /><span class="textBlock">Descripción</span>
        </div>
        <div class="rightTextPop">
            <?php echo $informe_obra['proyecto_id']['descripcion_publico'] ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #91D8F6;">
            <br><span class="textBlock">Contratista</span>
        </div>
        <div class="rightTextPop">
            <?php echo $informe_obra['proyecto_id']['nombre_contratista'] ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #5BC6D0;">
            <br><span class="textBlock">Interventoría</span>
        </div>
        <div class="rightTextPop">
            <?php echo $informe_obra['proyecto_id']['nombre_interventoria'] ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #A9CF48;">
            <br><span class="textBlock">Plazo de Ejecución</span>
        </div>
        <div class="rightTextPop">
            <?php echo $informe_obra['proyecto_id']['plazo_actualizado'] ?> días
            (<?php echo $informe_obra['proyecto_id']['plazo_actualizado']/30 ?> meses)
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #FFF112;">
            <br><span class="textBlock">Inversión</span>
        </div>
        <div class="rightTextPop">
            Inversión obras civiles: $<?php echo number_format($informe_obra['proyecto_id']['valor_actualizado']) ?><br>
            Interventoría: $<?php echo number_format($informe_obra['proyecto_id']['valor_actualizado_interv']) ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #D7CB75;">
            <br><span class="textBlock">Avance de la Obra</span>
        </div>
        <div class="rightTextPop">
            Estado: Avance acumulado de las obras a la fecha: <?php echo $informe_obra['obra_ejecutada_acum'] ?>%
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #F58634;"><br>
            <span class="textBlock">Fin de la Obra</span>
        </div>
        <div class="rightTextPop">
            <?php echo $informe_obra['proyecto_id']['fecha_terminacion'] ?>
            <br /><em>Fecha programada para la finalización del contrato de obra (Año-mes-dia)</em>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #FAAA55;">
            <br><span class="textBlock">En que se trabaja</span>
        </div>
        <div class="rightTextPop">
            <?php
                $act = $informe_obra['frente_id']['actividad_semana_publico'];
                if(!empty($act))
                {
                    echo nl2br($act);
                }
            ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
        <p></p>
        <div class="block" style="background-color: #FDD2B0;">
            <br><span class="textBlock">Novedades</span>
        </div>
        <div class="rightTextPop" style="background-color: #D7D7D7; border-radius: 10px 10px 10px 10px;">
            <?php echo $informe_obra['observaciones_publico']; ?>
        </div>
        <hr style="border-top: 1.5px dashed #0095FF;">
    </div>
</div>
