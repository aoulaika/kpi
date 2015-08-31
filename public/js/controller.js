var app = angular.module('app', []);
app.config(function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});
app.controller('ctrl', function($scope,$http) {
    // set this as default, so it any elements that watch this are OK on load 
    $scope.searchText = "";
    // if you want to be able to undo an edit, you need a container to store it
    $scope.selectedItem = {
      "name": "",
      "email": "",
      "department": "",
      "imageNum": -99
    };
    // you will likely want to edit your item, so just pass the item to the edit function
    $scope.editItem = function(item){
        // make a copy of the item and store it in the item itself, just in case we want to cancel our change
        item.originalItem = angular.copy(item);
        $http.post('polarData', {agent:item}).then(function (response) {
          /*get the polar data*/
          console.log(response);
        });
        // set the selected item to our current item
        $scope.selectedItem = item;
        var data = [
        {
          className: 'Agent',
          axes: [
          {axis: "FCR", value: 0.8}, 
          {axis: "FCR Resolvable", value: 0.9},
          {axis: "Resolvable Missed", value: 0.4},
          {axis: "CI Usage", value: 0.76},  
          {axis: "EKMS Usage", value: 0.75}
          ]
        },
        {
          className: 'Average',
          axes: [
          {axis: "FCR", value: 0.76}, 
          {axis: "FCR Resolvable", value: 0.92},
          {axis: "Resolvable Missed", value: 0.2},
          {axis: "CI Usage", value: 0.7},  
          {axis: "EKMS Usage", value: 0.8}
          ]
        }
        ];
        showRadar();
      }

      $scope.adduser = function (user) {
        console.log(user);
        $http.post('adduser', {user:user}).then(function (response) {
          console.log(response);
        });
      }
    // if edit is canceled, we want to revert to the value we stored in editItem(). Aren't we clever!?
    $scope.cancelEdit = function(){
        // revert each one indivdually so we don't loose our watch & data bindings
        $scope.selectedItem.name = $scope.selectedItem.originalItem.name;
        $scope.selectedItem.email = $scope.selectedItem.originalItem.email;
        $scope.selectedItem.department = $scope.selectedItem.originalItem.department;
      }
    // getting rid of an item? No problem. Just get it's index and splice it into oblivion.
    $scope.deleteItem = function(index){
      $scope.items.splice(index, 1);
    }
    $http.get('getUsers')
    .success(function (response) {
      $scope.items=response.users;
    });
  });