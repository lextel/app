SERVER = 'http://192.168.3.10:85';
DEFAULT_LOGIN_STATE = false;

angular.module('starter', ['ionic', 'lltao.controllers', 'lltao.services'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function($timeout) {
    if(window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
      
    // 开启服务
    window.lservice.start();
  });
})

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider

    // setup an abstract state for the tabs directive
    .state('tab', {
      url: "/tab",
      abstract: true,
      templateUrl: "templates/tabs.html"
    })

    // Each tab has its own nav history stack:
    /*
    .state('tab.dash', {
      url: '/dash',
      views: {
        'tab-dash': {
          templateUrl: 'templates/tab-dash.html',
          controller: 'DashCtrl'
        }
      }
    })
    */

    // 应用列表
    .state('tab.apps', {
      url: '/apps',
      views: {
        'tab-apps': {
          templateUrl: 'templates/tab-apps.html',
          controller: 'AppsCtrl'
        }
      }
    })
  
    // 应用详情
    .state('tab.app-detail', {
      url: '/app/:appId',
      views: {
        'tab-apps': {
          templateUrl: 'templates/app-detail.html',
          controller: 'AppDetailCtrl'
        }
      }
    })

    // 用户中心
    .state('tab.account', {
      url: '/account',
      views: {
        'tab-account': {
          templateUrl: 'templates/tab-account.html',
          controller: 'AccountCtrl'
        }
      }
    })
  
    // 安装记录
    .state('tab.log', {
      url: '/log',
      views: {
        'tab-account': {
          templateUrl: 'templates/log.html',
          controller: 'LogCtrl'
        }
      }
    })

  // 首页设置
  $urlRouterProvider.otherwise('/tab/apps');

});

