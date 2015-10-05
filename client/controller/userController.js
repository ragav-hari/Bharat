/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function(){
    
    bharatApp.controller('userController',['$scope','$location','customerService','userService',userController]);
    
    function userController($scope,$location,customerService,userService)
    {
        $scope.logout=window.localStorage.getItem("logout");
        
        $scope.allCustomerDetails = customerService.getAllCustomerDetails();
        $scope.addEmployee = function()
        {   
            
            if($scope.employee.bh_user_password==$scope.employee.bh_user_cpassword)
            {
    
            userService.addEmployee($scope.employee).then(function(response)
            {
                alert("RESP"+JSON.stringify(response));
            });  
            }
            else
            {   
                 
                $scope.form_error="Password and Confirm Password Does not Match";
            }
           
        }
        $scope.checkSession = function()
        {
        var u_id= window.sessionStorage.getItem("userid");
        if(u_id)
        {
            $location.path('/vieworder');
        }
        }
        $scope.validateEmployee = function()
        {
           var signout="logout";
           window.localStorage.setItem("logout",signout);
        
                userService.validateEmployee($scope.employeelogin).then(function(response)
            { 
              console.log("HHH"+JSON.stringify(response));
             window.localStorage.setItem("menu",JSON.stringify(response));
              
             console.log("ROLL"+response[0].user_role);
                    
                    
            window.localStorage.setItem("role",response[0].user_role);
            
                    
                if(response[0].id)
                {
                sessionStorage.setItem("userid",response[0].id);
                $location.path('/vieworder');
                }
                else
                {
                $scope.login_error="User and password does not match";
                      
                }
                
            }); 
                
           
           
            
        }
        
           $scope.gridOptions1 = {
          
        
    enableRowSelection: true,
    enableSelectAll: true,
     multiSelect:true,
    selectionRowHeaderWidth: 35,
    rowHeight: 35,
    showGridFooter:true
  };

   $scope.gridOptions1.columnDefs = [
         { name: 'id' ,enableCellEdit: false},
         {  name: 'bh_id'},
         {  name: 'bh_user_name'},
         { name: 'bh_mobileno'},
         { name: 'bh_emailid' },
         
       ];
       
         $scope.gridOptions1.multiSelect = false;
        $scope.gridOptions1.enablePaginationControls=true;
      userService.listEmployee().then(listEmpResponse);
        
        function listEmpResponse(response)
        {
            
            console.log("THE LIST OF EMP " +JSON.stringify(response));
            $scope.gridOptions1.data =response;
        }
           $scope.gridOptions1.onRegisterApi = function(gridApi){
      //set gridApi on scope
      $scope.gridApi = gridApi;
      gridApi.selection.on.rowSelectionChanged($scope,function(row){
        var msg = 'row selected ' + row.isSelected;
         
          var data= $scope.gridApi.selection.getSelectedRows();
          console.log("IIDD"+JSON.stringify(data[0].id));
          window.localStorage.setItem("editid",data[0].id);
          var editid=window.localStorage.getItem("editid");
        $scope.geteditDetails(editid);
        $location.path('/editEmployee');
      //  $log.log(msg);
      });

      gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
        var msg = 'rows changed ' + rows.length;
            
        //$log.log(msg);
      });
    };
        
      $scope.geteditDetails=function(data)
      {
         location.reload();
          console.log("HHHeee"+data); 
          var data1 = {"emp_id":data }
          userService. getEmpDetails(data1).then(getEmpResponse);
          function getEmpResponse(response)
          {
               window.localStorage.setItem("edit_id",response[0].id);
             window.localStorage.setItem("edit_bhid",response[0].bh_id);
             window.localStorage.setItem("edit_name",response[0].bh_user_name);
              window.localStorage.setItem("edit_mobileno",response[0].bh_mobileno);
              window.localStorage.setItem("edit_emailid",response[0].bh_emailid);
                
           
            //  console.log("name is "+response[0].bh_user_name);
             
          }
      }
      $scope.geteditData=function()
      {
       $scope.bh_user_name=window.localStorage.getItem("edit_id"); 
      $scope.bh_user_name=window.localStorage.getItem("edit_name");
      $scope.bh_id=window.localStorage.getItem("edit_bhid");
      $scope.bh_mobileno=window.localStorage.getItem("edit_mobileno");
      $scope.bh_emailid=window.localStorage.getItem("edit_emailid");

      }
     
        
       $scope.editEmployee=function()
       {
           
           var id=window.localStorage.getItem("edit_id");
           var name =$scope.bh_user_name;
           var bhid = $scope.bh_id;
           var mobile=$scope.bh_mobileno;
           var email = $scope.bh_emailid;
           
            var data1 = {"id":id,"bh_id":bhid,"bh_name":name,"bh_mobile":mobile,"bh_email":email}
            
          userService.updateEmployee(data1).then(updateResponse);
          
           function updateResponse(response)
           {
             console.log("JSON is" +JSON.stringify(response));  
               
           }
           
       }
       
       $scope.deleteEmployee=function()
       {
           
           var id=window.localStorage.getItem("edit_id");  
            var data1 = {"id":id}
            userService.deleteEmployee(data1).then(deleteResponse);
           function deleteResponse(response)
           {
               console.log("DEL"+JSON.stringify(response));
           }
          
           
       }
      
       $scope.getMenu = function()
       {
           $scope.menuitem = [];
          
         
         $scope.m=JSON.parse(window.localStorage.getItem("menu"));
         for(var i=0;i<$scope.m.length;i++)
         {
             $scope.menuitem.push({"menu_name":$scope.m[i].menu_name,"menu_url":$scope.m[i].menu_url});
         }
console.log("MENUITEM"+JSON.stringify($scope.menuitem));           
         
        
           
          
           
       }
       $scope.userSignout = function()
       {
        
        sessionStorage.clear();
        window.localStorage.clear();
        window.location.reload();
       }
    }
    

}());