var Mixins = {};

//You need to have defined this:
// - create_subview_instance: function(model){ return new Backbone.View.extend({model: model, parent_view: this});},
// - subviews_element: set the element where all subviews will be append, if undefined this.el is taken instead
Mixins.SubviewCollection = {
  add_subview: function(model){
      if(_.isUndefined(this.subviews)) this.subviews = {};
      if(_.isUndefined(this.subviews[model.id])) {
          var view = this.create_subview_instance(model);
          if(_.isUndefined(view)) return;
          view.render();
          this.append_subview(view.el);
          this.subviews[model.id] = view;
      }
  },
  remove_subview: function(model) {
      if(_.isUndefined(this.subviews)) {
          return;
      }
      var subview = this.subviews[model.id];
      if(!_.isUndefined(subview)) {
          $(subview.el).remove();
      }
      delete this.subviews[model.id];
  },
  remove_all_subviews: function() {
      $(this.el).empty();
      if(_.isUndefined(this.subviews)) {
          return;
      }
      this.subviews = {};
  },
  append_subview: function(subview){
      if(_.isUndefined(this.subviews_element)) {
          $(this.el).append(subview);
      }
      else {
          $(this.subviews_element).append(subview);
      }
      return this;
  },
  empty_subview: function(){
      if(_.isUndefined(this.subviews_element)) {
          $(this.el).empty();
      }
      else {
          $(this.subviews_element).empty();
      }
      return this;
  },
  empty_subview_el_and_append_all_subviews: function() {
      this.empty_subview();
      _.each(this.subviews, function(subview, key){
          this.append_subview(subview);
      });
  },
  subviews_delegate_events: function(){
      _.each(this.subviews, function(subview, key){
          subview.delegateEvents();
      });
  },
  delegate_all_events: function(){
      this.delegateEvents();
      this.subviews_delegate_events();
  }
};

//You need to have defined this:
// - template_id: pointing to the template which will be used to render the content pf this view el
// - render_rebuilds_el_flag: define this if you want to regenerate the content of this.el in render(), by default it is true
// - template_data = function which return the data to be used to render the template, it will be merged with model.toJSON data
Mixins.TemplateView = {
  render_rebuilds_el: function(){
      if(_.isUndefined(this.render_rebuilds_el_flag)) {
          return true;
      }
      return this.render_rebuilds_el_flag;
  },
  _template_data: function(data){
      var model_data = {};
      if(!_.isUndefined(this.model)) {
          model_data = this.model.toJSON();
      }
      if(!_.isUndefined(data)) {
          _.defaults(data, model_data);
      }
      else {
          data = model_data;
      }

      if(!_.isUndefined(this.template_data)) {
          var my_data = this.template_data();
          _.extend(data, my_data);
      }

      return data;
  },
  template: function(){
      if(_.isUndefined(this._template)) {
          this._template =  _.template($('#' + this.template_id).html());
      }
      return this._template;
  },
  render_template: function(data){
      return this.template()(this._template_data(data));
  },
  render: function() {
      if(this.render_rebuilds_el()) {
          $(this.el).html(this.render_template());
          this.delegateEvents();
      }
      return this;
  }
};