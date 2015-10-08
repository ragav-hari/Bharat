"use strict";
var bharat = angular.module('bharat',['ui.router','ngAnimate','ngTouch','ngFileUpload','ui.bootstrap','valdr']);

bharat.config(["$stateProvider","$urlRouterProvider",function(stateProvider,urlRouterProvider){
    urlRouterProvider
    .when('/dashboard','/dashboard/manageorders','/dashboard/vieworderdetail','dashboard/viewallusers'),
    urlRouterProvider
    .otherwise('/login'),
    stateProvider
    .state("base", {"abstract": !0,url: "",templateUrl: "Customercare/views/base.html"})
    .state("login", {url: "/login",parent: "base",templateUrl: "Customercare/views/login/login.html",controller: "userController"})
    .state("dashboard",{url: "/dashboard",templateUrl : "Customercare/views/dashboard.html", parent: "base",controller  : 'userController'})
    .state("manageorders",{url: "/manageorders",parent: "dashboard",templateUrl: "Customercare/views/orders/manageorders.html"})
    .state("vieworderdetail",{url: "/vieworderDetail",parent: "dashboard",templateUrl: "Customercare/views/orders/vieworderdetail.html",params: {'order_id':null},controller:'ordersController'})
    .state("viewallusers",{url: "/viewallusers",parent: "dashboard",templateUrl: "Customercare/views/user/viewAllUsers.html",controller:'userController'})
    .state("allocatework",{url: "/allocatework",parent: "dashboard",templateUrl: "Customercare/views/orders/allocatework.html",controller:'ordersController'});
}])
 angular.module("bharat").controller("dashboardController", ["$scope", "$state", function(r, t) {
    r.$state = t
}]);

bharat.directive('pushnotification', function() {
  return {
    templateUrl: 'Customercare/views/orders/pushNotification.html'
  };
});

bharat.directive('adduser', function() {
  return {
    templateUrl: 'Customercare/views/user/useradd.html'
  };
});

bharat.directive('edituser', function() {
  return {
    templateUrl: 'Customercare/views/user/useredit.html'
  };
});



bharat.config(function(valdrProvider) {
    console.log("VALDRCALLED");
  valdrProvider.addConstraints({
        'User': {
      'loginid': {
        'required': {
          'message': 'Email is required.'
        }
      },
      'password': {
        'required': {
          'message': 'Password is required.'
        }
      }
  }
});
});