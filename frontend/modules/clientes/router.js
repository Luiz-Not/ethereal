define([
  'frontendPath/clientes/views/clientes'
],

function(Clientes) {
  return Backbone.SubRoute.extend({
    routes: {
      '': 'index',
      'insert': 'create',
      'update/:id': 'update',
    },

    index: function() {
      App.mainRegion.show(new Clientes());
    },

    create: function() {
      App.mainRegion.show(new Clientes());
    },

    update: function(id) {
      App.mainRegion.show(new Clientes({id: id}));
    },
  });
});
