var Ocs = { };//Setting namespace
Ocs.Map = Backbone.Model.extend({
    urlRoot: '',
    map: new OpenLayers.Map('map_element', { controls:[] }),
    defaults: function() {
        return {
            id:  '',
            from_projection: new OpenLayers.Projection("EPSG:4326"),   // Transform from WGS 1984
            to_projection: new OpenLayers.Projection("EPSG:900913"), // to Spherical Mercator Projection
            initial_position: new OpenLayers.LonLat(-74.075833,4.598056), //Currently Bogota
            initial_zoom: 14,
            controls: [
                new OpenLayers.Control.Navigation(),
                new OpenLayers.Control.PanZoomBar(),
                new OpenLayers.Control.ScaleLine(),
                new OpenLayers.Control.LayerSwitcher(),
            ],
            layers: [
                new OpenLayers.Layer.OSM(),
            ],
            //markers: new OpenLayers.Layer.Markers( "Markers" ),
            markers: new OpenLayers.Layer.Vector("Pins"),
        };
    },
    initialize: function() {
        var attr = this.attributes;
        this.map.addLayers(attr.layers);
        this.map.setCenter(attr.initial_position.transform( attr.from_projection, attr.to_projection), attr.initial_zoom );
        this.map.addControls(attr.controls);
    },
    render: function(element) {
        this.map.render(element);
    },
});
