(function(){
    
    bharat.controller('userController',['$scope','$location','userService',userController]);
    
    function userController($scope,$location,userService)
    {
        
        // User Registration Function
        $scope.registerUser = function()
        {
            var registrationData = $scope.user;   
            
            // User Registration Service Call
            userService.userRegistration(registrationData).then(function(response){
                alert("User Registration"+JSON.stringify((response)));
            })
        }
        
        
        // User Login Function
        $scope.userLogin = function()
        {
            var loginData = $scope.user;
            
            // User Login Service Call
            userService.userLogin(loginData).then(function(response){
                if(response.status === "Success")
                {
                    window.localStorage.setItem("dashboardItems",JSON.stringify(response));
                    sessionStorage.userid = response.id; 
                    $location.path('/dashboard/manageorders');
                }
                else
                {
                   $scope.loginErrorMessage = LOGIN_ERROR_MESSAGE;
                }
            })
        }
        
        // Check if user is logged in
        $scope.checkLogin = function()
        {
            if(sessionStorage.userid)
            {
                $location.path('/dashboard/manageorders');
            }
        }
        
        $scope.getAllUSers = function()
        {
            userService.getAllUsers().then(function(response){
               alert(JSON.stringify(response));
            });
        }
        
        
    } 
}())