(function(){
    
    bharat.controller('ordersController',['$scope','$location','ordersService','$filter','Upload','$timeout',ordersController]);
    
    function ordersController($scope,$location,ordersService,$filter,Upload,$timeout)
    {
        
        $scope.noorders = false;
        
         // Function to get all orders
        $scope.getAllOrders = function()
        {
            $scope.orderList = [];
            $scope.date = $filter('date')($scope.date,'yyyy-MM-dd'); 
            
            ordersService.getAllOrders({"date":$scope.date}).then(function(response){
                    
                if(response[0].status == "Success")
                {
                   $scope.noorders = false; 
                   $scope.orderList = response;
                }
                else
                {
                    $scope.noorders = true;
                }
            })
        }
        
        // Function to get complete details of an order
        $scope.getSingleOrderDetail = function()
        {
           $scope.order_id = $location.search().order_id;
           ordersService.getSingleOrderDetail({"order_id":$scope.order_id}).then(function(response){
               console.log(JSON.stringify(response));
               if(response.status === "Success")
               {
                        $scope.noOrderDetail       =   false;
                        $scope.order_date          =   response.order_date;
                        $scope.order_status        =   response.order_status; 
                        $scope.assignCustomerDetail(response);
                        
                        // check if order item exists
                        if(response.order_items[0].status === "Success")
                        {
                            $scope.hasOrderItem = true;
                            $scope.assignOrderItems(response.order_items);    
                        }
                        else
                        {
                            $scope.hasOrderItem = false;
                        }
                        
                        // check if amount detals exists
                        if(response.amountDetails.status === "Success")
                        {
                            $scope.hasAmountDetails = true;
                            $scope.assignAmountDetails(response.amountDetails);
                            $scope.ordertype        = "Placed";
                        }
                        else
                        {
                            $scope.ordertype        = "Quote";
                            $scope.hasAmountDetails = false;
                        }
                        
                        // check if gift details exists
                        if(response.giftDetails.status === "Success")
                        {
                            $scope.hasGiftDetails = true;
                            $scope.assignGiftDetails(response.giftDetails);
                        }
                        else
                        {
                            $scope.hasGiftDetails = false;
                        }
                        
                        // check if invoice exists
                        if(response.invoiceDetails.status === "Success")
                        {
                            console.log("SUCC"+response.invoiceDetails);
                            $scope.hasInvoiceDetails = true;
                            $scope.assigninvoiceDetails(response.invoiceDetails);
                        }
                        else
                        {
                            console.log("FAIL"+response.invoiceDetails);
                            $scope.hasInviceDetails = false;
                        }
               }
               else
               {
                   $scope.noOrderDetail = true;
               }
           }); 
        }
        
        
        $scope.assignCustomerDetail     =    function(response)
        {
            $scope.first_name           =    response.first_name;
            $scope.email_id             =    response.email_id;
            $scope.address              =    response.address;
            $scope.pincode              =    response.pincode;
            $scope.landmark             =    response.landmark;
            $scope.usertype_name        =    response.usertype_name;
            $scope.dealer_code          =    response.dealer_code;
            $scope.comments             =    response.comments;
            $scope.mobile_no            =    response.user_mobileno;
        }
        
        $scope.assignOrderItems         =    function(response)
        {
            $scope.orderItems           =    [];
            $scope.itemType             =    response[0].item_type;
            $scope.orderItems           =    response;  
        }
        
        $scope.assignAmountDetails      =    function(response)
        {
            $scope.amount_from          =    response.amount_from;
            $scope.amount_to            =    response.amount_to;
        }
        
        $scope.assignGiftDetails        =    function(response)
        {
            $scope.gift_name            =    response.gift_name;
        }
        
        $scope.assigninvoiceDetails     =    function(response)
        {
            $scope.invoiceDetails       =    [];
            $scope.invoiceDetails       =    response;
            console.log("IN"+$scope.invoiceDetails);
        }
        
        
        
       /* Date Related Coding Starts*/
        $scope.today = function() {
         $scope.date = new Date();
        };
        
        $scope.today();

        $scope.clear = function () {
          $scope.date = null;
        };

        $scope.open = function($event) {
          $scope.status.opened = true;
        };

        $scope.dateOptions = {
          formatYear: 'yy',
          startingDay: 1
        };

        $scope.status = {
          opened: false
        };
        
        $scope.formats = ['yyyy-MM-dd', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        /* Date Related Coding Ends*/
        
        
        
        /* File Upload Functionality Starts */
        
        $scope.$watch('files', function () {
            $scope.upload($scope.files);
        });
        
        $scope.$watch('file', function () {
             if ($scope.file != null) {
                $scope.upload([$scope.file]);
             }
        });
        
        $scope.log = '';
        
        $scope.upload = function (files) 
        {
        
            var orders_id   = $scope.order_id;
            var userid      = sessionStorage.userid;
            var order_type  = $scope.ordertype;
            console.log("Upload Called");    
            if (files && files.length) 
            {
                for (var i = 0; i < files.length; i++) 
                {
                  var file = files[i];
                  if (!file.$error) 
                  {
                        Upload.upload({url: HOST+FILEUPLOAD, method: 'POST',file: file,
                            fields: {
                            'order_id'   : orders_id,
                            'user_id'    : userid,
                            'order_type' : order_type
                            },
                        sendFieldsAs: 'form',
                        }).progress(function (evt) 
                        {
                        $scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        })
                        .success(function (data, status, headers, config) 
                        {

                            if(data.status === "Success")
                            {
                                $scope.fileurl = data.file_path;
                                $scope.hasInvoiceDetails = true;
                            }

                        $timeout(function() {
                             // $scope.log='file: ' + config.file.name;
                            // $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                         });
                     });
                   }
                 }
              }
        };
        
        /* File Upload Functionality Ends */

        
    }
    
   
    
}())
    