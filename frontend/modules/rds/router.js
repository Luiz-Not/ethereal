define([
  'frontendPath/rds/views/rds'
],

function(Rds) {
  return Backbone.SubRoute.extend({
    routes: {
      '': 'index',
      'insert': 'createRds',
      'update/:id': 'updateRds',
    },

    index: function() {
      App.mainRegion.show(new Rds());
    },

    createRds: function() {
      App.mainRegion.show(new Rds());
    },

    updateRds: function(id) {
      App.mainRegion.show(new Rds({id: id}));
    },
  });
});
