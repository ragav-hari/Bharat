"use strict";
var bharat = angular.module('bharat',['ui.router','ngAnimate','ngTouch','ngFileUpload','ui.bootstrap']);

bharat.config(["$stateProvider","$urlRouterProvider",function(stateProvider,urlRouterProvider){
    urlRouterProvider
    .when('/dashboard','/dashboard/manageorders'),
    urlRouterProvider
    .otherwise('/login'),
    stateProvider
    .state("base", {"abstract": !0,url: "",templateUrl: "Customercare/views/base.html"})
    .state("login", {url: "/login",parent: "base",templateUrl: "Customercare/views/login/login.html",controller: "userController"})
    .state("dashboard",{url: "/dashboard",templateUrl : "Customercare/views/dashboard.html", parent: "base",controller  : 'userController'})
    .state("manageorders",{url: "/manageorders",parent: "dashboard",templateUrl: "Customercare/views/orders/manageorders.html"})
    .state("vieworderdetail",{url: "/vieworderDetail/{order_id}",parent: "dashboard",templateUrl: "Customercare/views/orders/vieworderdetail.html"});
}])
 angular.module("bharat").controller("dashboardController", ["$scope", "$state", function(r, t) {
    r.$state = t
}]);

