(function(){
    
    bharat.controller('ordersController',['$scope','$location','ordersService','$filter',ordersController]);
    
    function ordersController($scope,$location,ordersService,$filter)
    {
        
        $scope.noorders = false;
        
         // Function to get all orders
        $scope.getAllOrders = function()
        {
            $scope.orderList = [];
            $scope.date = $filter('date')($scope.date,'yyyy-MM-dd'); 
            
            ordersService.getAllOrders({"date":$scope.date}).then(function(response){
                    
                if(response[0].status == "Success")
                {
                   $scope.noorders = false; 
                   $scope.orderList = response;
                }
                else
                {
                    $scope.noorders = true;
                }
            })
        }
        
        
        
        
       /* Date Related Coding Starts*/
       $scope.today = function() {
        $scope.date = new Date();
       };
       $scope.today();

        $scope.clear = function () {
          $scope.date = null;
        };

        $scope.open = function($event) {
          $scope.status.opened = true;
        };

        $scope.dateOptions = {
          formatYear: 'yy',
          startingDay: 1
        };

        $scope.status = {
          opened: false
        };
        
        $scope.formats = ['yyyy-MM-dd', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        /* Date Related Coding Ends*/

        
    }
    
   
    
}())
    