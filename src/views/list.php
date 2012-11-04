<div class="span6">
    <fieldset>
        <legend>Ubicación</legend>
        <div id='map_element' class="map span12"></div>
    </fieldset>
</div>
<div class="span6">

</div>
<script type='text/javascript'>
    $(document).ready(function(){
        var style = new OpenLayers.Style({
            pointRadius: "${radius}",
            fillColor: "#ffcc66",
            fillOpacity: 0.8,
            strokeColor: "#cc6633",
            strokeWidth: 2,
            strokeOpacity: 0.8
        }, {
            context: {
                radius: function(feature) {
                    var pix = 2;
                    if(feature.cluster)
                        pix = Math.min(feature.attributes.count, 7) + 2;
                    return pix;
                }
            }
        });
        var styleMap = new OpenLayers.StyleMap({
                        "default": style,
                        "select": {
                            fillColor: "#8aeeef",
                            strokeColor: "#32a8a9"
                        }
                    });
        var geoJSONsource = './list.geojson';
        var listLayer = new OpenLayers.Layer.Vector('reports', {
          styleMap: styleMap,
          projection: new OpenLayers.Projection("EPSG:900913"),
          strategies: [
            new OpenLayers.Strategy.Fixed(),
            new OpenLayers.Strategy.Cluster(),
            //new OpenLayers.Strategy.BBOX()
          ],
          protocol: new OpenLayers.Protocol.HTTP({
            url: geoJSONsource,
            format: new OpenLayers.Format.GeoJSON(),
          })
        });

        window.main = new Ocs.View.ListMap({
            model: new Ocs.Model.Map({
                layers: [listLayer],
            }),
            initial_zoom: 12,
            el: $('#map_element')
        });
        main.render();
    });
</script>
