var Ocs = { };//Setting namespace
Ocs.View = { };
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

Ocs.View.FormMap = Ocs.View.BaseMap.extend({
    my_initialize: function() {
        this.model.on('change:geometry', this.set_geometry, this);
        navigator.geolocation.getCurrentPosition( _.bind(this.set_geolocation, this) );
    },
    controls: function() {
        return [
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoomBar(),
            new OpenLayers.Control.ScaleLine(),
            new OpenLayers.Control.LayerSwitcher(),
            new Ocs.OpenLayers.Control.SinglePointEditingToolbar(this.model.get('markers'))
        ];
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

Ocs.View.ListMap = Ocs.View.BaseMap.extend({
    my_initialize: function() {
        this.report_list_view = new Ocs.View.ReportList({
            el: $('#report_list')
        });
        this.report_list_view.on('display_layer', this.display_layer, this);
        this.report_list_view.render();
        var style = new OpenLayers.Style({
            pointRadius: "${radius}",
            fillColor: "#00FF00",
            fillOpacity: 0.8,
            strokeColor: "#001100",
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
        var style_map = new OpenLayers.StyleMap({
            "default": style,
            "select": {
                fillColor: "#8aeeef",
                strokeColor: "#32a8a9"
            }
        });

        var list_layer = this.model.get('list_layer');
        list_layer.styleMap = style_map;
        list_layer.redraw();
        list_layer.setVisibility(false);

        select_control = new OpenLayers.Control.SelectFeature( list_layer, {
            onSelect: _.bind(this.on_feature_select, this),
            onUnselect: _.bind(this.on_feature_unselect, this)
        });
        this.model.map.addControl(select_control);
        select_control.activate();
    },
    controls: function() {
        return [
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoomBar(),
            new OpenLayers.Control.ScaleLine(),
            new OpenLayers.Control.LayerSwitcher(),
        ];
    },
    on_feature_select: function (feature) {
        this.report_list_view.add_feature(feature);
    },
    on_feature_unselect: function (feature) {
        this.report_list_view.remove_feature(feature);
        this.display_layer();
    },
    display_layer: function() {
        var layer = this.model.get('list_layer');
        layer.setVisibility(true);
        var feature = new OpenLayers.Feature.Vector();
        feature.cluster = [];
        _.each(layer.features, function(i) {
            feature.cluster.push(i.cluster[0]);
        });
        this.report_list_view.add_feature(feature, true);
    }
});

Ocs.View.ReportList = Backbone.View.extend(
    _.extend({}, Mixins.SubviewCollection, {
    events: {
        "click #consultar" : "display_layer",
    },
    initialize: function() {
        this.template = _.template($('#list-template').html());
        this.subview_tmpl = _.template($('#item-template').html());
    },
    display_layer: function(e) {
        this.trigger('display_layer');
        e.preventDefault();
    },
    create_subview_instance: function(model) {
        var view = new Ocs.View.ReportListItem({
            model: model, parent_view: this,
            template: this.subview_tmpl
        });
        return view;
    },
    add_feature: function(feature, all) {
        this.render();
        this.remove_all_subviews();
        $('.pagination').remove();
        if(all) {
            $(this.el).append('<h2>Reportes</h2>');
        }
        else {
            $(this.el).append('<h2>Reportes seleccionados</h2>');
        }
        _.each(feature.cluster, function(feature) {
            var model = new Ocs.Model.Report({ feature: feature });
            var view = this.add_subview(model);
        }, this); //Add a subview per feature in the cluster
        $(this.el).jPaginate({
            items: 5,
            next: ">>",
            previous: "<<",
            cookies: false,
            position: 'both'
        });
    },
    remove_feature: function(feature) {
        //_.each(feature.cluster, this.remove_subview, this); //remove a subview per feature in the cluster
        this.remove_all_subviews();
        this.render();
    },
    render: function() {
        $('.pagination').remove();
        $(this.el).html(this.template());
    }
}));

Ocs.View.ReportListItem = Backbone.View.extend({
    className: 'media',
    initialize: function() {
        this.template = this.options.template;
    },
    render: function() {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }
});

////////////// MODEL
Ocs.Model = {};
Ocs.Model.Map = Backbone.Model.extend({
    urlRoot: '',
    map: new OpenLayers.Map('map_element', { controls:[] }),
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
////////////// OPENLAYERS Extensions
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
