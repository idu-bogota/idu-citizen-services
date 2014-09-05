<?php echo $form->render($view) ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').submit(function() {
            var mensajes = [];
            if(!$('#description').val()) {
                mensajes.push('Por favor ingrese una descripción');
            }

            if(($('#name').val() && !$('#lastname').val()) || (!$('#name').val() && $('#lastname').val())) {
                mensajes.push('Por favor diligencie nombres y apellidos');
            }

            if( $('#name').val() && !(
                $('#email').val() ||
                $('#twitter').val() ||
                $('#facebook').val() ||
                $('#phone').val() ||
                $('#address').val()
            )) {
                mensajes.push('Por favor ingrese al menos un dato de contacto');
            }

            if(mensajes.length > 0) {
                alert(mensajes.join("\n"));
                return false;
            }
            $('#submit').attr('disabled','disabled');
        });
    });
</script>