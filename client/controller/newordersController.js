

  bharatApp.controller('newordersController', ['$scope', '$http', '$log', '$timeout', 'uiGridConstants', function ($scope, $http, $log, $timeout) {
  $scope.gridOptions = {
    enableRowSelection: true,
    enableSelectAll: true,
     multiSelect:true,
    selectionRowHeaderWidth: 35,
    rowHeight: 35,
    showGridFooter:true
  };

  $scope.gridOptions.columnDefs = [
    { name: 'id' },
    { name: 'name'},
    { name: 'age', displayName: 'Age (not focusable)', allowCellFocus : false },
    { name: 'address.city' }
  ];

  $scope.gridOptions.multiSelect = false;

  $http.get('https://cdn.rawgit.com/angular-ui/ui-grid.info/gh-pages/data/500_complex.json')
    .success(function(data) {
      $scope.gridOptions.data = data;
      $timeout(function() {
        if($scope.gridApi.selection.selectRow){
          $scope.gridApi.selection.selectRow($scope.gridOptions.data[0]);
        }
      });
    });

    $scope.info = {};

  
    $scope.gridOptions.onRegisterApi = function(gridApi){
      //set gridApi on scope
      $scope.gridApi = gridApi;
      gridApi.selection.on.rowSelectionChanged($scope,function(row){
        var msg = 'row selected ' + row.isSelected;
         
          var dataaa= $scope.gridApi.selection.getSelectedRows();
          alert("LOL"+JSON.stringify(dataaa));
        $log.log(msg);
      });

      gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
        var msg = 'rows changed ' + rows.length;
            
        $log.log(msg);
      });
    };
}]);

 