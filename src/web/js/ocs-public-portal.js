var Ocs = { };//Setting namespace
Ocs.View = { };
Ocs.View.Map = Backbone.View.extend({
    options: {
        initial_zoom: 14,
        initial_position: new OpenLayers.LonLat(-74.075833,4.598056),  //Currently Bogota
        controls: []
    },
    initialize: function() {
        this.map = this.model.map;
        this.model.on('change:geometry', this.set_geometry, this);
        navigator.geolocation.getCurrentPosition( _.bind(this.set_geolocation, this) );
    },
    render: function() {
        this.controls = [
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoomBar(),
            new OpenLayers.Control.ScaleLine(),
            new OpenLayers.Control.LayerSwitcher(),
            new Ocs.OpenLayers.Control.SinglePointEditingToolbar(this.model.get('markers'))
        ];
        bounds = new OpenLayers.Bounds();
        bounds.extend(new OpenLayers.LonLat(-74.2317015302732,4.851251346630552).transform(
            new OpenLayers.Projection("EPSG:4326"), this.map.getProjectionObject())
        );
        bounds.extend(new OpenLayers.LonLat(-73.9927488935548,4.476900687054601).transform(
            new OpenLayers.Projection("EPSG:4326"), this.map.getProjectionObject())
        );
        this.map.setOptions({restrictedExtent: bounds });

        var initial_position = this.options.initial_position;
        var initial_zoom = this.options.initial_zoom;
        this.map.setCenter(initial_position.transform( this.model.get('from_projection'), this.model.get('to_projection')), initial_zoom);
        this.map.addControls(this.controls);
        this.map.render(this.el);
    },
    set_geometry: function(model){
        var geoJSON = new OpenLayers.Format.GeoJSON();
        $('#geo_point').attr('value',geoJSON.write(model.get('geometry')));
    },
    set_geolocation: function(position) {
        var lonLat = new OpenLayers.LonLat(position.coords.longitude, position.coords.latitude);
        lonLat = lonLat.transform(
            new OpenLayers.Projection("EPSG:4326"), //transform from WGS 1984
            this.map.getProjectionObject() //to Spherical Mercator Projection
        );
        var layer = this.model.get("markers");
        var point = new OpenLayers.Geometry.Point(lonLat.lon, lonLat.lat);
        var feature = new OpenLayers.Feature.Vector(point,{icon: "http://www.openlayers.org/dev/img/marker.png"} );
        layer.addFeatures(feature);
        this.map.setCenter(lonLat, 18);// Zoom level
    }
});

Ocs.Model = {};
Ocs.Model.Map = Backbone.Model.extend({
    urlRoot: '',
    map: new OpenLayers.Map('map_element', { controls:[] }),
    defaults: function() {
        return {
            id:  '',
            from_projection: new OpenLayers.Projection("EPSG:4326"),   // Transform from WGS 1984
            to_projection: new OpenLayers.Projection("EPSG:900913"), // to Spherical Mercator Projection
            layers: [
                new OpenLayers.Layer.OSM(),
            ],
            markers: new OpenLayers.Layer.Vector("Markers"),
            geometry: new OpenLayers.Geometry()
        };
    },
    initialize: function() {
        var attr = this.attributes;
        this.map.addLayers(attr.layers);
        this.map.addLayer(attr.markers);
    }
});

Ocs.OpenLayers = {};
Ocs.OpenLayers.Control = {};
Ocs.OpenLayers.Control.SinglePointEditingToolbar = OpenLayers.Class( OpenLayers.Control.Panel, {
    /**
        * APIProperty: citeCompliant
        * {Boolean} If set to true, coordinates of features drawn in a map extent
        * crossing the date line won't exceed the world bounds. Default is false.
        */
    citeCompliant: false,

    /**
     * Constructor: Ocs.OpenLayers.Control.SinglePointEditingToolbar
        * Create an editing toolbar for a given layer.
        *
        * Parameters:
        * layer - {<OpenLayers.Layer.Vector>}
        * options - {Object}
        */
    initialize: function(layer, options) {
        OpenLayers.Control.Panel.prototype.initialize.apply(this, [options]);

        this.addControls(
            [ new OpenLayers.Control.Navigation() ]
        );
        var controls = [
        new Ocs.OpenLayers.Control.DrawOnePointOnly(layer, OpenLayers.Handler.Point, {
            displayClass: 'olControlDrawFeaturePoint',
            handlerOptions: {citeCompliant: this.citeCompliant}
        }),
        ];
        this.addControls(controls);
    },

    /**
     * Method: draw
     * calls the default draw, and then activates mouse defaults.
     *
     * Returns:
     * {DOMElement}
     */
    draw: function() {
        var div = OpenLayers.Control.Panel.prototype.draw.apply(this, arguments);
        if (this.defaultControl === null) {
            this.defaultControl = this.controls[1];
        }
        return div;
    },

    CLASS_NAME: "OpenLayers.Control.EditingToolbar"
});

Ocs.OpenLayers.Control.DrawOnePointOnly = OpenLayers.Class( OpenLayers.Control.DrawFeature, {
    initialize: function(layer, handler, options) {
        OpenLayers.Control.DrawFeature.prototype.initialize.apply(this, [layer, handler, options]);
    },

    /**
     * Method: drawFeature
     */
    drawFeature: function(geometry) {
        this.layer.removeAllFeatures();
        OpenLayers.Control.DrawFeature.prototype.drawFeature.apply(this, [geometry]);
        //var p = geometry.transform(this.map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
        //alert(p.x +','+ p.y +' resol:'+ this.map.getResolution());
        window.main.model.set('geometry',geometry); //FIXME: Dirty hack shouldn't use a global variable
    },

    CLASS_NAME: "OpenLayers.Control.DrawFeature"
});
