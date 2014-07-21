<?php if(!is_mobile()): ?>
<div class="row">
  <div class="span6">
      <div id='map_element' class="map"></div>
  </div>
  <div class="span6">
      <div class="hero-unit">
          <h1>Participe</h1>
          <p>Ayudanos a reportar los problemas que se presentan en el sector donde vives de manera rápida y sencilla.</p>
          <p>
              <a class="btn btn-primary" href="./new">Reportar</a>
          </p>
      </div>
      <div id="report_list">
      </div>
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
    <a class="pull-left" href="<%= image_url %>">
        <img class="media-object" src="<%= thumb_image_url %>">
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
            <p>Conozca los detalles de los últimos reportes realizados a través de esta herramienta. Haciendo clic en los puntos que se presentan en el mapa</p>
            <p>
                <a class="btn btn-primary" id="consultar" href="#">Consultar</a>
            </p>
        </div>
</script>
<?php else: ?>
    <div class="row">
      <div class="span12">
          <div class="well">
              <h1>Participe</h1>
              <p>Ayudanos a reportar los problemas que se presentan en el sector donde vives de manera rápida y sencilla.</p>
              <p>
                  <a class="btn btn-primary" href="./new">Reportar</a>
              </p>
          </div>
      </div>
    </div>
<?php endif ?>