(function(){
    
    bharatApp.controller('customerController',['$q','$scope','customerService',customerController]);
    
    function customerController($q,$scope,customerService)
    {
       $scope.gridOptions = {
                    enablePaginationControls: true,    
                    paginationPageSizes: [2,10, 20, 30],
                    paginationPageSize: 2,
                    columnDefs: [
                      { name: 'name' },
                      { name: 'email_id' },
                      { name: 'mobile_no' }
                    ]
            };
            
       customerService.getAllCustomerDetails().then(function(response){
           console.log("SCOPE",response.data);
           $scope.gridOptions.data = response.data;
            
       });
        
    }


}());