<h1>DEVOLUCIONES DE VALORIZACIÓN POR ACUERDO 398 DE 2009 - RECHAZOS DE GIRO</h1>

<p>La Dirección Técnica de Apoyo a la Valorización en cumplimiento de la Ley 1437 de 18 de enero de 2011, lo preceptuado en el Decreto 019 de 2012 y ante la imposibilidad de lograr la comparecencia de los contribuyentes y la notificación personal prevista en el artículo 67 y 69 de Código de Procedimiento Administrativo y Contencioso Administrativo, luego de haberse realizado el envío de la comunicación citada a la dirección registrada por el peticionario de la devolución de la Contribución de Valorización ordenada mediante el Acuerdo 398 de 2009, se publica:
</p>
 
<p>
Que mediante el presente aviso se notifican los rechazos de giro de peticiones de devolución presentadas para el Acuerdo 398 de 2009, con el número de documento de identificación del beneficiario, número del radicado mediante el que se surtió el trámite, así como el número de la orden de pago. En este sentido se considera legalmente notificado el contribuyente al finalizar el día siguiente del retiro del presente aviso. (Julio 14 de 2014)</p>

<!--<p>La Dirección Técnica de Apoyo a la Valorización informa que mediante Resoluciones VA 2 - VA 7 del 30 de noviembre de 2007, se gravaron los predios beneficiarios de la Contribución de Valorización por Beneficio Local -Acuerdo 180 de 2005, ubicados en las localidades de Fontibon y Engativá.</p>

<p>Con fundamento en el Acuerdo 398 de 2009, se expidió la Resolución VA 016 de 29 de octubre de 2010, reasignando la Contribución de que se trata, a los predios ubicados en la Zona de Influencia 2 del grupo 1 del Sistema de Movilidad.</p>

<p>La norma Ibídem igualmente estableció, en el artículo 11 que el Instituto de desarrollo Urbano debía realizar las devoluciones correspondientes con valores indexados de conformidad a lo estatuido en el Estatuto Tributario Nacional.</p>

<p>En la Resolución 3244 del 21 de octubre de 2010, se definieron las reglas de reasignación y devolución, adoptándose mediante Resolución 3858 de 07 de diciembre de 2010, el procedimiento PR-CV-059 Devolución de Dinero por Concepto de Valorización” versión 1.0.</p>

<p>En ese orden, solicitudes de devolución se reconocieron y ordenaron mediante actos administrativos, elaborándose órdenes de pago para los respectivos giros, previa validación de las cuentas bancarias.</p>

<p>Sin embargo, los giros fueron rechazados por diferentes causales como son: cuenta cancelada, cuenta saldada, identificación errada, número de cuenta inválido, cuenta no abierta ó cuenta inactiva entre otros, por lo que la transferencia de recursos no ha concluido. El Instituto de Desarrollo Urbano envió comunicaciones a los contribuyentes que hubieran presentado efectivamente la devolución y los giros hubiesen sido rechazados; pero los resultados no fueron favorables.</p>

<p>Dando aplicación al Decreto 624 de 1989, artículo 563 modificado por el artículo 59 del decreto 019 de 2012, 565 modificado por el artículo 45 de la Ley 111 de 2006, El Instituto de Desarrollo Urbano se permite relacionar los datos de los contribuyentes que a la fecha los giros de solicitud por devolución no han sido efectivos.</p>-->

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Fecha Rechazo</th>
            <th>OP</th>
            <th>No. Documento</th>
            <th>Notificación Rechazo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rechazos as $r): ?>
            <tr>
                <td><?php echo $r['rechazo']['fecha_de_rechazo'] ?></td>
                <td><?php echo $r['rechazo']['OP'] ?></td>
                <td><?php echo $r['rechazo']['numero_documento'] ?></td>
                <td><?php echo $r['rechazo']['numero_notificacion'] ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
