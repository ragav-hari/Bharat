/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function RESTCALL(method,url,data,$http)
{
     return $http({method: method,url:url,data:data});
}