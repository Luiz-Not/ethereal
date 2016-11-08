define([
  'text!frontendPath/rds/templates/form-rds.html',
  'frontendPath/rds/models/rds' 
  ],

  function(tpl, model) {
    return Backbone.Marionette.ItemView.extend({
      template: _.template(tpl),
      model: new model(),

      events: {
        'click .btn-send': 'createRds'


      },

      bindings : {
        'input[name=name]': 'name',
        'input[name=url]': 'url', 
        'input[name=port]': 'port'
      },

      initialize: function() {
        
      },

      onRender: function() {
        
      },

      onShow: function() {
        this.stickit();
      },

      createRds: function(){
        this.model.save({},{
          success: function(model, response){
            console.log(response)
          }
        });
      }
    });
  });
