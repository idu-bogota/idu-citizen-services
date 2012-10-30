<?php echo $form->render(new Zend_View()) ?>
<div id='map_element' style='width: 500px; height: 500px;'></div>


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
