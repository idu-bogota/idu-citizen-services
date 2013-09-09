var Ocs = { };//Setting namespace
Ocs.View = { };
/* Clase base para desplegar un mapa de Bogotá utilizando OpenLayers */
Ocs.View.BaseMap = Backbone.View.extend({
    options: {
        initial_zoom: 14,
        initial_position: [-74.075833, 4.598056],  //Currently Bogota
        extent_bbox: [[-74.2317015302732, 4.851251346630552],[-73.9927488935548, 4.476900687054601]],//Currently Bogota aprox limits
    },
    initialize: function() {
        this.map = this.model.map;
        this.my_initialize();
    },
    render: function() {
        var extent_bbox = this.options.extent_bbox;
        var bounds = new OpenLayers.Bounds();
        bounds.extend(this.model.get_lonlat(extent_bbox[0][0], extent_bbox[0][1]));
        bounds.extend(this.model.get_lonlat(extent_bbox[1][0], extent_bbox[1][1]));
        this.map.setOptions({restrictedExtent: bounds });

        var initial_position = this.options.initial_position;
        var initial_zoom = this.options.initial_zoom;
        this.map.setCenter(this.model.get_lonlat(initial_position[0],initial_position[1]), initial_zoom);

        this.map.addControls(this.controls());
        this.map.render(this.el);
    }
});

/****************************************
 * MODEL
 */

Ocs.Model = {};
Ocs.Model.Map = Backbone.Model.extend({
    urlRoot: '',
    map: new OpenLayers.Map(),
    defaults: function() {
        return {
            id:  '',
            from_projection: new OpenLayers.Projection("EPSG:4326"),   // Transform from WGS 1984
            to_projection: new OpenLayers.Projection("EPSG:900913"), // to Spherical Mercator Projection
            base_layers: [
                new OpenLayers.Layer.OSM(),
            ],
            layers: [],
            markers: null,
            geometry: new OpenLayers.Geometry()
        };
    },
    initialize: function() {
        var attr = this.attributes;
        this.map.addLayers(attr.base_layers);
        this.map.addLayers(attr.layers);
        if(attr.markers) {
            this.map.addLayer(attr.markers);
        }
    },
    get_lonlat : function(lon, lat) {
        return new OpenLayers.LonLat(lon,lat).transform(
            this.get('from_projection'), this.get('to_projection')
        );
    }
});

Ocs.Model.Report = Backbone.Model.extend({
    urlRoot: '',
    initialize: function() {
        this.id = this.get('feature').id;
        this.set(this.get('feature').attributes);
    },
});


/****************************************
 * OPENLAYERS Extensions
 */

/* Crea un toolbar para permitir la edición de un unico punto en una capa */
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

/* Clase para permitir adicionar un unico punto en una capa, utilizada en gep_pqr para reportar un daño por PQR */
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
