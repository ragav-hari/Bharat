(function(){

    bharatApp.factory('userService',['$q','$http',userService]);
    
    function userService($q,$http)
    {
        return{
            addEmployee : addEmployee,
            validateEmployee :validateEmployee,
            listEmployee : listEmployee,
            getEmpDetails :  getEmpDetails,
            updateEmployee :  updateEmployee,
            deleteEmployee :  deleteEmployee,

        }
        
        function addEmployee(data)
        {
             return $http({method: 'POST',data:data,url:HOSTNEW+REGISTER_EMPLOYEE}).then(function(response){return response;});
        }
        
        function validateEmployee(data)
        {
             return $http({method: 'POST',data:data,url:HOSTNEW+VALIDATE_EMPLOYEE}).then(function(response){return response.data;});
        }
        
        
         function   listEmployee()
        {  
               return $http({
                method : 'POST',
                url:HOSTNEW+LISTEMP
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
          function   getEmpDetails(data)
        {  
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+GETEMPDETAILS
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
         function   updateEmployee(data)
        {  
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+UPDATEEMPLOYEE
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
        function   deleteEmployee(data)
        {  
               return $http({
                method : 'POST',
                data:data,
                url:HOSTNEW+DELETEEMPLOYEE
              })
              .then(function(response){
                   console.log("SERVICE DATA" +JSON.stringify(response));
                   return response.data;
               })
              .catch(function(error){
                   console.log("SERVICE ERROR" +JSON.stringify(error));
                 return error;
               });
            
        }
        
        
    }
    
  
}());