(function(){

    bharatApp.factory('editEmployeeService',['$q','$http',editEmployeeService]);
    
    function editEmployeeService($q,$http)
    {
        return{
            listEmployees : listEmployees         
            

        }
        
        function  listEmployees()
        {
             return $http({method: 'POST',url:HOSTNEW+GETALLORDERS}).then(function(response){
                   return response.data;
               })
              .catch(function(error){
                   alert(JSON.stringify(error));
               });
        }
        
       
        
    }
    
    
    
    
    
    
    
  
}());

