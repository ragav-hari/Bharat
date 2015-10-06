(function(){
    
    bharat.controller('dashboardController',['$scope','$location',dashboardController]);
    
    function dashboardController($scope,$location)
    {
        
        $scope.getPreloadData = function()
        {
            if(!sessionStorage.userid)
            {
                $location.path('/login');
            }
            else
            {
                $scope.dashboardItems = JSON.parse(window.localStorage.getItem("dashboardItems"));
                $scope.menuItems = $scope.dashboardItems.preloaddata.menus;
            }
        }
        
 
            
       
        
    }
    
}())
    