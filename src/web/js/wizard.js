WizardView = Backbone.View.extend({
    id: 'wizard',

    initialize: function() {
        this.initializeModel();
        this.initializeViews();
        this.model.get('steps').each(function(step){
            var view = step.get('view');
            $('#current_step_container').append(view.el);
            $(view.el).hide()
        });
        this.model.on('change:current_step',this.render, this);
        this.model.set('current_step', 0);
    },

    initializeViews: function() {
        this.prev_button_view = new WizardPrevStepButtonView({
            model: this.model,
            template: _.template($('#' + this.id + '_prev_button_tmpl').html()),
            el: $('#prev_step_button'),
        });
        this.next_button_view = new WizardNextStepButtonView({
            model: this.model,
            template: _.template($('#' + this.id + '_next_button_tmpl').html()),
            el: $('#next_step_button'),
        });
        this.progress_indicator_view = new WizardProgressIndicatorView({
            model: this.model,
            template: _.template($('#' + this.id + '_progress_indicator_tmpl').html()),
            el: $('#progress_indicator'),
        });
    },

    render: function() {
        this.model.get('steps').each(function(step){$(step.get('view').el).hide()});
        $('#step_error').html('');
        $(this.model.currentView().render().el).show();
        var current_step = this.model.currentStep().toJSON();
        $('#step_title').html(current_step['title']);
        $('#step_instructions').html(current_step['instructions']);
        this.render_progress_indicator();
        return this;
    },
    render_progress_indicator: function() {
        this.progress_indicator_view.render();
        this.next_button_view.render();
        this.prev_button_view.render();
    },
    goNextStep: function() {
        this.model.goNextStep();
        $('html, body').animate({ scrollTop: 0 }, 0);
    },

    goPrevStep: function() {
        this.model.goPrevStep();
        $('html, body').animate({ scrollTop: 0 }, 0);
    }
});

WizardPrevStepButtonView = Backbone.View.extend({
    render: function() {
        var step = this.model.getPrevStep()['step'];
        if(! _.isEmpty(step)) {
            $(this.el).show();
            $(this.el).html(this.options.template(step.toJSON()));
        }
        else {
            $(this.el).hide();
            $(this.el).empty();
        }
        return this;
    }
});

WizardNextStepButtonView = Backbone.View.extend({
    render: function() {
        var step = this.model.getNextStep()['step'];
        if(! _.isEmpty(step)) {
            $(this.el).html(this.options.template(step.toJSON()));
        }
        else {
            $(this.el).html(this.options.template({title: 'Terminar'}));
        }
        return this;
    }
});

WizardProgressIndicatorView = Backbone.View.extend({
    render: function() {
        $(this.el).empty();
        this.model.get('steps').each(function(step) {
                var data = step.toJSON();
                data.css_class = '';
                if (step.get('step_number') == this.model.get('current_step') + 1) data.css_class = 'active';
                if(!step.skipMe()) {
                    $(this.el).append(this.options.template(data));
                }
            },this
        );
        return this;
    }
});

WizardModel = Backbone.Model.extend({
    defaults: function() {
        return {
            steps: new WizardStepCollection(),
            current_step: -1
        };
    },

    currentView: function() {
        return this.get('steps').at(this.get('current_step')).get('view');
    },

    currentStep: function() {
        return this.get('steps').at(this.get('current_step'));
    },

    getNextStep: function() {
        if(this.isLastStep()) return {'step': 0};
        var count = 0;
        var steps_forward = 0;
        do {
            var step_number = this.get('current_step') + steps_forward + 1;
            var step = this.get('steps').at(step_number);
            var skip_me = step.skipMe();
            if(skip_me){
                steps_forward++;
            }
            count++;
        }
        while (skip_me && count < 3);

        return { 'step': this.get('steps').at(step_number), 'step_number': step_number};
    },

    goNextStep: function() {
      var current = this.currentStep();
      if (current.validate()) {
        if (!this.isLastStep()) {
            var step_data = this.getNextStep();
            this.set('current_step', step_data['step_number']);
        } else {
            this.save();
        };
      };
    },

    getPrevStep: function() {
        if(this.isFirstStep()) return {'step': 0};
        var count = 0;
        var steps_backwards = 0;
        do {
            var step_number = this.get('current_step') - steps_backwards - 1;
            var step = this.get('steps').at(step_number);
            var skip_me = step.skipMe();
            if(skip_me){
                steps_backwards++;
            }
            count++;
        }
        while (skip_me && count < 3);
        return { 'step': this.get('steps').at(step_number), 'step_number': step_number};
    },

    goPrevStep: function() {
      if (!this.isFirstStep()) {
        var step_data = this.getPrevStep();
        this.set('current_step', step_data['step_number']);
      };
    },

    isFirstStep: function() {
      return (this.get('current_step') == 0);
    },

    isLastStep: function() {
      return (this.get('current_step') == this.get('steps').length - 1);
    }
});

WizardStep = Backbone.Model.extend({
    defaults: function() {
        return {
            number: 0,
            title: '',
            instructions: '',
            view: ''
        };
    },
    validate: function() {
        if(_.isFunction(this.get('view').validate)) {
            var is_valid = this.get('view').validate();
            if(is_valid != true) {
                this.display_error(is_valid);
            }
            return is_valid == true;
        }
        return true;
    },
    skipMe: function() {
        if(_.isFunction(this.get('view').skipMe)) {
            return this.get('view').skipMe();
        }
        return false;
    },
    display_error: function(msg) {
        var msg = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + msg + '</div>'
        $('#step_error').html(msg);
    }
});

WizardStepCollection = Backbone.Collection.extend({
    model: WizardStep,
    comparator: function(step) {
        return step.get('step_number');
    }
});