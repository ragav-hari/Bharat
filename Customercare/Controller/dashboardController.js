(function(){
    
    bharat.controller('dashboardController',['$scope','$location','$state',dashboardController]);
    
    function dashboardController($scope,$location,$state)
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
        
        $scope.logout = function()
        {
            sessionStorage.removeItem("userid");
            window.localStorage.removeItem("dashboardItems");
            $state.go("login");
        }
 
            
       
        
    }
    
}())
    