var bharatApp = angular.module('bharatApp',['ngRoute','ui.grid','ngTouch','ui.grid.selection','ngFileUpload']);

bharatApp.config(function($routeProvider){
    $routeProvider
    .when('/dashboard', {templateUrl : 'client/view/dashboard/dashboard.html',controller  : 'customerController'})
  .when('/vieworder', {templateUrl : 'client/view/orders/partials/viewOrder.html',controller  : 'ordersController'})
  
  .when('/fullorder', {templateUrl : 'client/view/orders/partials/viewfullOrder.html',controller  : 'ordersController'})
    .when('/sendinvoice', {templateUrl : 'client/view/orders/partials/sendInvoice.html',controller  : 'ordersController'})
    .when('/addEmployee', {templateUrl : 'client/view/user/partials/addemployee.html',controller  : 'userController'})
     .when('/validateEmployee', {templateUrl : 'client/view/user/partials/login.html',controller  : 'userController'})
     .when('/listEmployee', {templateUrl : 'client/view/user/partials/listEmployee.html',controller  : 'userController'})
   .when('/editEmployee', {templateUrl : 'client/view/user/partials/editEmployee.html',controller  : 'userController'})
            .when('/', {templateUrl : 'client/view/dashboard.html',controller  : 'mainController'})
})