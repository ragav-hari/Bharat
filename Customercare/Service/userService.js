(function(){

    bharat.factory('userService',['$q','$http',userService]);
    
    function userService($q,$http)
    {
        return{
            userRegistration : userRegistration,
            userLogin        : userLogin
        }
        
        function userRegistration()
        {
            return $http({method: 'POST',data:data,url:HOST+REGISTER_USER}).then(function(response){return response;});
        }
        
        function userLogin(data)
        {
            return $http({method: 'POST',data:data,url:HOST+LOGIN_USER}).then(function(response){return response.data;});
        }
    }
}())
        