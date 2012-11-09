<div class="span6">
    <div id='map_element' class="map span12"></div>
</div>
<div class="span6">
    <div class="hero-unit">
        <h1>Participe</h1>
        <p>Realize el reporte de daños en la malla vial y el espacio público de la ciudad, de manera rápida y sencilla.</p>
        <p>
            <a class="btn btn-primary" href="./new">Reportar</a>
        </p>
    </div>
    <div id="report_list">
    </div>
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
<script type="text/template" id="item-template">
    <a class="pull-left" href="#">
        <img class="media-object" src="http://placehold.it/64x64">
    </a>
    <div class="media-body report-item">
        <!-- <h4 class="media-heading">Reporte No: <%= claim_id %></h4> -->
        <%= description %>
        <ul>
            <li><strong>Reporte No:</strong> <%= claim_id %></li>
            <li><strong>Categoría:</strong> <%= category %></li>
            <li><strong>Clasificación: </strong><%= classification %></li>
        </ul>
    </div>
</script>
<script type="text/template" id="list-template">
        <div class="hero-unit">
            <h1>Consulte</h1>
            <p>Haga click sobre los reportes disponibles en el mapa para conocer los detalles del mismo</p>
        </div>
</script>