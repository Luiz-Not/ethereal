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

    bootstrap: 'lib/bootstrap/js/bootstrap.min',
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
    bootstrap: {
      deps:['jquery'],
      exports: 'bootstrap'
    },

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
    Backgrid: {
      deps: ['backbone'],
      exports: 'Backgrid'
    },
    PageableCollection: {
      deps: ['backbone'],
      exports: 'backbone'
    },

    BackgridPaginator: {
      deps: ['Backgrid'],
      exports: 'BackgridPaginator'
    },

    BackgridAllCell: {
      deps: ['Backgrid'],
      exports: 'BackgridAllCell'
    },

    BackgridFilter: {
      deps: ['Backgrid', 'lunr'],
      exports: 'Backgrid'
    },
  }
});

require(['backbone', 'marionette','underscore','subroute','text', 'BackboneStick', 'BackboneTrackit', 'bootstrap' ], function() {

  App = new Backbone.Marionette.Application();
  App.Routers = {};
  App.View = {};
  App.BaseUrl = 'http://localhost/ethereal/';

  var MainRouter = Backbone.Router.extend({
    routes: {
      '': 'dashboard',
      'rds/*subroute': 'rdsModule',
      'menu/*subroute': 'menuModule',
      'clientes/*subroute': 'clienteModule',
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

    clienteModule: function(){
      require(['frontendPath/clientes/router'], function(Clientes){
        App.Routers.Clientes = new Clientes('clientes/');
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
      require(['frontendPath/dashboard/views/dashboard', 'frontendPath/menu/views/menu'], function(dashboard, menu) {
        App.mainRegion.show(new dashboard());
        App.menuRegion.show(new menu());

        Backbone.history.start();
      });
    }
  });

  App.start();
});

