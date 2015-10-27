(function(){
    
    bharat.controller('userController',['$scope','$location','userService','$modal','$state','usSpinnerService',userController]);
    
    function userController($scope,$location,userService,$modal,$state,usSpinnerService)
    {
        $scope.codesend      = false;
        $scope.codeverified  = false; 
        $scope.forgotinitial = true;
        
        $scope.startSpin = function()
        {
            usSpinnerService.spin('spinner-1');
        }
        $scope.stopSpin = function()
        {
            usSpinnerService.stop('spinner-1');
        }
        
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
            $scope.startSpin();
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
                $scope.stopSpin();
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
            $scope.startSpin();    
            
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
               $scope.stopSpin();
            });
        }
        
        $scope.getAllUsersForAllocation = function()
        {
            $scope.startSpin();
            userService.getAllUsers().then(function(response){
             if(response[0].status === "Success")
               {
                   $scope.allocuserlist = response;
               }
               else
               {
                   $scope.allocuserlist = [];
               }
               $scope.stopSpin();
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
            $scope.startSpin();
            userService.getUserDataByID(data).then(function(response){
                   $scope.id   = response[0].id;
                   $scope.bh_id = response[0].bh_id;
                   $scope.bh_name = response[0].bh_name;
                   $scope.bh_user_name = response[0].bh_user_name;
                   $scope.bh_mobno = response[0].bh_mobileno;
                   $scope.bh_email = response[0].bh_emailid;
                   $scope.bh_userrole = response[0].bh_user_role;
               // console.log("RES"+JSON.stringify($scope));
                   $scope.stopSpin();
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
            if($scope.bh_reenter_pwd==$scope.bh_password)
            {
                 $scope.startSpin();
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
               $scope.stopSpin();
            }); 
            }
            else
            {
                
              $scope.error_message="User Name and Password Does Not Match";
            }
          
        }
        
        $scope.editUserData = function()
        {
            $scope.startSpin();
             var data = {"id":$scope.id,"bh_id":$scope.bh_id,"bh_name":$scope.bh_name,"bh_user_name":$scope.bh_user_name,
                        "bh_mobno":$scope.bh_mobno,"bh_email":$scope.bh_email,"bh_userrole":$scope.bh_userrole};
             userService.editUserData(data).then(function(response){
                 $scope.accountstatus = response[0].message;
                 $scope.stopSpin();
             })       
        }
        
        $scope.deleteUser = function(userid)
        {
            
            var data = {"user_id":userid};
            var deleteconfirm = confirm("Do you want to delete this profile?");
            
            if(deleteconfirm == true)
            {
               $scope.startSpin();
               userService.deleteUser(data).then(function(response)
                {
                    $scope.stopSpin();
                    alert(response[0].message);
                    $state.reload();
                }) 
            }
            else
            {
                $state.reload();
            } 
        }
        
        
        $scope.forgotpassword = function()
        {
            $scope.startSpin();
            var data = {"user_email":$scope.emailid};
            userService.forgotpassword(data).then(function(response){
                console.log("hello"+JSON.stringify(response));
               if(response.status === "Success")
               {
                   $scope.codesend = true;
                   $scope.forgotinitial = false;
               }
               else
               {
                   $scope.forgotinitial = true;
                   $scope.codesend = false;
                   $scope.errormessage = response.message;
               }
               $scope.stopSpin();
            });
            
        }
        
        $scope.verifyCode = function()
        {
            $scope.startSpin();
            var data = {"user_email":$scope.emailid,"user_code":$scope.code};
            userService.verifycode(data).then(function(response){
                if(response.status === "Success")
                {
                    $scope.codeverified = true;
                    $scope.codesend = false;
                }
                else
                {
                    $scope.codesend = true;
                    $scope.codeverified = false;
                    $scope.codeerrormessage = response.message;
                }
                $scope.stopSpin();
            });
        }
        
       /* $scope.changePassword = function()
        {  
            alert("he");
            alert("called" +$scope.password);
            alert("caa"+$scope.confirmpassword);
            $scope.startSpin();
            if($scope.password !== $scope.confirmpassword)
            {
                $scope.passworderrormessage = "Passwords donot match";
            }
            else
            {
                var data = {"emailid":$scope.emailid,"password":$scope.password};
                userService.changeforgottenPassword(data).then(function(response){
                    if(response.status === "Success")
                    {
                        alert("Password changed successfully");
                        $state.go('login');
                    }
                    else
                    {
                        $scope.passworderrormessage = response.message;
                    }
                    $scope.stopSpin();
                });
            }
        }*/
        $scope.changePassword = function()
        {
            $scope.startSpin();
            if($scope.newpassword !== $scope.confirmpassword)
            {
                $scope.errormessage = "Passwords mismatch";
            }
            else
            {
                var data = {"user_id":sessionStorage.userid,"old_password":$scope.oldpassword,"new_password":$scope.newpassword};
                userService.changePassword(data).then(function(response){
                    console.log("resp"+JSON.stringify(response));
                    if(response.status === "Success")
                    {
                        alert("Password Changed Successfully");
                        sessionStorage.removeItem("userid");
                        window.localStorage.removeItem("dashboardItems");
                        $state.go("login");
                    }
                    else
                    {
                        $scope.errormessage = response.message;
                    }
                    $scope.stopSpin();
                });
            }
        }
        
        $scope.forgotpasswordChange = function()
        {
         
          var querystring = $location.search();
           $scope.codedata =querystring.code;
          $scope.email=querystring.email;
          var data = {code:$scope.codedata,email:$scope.email}
          userService.changeForgotPassword(data).then(passresponse);
           
            function passresponse(response)
            {   
                console.log("JSN"+JSON.stringify(response));
                if(response.status=="Success")
                {
                    window.localStorage.setItem("emailid",response.email); 
                    
                }
                else
                {
                    
                    $location.path("/login");
                }
            
            }
        }   
        $scope.setPassword = function()
        {
            
            //$scope.passcode is the password
            $scope.passcode;
            $scope.passcodenew;
            alert("pass"+$scope.passcode);
            alert("passcode"+$scope.passcodenew);
            
            if($scope.passcode!=$scope.passcodenew)
            {
               $scope.error_message="Password and Confirm Password Does not Match"; 
               
            }
            else
            {
                var querystring = $location.search();
                $scope.email=querystring.email;
                var data = {passcode:$scope.passcode,email:$scope.email}
                userService.setPassword(data).then(setpassresponse);
                function setpassresponse(response)
                {
                 console.log("resp is"+JSON.stringify(response));   

                }   
            }
           
        }
        
    } 
}());