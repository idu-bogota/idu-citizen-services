<div class="span6">
    <div id='map_element' class="map span12"></div>
</div>
<div class="span6">
<?php for($i = 0; $i <= 5; $i++): ?>
    <div class="media">
        <a class="pull-left" href="#">
            <img class="media-object" src="http://placehold.it/64x64">
        </a>
        <div class="media-body">
            <h4 class="media-heading">Reporte de hueco</h4>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor, augue et malesuada dignissim, quam magna interdum quam, id molestie nunc nibh sit amet ante. Suspendisse purus mauris, suscipit ut tincidunt eget, luctus ut diam. Phasellus scelerisque, est at imperdiet sagittis,
        </div>
    </div>
<?php endfor ?>
</div>
<script type='text/javascript'>
    $(document).ready(function(){

        var list_layer = new OpenLayers.Layer.Vector('reportes', {
            projection: new OpenLayers.Projection("EPSG:900913"),
            strategies: [
                new OpenLayers.Strategy.Fixed(),
                new OpenLayers.Strategy.Cluster(),
                //new OpenLayers.Strategy.BBOX()
            ],
            protocol: new OpenLayers.Protocol.HTTP({
                url: './list.geojson',
                format: new OpenLayers.Format.GeoJSON(),
            })
        });

        window.main = new Ocs.View.ListMap({
            model: new Ocs.Model.Map({
                layers: [ list_layer ],
                list_layer: list_layer
            }),
            initial_zoom: 12,
            el: $('#map_element')
        });
        main.render();
        map = window.main.map;
    });
</script>
