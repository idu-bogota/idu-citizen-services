<div class="span6">
    <fieldset>
        <legend>Ubicación</legend>
        <div id='map_element' class="map span12"></div>
    </fieldset>
</div>
<div class="span6">
    <?php echo $form->render(new Zend_View()) ?>
</div>
<script type='text/javascript'>
    $(document).ready(function(){
        var styleMap = new OpenLayers.StyleMap({
            'default': {
                pointRadius: 10,
                fillOpacity: 1,
                externalGraphic: 'http://www.openlayers.org/dev/img/marker.png'
            },
            'temporary': {
                fillOpacity: 0.5,
                pointRadius: 10,
                externalGraphic: 'http://www.openlayers.org/dev/img/marker.png'
            }
        });

        window.main = new Ocs.View.FormMap({
            model: new Ocs.Model.Map({
              markers: new OpenLayers.Layer.Vector("Markers", {styleMap: styleMap})
            }),
            initial_zoom: 15,
            el: $('#map_element')
        });
        main.render();

        var is_anonymous_checked = $('#is_anonymous').attr('checked')?true:false;
        if(is_anonymous_checked) {
            $("#fieldset-datos_contacto").hide();
            $("#fieldset-datos_personales").hide();
        }

        $('#is_anonymous').click(function () {
            $("#fieldset-datos_contacto").toggle();
            $("#fieldset-datos_personales").toggle();
        });
    });
</script>
