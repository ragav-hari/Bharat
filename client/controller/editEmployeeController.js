(function(){
    
    bharatApp.controller('editEmployeeController',['$q','$scope','$rootScope','$location', 'Upload', '$timeout','ordersService',editEmployeeController]);
    
    function editEmployeeController($q,$scope,$rootScope,$location,Upload,$timeout,ordersService)
    {
       
     
  
       $scope.gridOptions = { enableCellEditOnFocus: true };

   $scope.gridOptions.columnDefs = [
         { name: 'id' ,enableCellEdit: false},
         {  name: 'bh_id'},
         { name: 'bh_user_name'},
         { name: 'bh_mobileno' },
          {name : 'bh_emailid'}
       ];
       
        $scope.msg = {};

 $scope.gridOptions.onRegisterApi = function(gridApi){
          //set gridApi on scope
          $scope.gridApi = gridApi;
          gridApi.edit.on.afterCellEdit($scope,function(rowEntity, colDef, newValue, oldValue){
            $scope.msg.lastCellEdited = 'edited row id:' + rowEntity.id + ' Column:' + colDef.name + ' newValue:' + newValue + ' oldValue:' + oldValue ;
            $scope.$apply();
          });
        };

 
       
     
    }

}());