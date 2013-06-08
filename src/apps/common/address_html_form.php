<script type='text/javascript' src='<?php echo RELATIVE_ROOT_URI ?>/js/underscore-min.js'></script>
<script type='text/javascript' src='<?php echo RELATIVE_ROOT_URI ?>/js/bootstrap-min.js'></script>
<script type='text/javascript' src='<?php echo RELATIVE_ROOT_URI ?>/js/bootstrap-inputmask.min.js'></script>
<script type='text/javascript' src='<?php echo RELATIVE_ROOT_URI ?>/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='<?php echo RELATIVE_ROOT_URI ?>/js/jquery.validate.messages_es.js'></script>
<!--Codigo no utilizado se deja como referencia --> 
<a href="#address_modal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>
<div  id="address_modal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Asistente para el ingreso de la dirección</h3>
        <p>El sistema reconoce direcciones del tipo <strong>KR 92 A BIS Z 16 34 INT 1 AP 1001</strong>, este formulario le ayudará a escribir la dirección en el formato correcto. Los campos marcados con asterisco son obligatorios.</p>
    </div>
    <div class="modal-body">
        <form class="address_form form-inline">
            <div>
                <div class="input-prepend">
                    <span class="add-on">Vía</span>
                    <select name="st_type" class="span2">
                        <option value="">- Seleccione* -</option>
                        <option value="CL">Calle</option>
                        <option value="AC">Avenida Calle</option>
                        <option value="DG">Diagonal</option>
                        <option value="KR">Carrera</option>
                        <option value="AK">Avenida Carrera</option>
                        <option value="TV">Transversal</option>
                        <option value="CA">Camino</option>
                        <option value="CT">Carretera</option>
                        <option value="PS">Paseo</option>
                    </select>
                </div>

                <input class="input-mini" name="st_number_1" type="text" placeholder="000*">
                <input class="input-mini" name="st_suffix_a" type="text" data-mask="a" placeholder="A">
                <label class="checkbox">
                    <input name="bis" type="checkbox" value="BIS"/>BIS
                </label>
                <input class="input-mini" name="st_suffix_b" type="text" data-mask="a" placeholder="A">
            </div>
            <div>
                <div class="input-prepend">
                    <span class="add-on">Número</span>
                    <input class="input-mini" name="st_number_2" type="text" placeholder="000*">
                </div>
                <input class="input-mini" name="st_suffix_a2" type="text" data-mask="a" placeholder="A">
                <label class="checkbox">
                    <input name="bis2" type="checkbox" value="BIS"/>BIS
                </label>
                <input class="input-mini" name="st_suffix_b2" type="text" data-mask="a" placeholder="A">
                <input class="input-mini" name="st_number_3" type="text" placeholder="000*">
            </div>
            <div>
                <div class="input-prepend">
                    <span class="add-on">Ubicación</span>
                    <select name="st_interior" class="span2">
                        <option value="">- Seleccione -</option>
                        <option value="INT">Interior</option>
                        <option value="BQ">Bloque</option>
                        <option value="TO">Torre</option>
                        <option value="CA">Casa</option>
                    </select>
                    <input class="input-mini" name="st_number_interior" type="text" placeholder="A0">
            </div>
            <div>
                <div class="input-prepend">
                    <span class="add-on">Prop. Horizontal</span>
                    <select name="st_horizontal" class="span2">
                        <option value="">- Seleccione -</option>
                        <option value="AP">Apartamento</option>
                        <option value="OF">Oficina</option>
                        <option value="CON">Consultorio</option>
                        <option value="PEN">Pent House</option>
                        <option value="LOC">Local</option>
                        <option value="DEP">Deposito</option>
                        <option value="GJ">Garaje</option>
                    </select>
                </div>

                <input class="input-mini" name="st_number_horizontal" type="text" placeholder="000">
            </div>
            <div>
                <div class="input-prepend">
                    <span class="add-on">Zona</span>
                    <select name="st_sector" class="span2">
                        <option value="N">- Seleccione --</option>
                        <option value="N">Norte</option>
                        <option value="E">Oriente</option>
                        <option value="S">Sur</option>
                        <option value="O">Occidente</option>
                        <option value="NE">Noreste/Nororiente</option>
                        <option value="SE">Sureste/Suroriente</option>
                        <option value="SO">Suroccidente</option>
                        <option value="NO">Noroccidente</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('.address_form').validate({
        rules: {
            st_type: {
                required: true
            },
            st_number_1: {
                required: true,
                range: [1, 999]
            },
            st_number_2: {
                required: true,
                range: [1, 999]
            },
            st_number_3: {
                required: true,
                range: [1, 999]
            },
            st_number_interior: {
                range: [1, 999]
            },
            st_number_horizontal: {
                range: [1, 999]
            },

        },
        errorPlacement: function(label, element) {
            alert(label.html());
        }
    });
    $('#address_modal').on('hidden', function () {
        return $('.address_form').validate({
        rules: {
            st_type: {
                required: true
            },
            st_number_1: {
                required: true,
                range: [1, 999]
            },
            st_number_2: {
                required: true,
                range: [1, 999]
            },
            st_number_3: {
                required: true,
                range: [1, 999]
            },
            st_number_interior: {
                range: [1, 999]
            },
            st_number_horizontal: {
                range: [1, 999]
            },

        },
        errorPlacement: function(label, element) {
            alert(label.html());
        }
    });
    })
}); // end document.ready
</script>