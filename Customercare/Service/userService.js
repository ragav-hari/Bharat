(function(){

    bharat.factory('userService',['$q','$http',userService]);
    
    function userService($q,$http)
    {
        return{
            addUserDetail       : addUserDetail,
            userLogin           : userLogin,
            getAllUsers         : getAllUsers,
            getUserPreloadData  : getUserPreloadData,
            getUserDataByID     : getUserDataByID,
            editUserData        : editUserData,
            deleteUser          : deleteUser
        }
        
        function userRegistration()
        {
            return $http({method: 'POST',data:data,url:HOST+REGISTER_USER}).then(function(response){return response;});
        }
        
        function userLogin(data)
        {
            return $http({method: 'POST',data:data,url:HOST+LOGIN_USER}).then(function(response){return response.data;});
        }
        
        function getAllUsers()
        {
            return $http({method: 'POST',url:HOST+GETALLUSERS}).then(function(response){return response.data;});
        }
        
        function getUserPreloadData()
        {
            return $http({method: 'POST',url:HOST+GETUSERPRELOADDATA}).then(function(response){return response.data;});
        }
        
        function addUserDetail(data)
        {
            return $http({method: 'POST',data:data,url:HOST+ADD_USER_DETAIL}).then(function(response){return response.data;});
        }
        
        function getUserDataByID(data)
        {
            return $http({method: 'POST',data:data,url:HOST+GET_USER_DATA_BY_ID}).then(function(response){return response.data;});
        }
        
        function editUserData(data)
        {
            return $http({method: 'POST',data:data,url:HOST+EDIT_USER_DATA}).then(function(response){return response.data;});
        }
        
        function deleteUser(data)
        {
            return $http({method: 'POST',data:data,url:HOST+DELETE_USER_DATA}).then(function(response){return response.data;});
        }
    }
}())
        