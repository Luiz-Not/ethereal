require.config({
  config: {
    text: {
      useXhr: function(url, protocol, hostname, port) {
        return true;
      }
    }
  },

  // Set burst to prevent any kind of cache
  waitSeconds: 0,
  locale: 'pt-br',
  baseUrl: "",

  paths: {
    frontendPath: 'frontend/modules',

    // CORE INCLUDES
    jquery: 'lib/jquery/jquery',
    backbone: 'lib/backbone/backbone',
    marionette: 'lib/marionette/lib/backbone.marionette',
    subroute: 'lib/backbone.subroute/backbone.subroute',
    text: 'lib/text/text',

    underscore: 'lib/underscore/underscore',

    BackboneStick: 'lib/backbone.stickit/backbone.stickit',
    BackboneTrackit: 'lib/backbone.trackit/backbone.trackit',
    
  },

  shim: {

    backbone: {
      deps: ['underscore', 'jquery'],
      exports: 'Backbone',
    },

    marionette: {
      deps: ['backbone'],
    },

    BackboneStick: {
      deps: ['backbone']
    },

    BackboneTrackit: {
      deps: ['backbone'],
      exports: 'BackboneTrackit'
    },

    subroute: {
      deps: ['backbone', 'jquery'],
      exports: 'subroute'
    },
  }
});

require(['backbone', 'marionette','underscore','subroute','text', 'BackboneStick', 'BackboneTrackit'], function() {

  App = new Backbone.Marionette.Application();
  App.Routers = {};
  App.View = {};
  App.BaseUrl = 'http://localhost/ethereal/';

  var MainRouter = Backbone.Router.extend({
    routes: {
      '': 'dashboard',
      'rds/*subroute': 'rdsModule',
    },

    dashboard: function() {
      require(['frontendPath/dashboard/views/dashboard'], function(Dashboard) {
        App.mainRegion.show(new Dashboard());
      });
    },

    rdsModule: function() {
      require(['frontendPath/rds/router'], function(Rds) {
        App.Routers.Rds = new Rds('rds/');
      });
    },

  });

  // Configura o router
  App.mainRouter = new MainRouter();

  App.addInitializer(function() {

    // Configura as regioes do sistema
    App.addRegions({
      menuRegion: '#main-menu',
      mainRegion: '#main-content #content'
    });

    App.mainRegion.on('show', function(view) {
      
    });

    App.mainRegion.on('empty', function(view) {
      
    });

    //Validação do login
    if (true === false) {
      require(['frontendPath/login/views/login'], function(login) {
        App.mainRegion.show(new login());
      });
    } else {
      require(['frontendPath/dashboard/views/dashboard'], function(dashboard) {
        App.mainRegion.show(new dashboard());
        Backbone.history.start();
      });
    }
  });

  App.start();
});

