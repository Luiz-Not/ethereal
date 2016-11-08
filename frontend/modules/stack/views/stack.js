define([
  'text!frontendPath/stack/templates/form-stack.html',
  'frontendPath/stack/models/stack' 
  ],

  function(tpl, model) {
    return Backbone.Marionette.ItemView.extend({
      template: _.template(tpl),
      model: new model(),

      events: {
        'click .btn-stack': 'stack'


      },

      bindings : {
        'input[name=name]': 'name',
        'input[name=endereco]': 'endereco', 
        
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
