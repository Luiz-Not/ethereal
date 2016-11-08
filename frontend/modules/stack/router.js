define([
  'frontendPath/stack/views/stack'
],

function(stack) {
  return Backbone.SubRoute.extend({
    routes: {
      '': 'index',
      'insert': 'create',
      'update/:id': 'update',
    },

    index: function() {
      App.mainRegion.show(new Stack());
    },

    create: function() {
      App.mainRegion.show(new Stack());
    },

    update: function(id) {
      App.mainRegion.show(new Stack({id: id}));
    },
  });
});
