<div class="span5">
    <?php echo $form->render(new Zend_View()) ?>
</div>
<div class="span6">
    <fieldset>
        <legend>Ubicación</legend>
        <div id='map_element' class="map"></div>
    </fieldset>
</div>

<script type='text/javascript'>
    $(document).ready(function(){
        window.main = new Ocs.View.Map({
            model: new Ocs.Model.Map(),
            initial_zoom: 15,
            el: $('#map_element')
        });
        main.render();
    });
</script>
