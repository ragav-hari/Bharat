(function(){
    
    bharat.controller('userController',['$scope','$location','userService','$modal','$state',userController]);
    
    function userController($scope,$location,userService,$modal,$state)
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
        
        $scope.getAllUsers = function()
        {
            $scope.userpreload = [];
            $scope.allUsers = [];

            
            userService.getAllUsers().then(function(response){
               if(response[0].status === "Success")
               {
                   $scope.allUsers = response;
                   $scope.nousers  = false;
               }
               else
               {
                   $scope.nousers  = true;
               }
            });
            
            userService.getUserPreloadData().then(function(response){
               $scope.userpreload = response;
               console.log("PRELOAD"+JSON.stringify($scope.userpreload));
            });
        }
        
        $scope.getAllUsersForAllocation = function()
        {
            userService.getAllUsers().then(function(response){
             if(response[0].status === "Success")
               {
                   $scope.allocuserlist = response;
               }
               else
               {
                   $scope.allocuserlist = [];
               }
            });
        }
        
        $scope.animationsEnabled = true;
        
        $scope.openAddModal = function () 
        {
            var modalInstance = $modal.open({
              animation: $scope.animationsEnabled,
              templateUrl: 'addUserModalContent.html',
              controller: 'userController',
              
              resolve: {
                items: function () {
                  return $scope.items;
                }
              }
            });

            modalInstance.result.then(function (selectedItem) {
              $scope.selected = selectedItem;
            }, function () {
                $state.reload();
            });
        };
        
        
        $scope.preloadEditData = function()
        {
            $scope.userid = window.localStorage.getItem("edituserid");
            console.log("UID"+$scope.userid);
            var data = {user_id:$scope.userid};
            
            userService.getUserDataByID(data).then(function(response){
                   $scope.id   = response[0].id;
                   $scope.bh_id = response[0].bh_id;
                   $scope.bh_name = response[0].bh_name;
                   $scope.bh_user_name = response[0].bh_user_name;
                   $scope.bh_mobno = response[0].bh_mobileno;
                   $scope.bh_email = response[0].bh_emailid;
                   $scope.bh_userrole = response[0].bh_user_role;
               // console.log("RES"+JSON.stringify($scope));
            });
        }
        
        $scope.openEditModal = function(userid)
        {
           window.localStorage.setItem("edituserid",userid);
            var modalInstance = $modal.open({
              animation: $scope.animationsEnabled,
              templateUrl: 'editUserModalContent.html',
              controller: 'userController',
              
              resolve: {
                items: function () {
                  return $scope.items;
                }
              }
            });

            modalInstance.result.then(function (selectedItem) {
              $scope.selected = selectedItem;
            }, function () {
                 $state.reload();
            });
             
        }

        $scope.addUserDetail = function()
        {
            var data = {"bh_id":$scope.bh_id,"bh_name":$scope.bh_name,"bh_user_name":$scope.bh_user_name,
                        "bh_password":$scope.bh_password,"bh_mobno":$scope.bh_mobno,"bh_email":$scope.bh_email,"bh_userrole":$scope.bh_userrole};
            userService.addUserDetail(data).then(function(response){
               console.log("RES"+JSON.stringify(response));
               console.log("STAT"+response[0].status);
               if(response[0].status === "Success") 
               {
                   $scope.id = "";
                   $scope.bh_id = "";
                   $scope.bh_name = "";
                   $scope.bh_user_name = "";
                   $scope.bh_password = "";
                   $scope.bh_reenter_pwd = "";
                   $scope.bh_mobno = "";
                   $scope.bh_email = "";
                   $scope.bh_userrole = "";
                   $scope.accountstatus = response[0].message;
               }
               else
               {
                   $scope.accountstatus = response[0].message;
               }
               
            });
        }
        
        $scope.editUserData = function()
        {
             var data = {"id":$scope.id,"bh_id":$scope.bh_id,"bh_name":$scope.bh_name,"bh_user_name":$scope.bh_user_name,
                        "bh_mobno":$scope.bh_mobno,"bh_email":$scope.bh_email,"bh_userrole":$scope.bh_userrole};
             userService.editUserData(data).then(function(response){
                 $scope.accountstatus = response[0].message;
             })       
        }
        
        $scope.deleteUser = function(userid)
        {
            var data = {"user_id":userid};
            var deleteconfirm = confirm("Do you want to delete this profile?");
            
            if(deleteconfirm == true)
            {
               userService.deleteUser(data).then(function(response)
                {
                alert(response[0].message);
                $state.reload();
                }) 
            }
            else
            {
                $state.reload();
            } 
        }
        
        
        
    } 
}())