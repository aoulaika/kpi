'use strict';
var app = angular.module('app', ["xeditable"]);
app.config(function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});
app.run(function(editableOptions, editableThemes) {
  editableThemes.bs3.inputClass = 'input-sm';
  editableThemes.bs3.buttonsClass = 'btn-sm';
  editableOptions.theme = 'bs3';
});
app.controller('ctrl', function($scope,$http) {

  $http.get('getUsers')
  .success(function (response) {
    response.teams.unshift({value: 0, text: ""});
    $scope.teams=response.teams;
    $scope.selectedTeam = {value: 0, text: ""};
    $scope.items=response.users;
    console.log(response.users);
  })
  .error(function(response) {
    console.log('Error getUsers');
    console.log(response);
  })

  $scope.languages = [];
  $scope.user_languages = [];

  $scope.tools = [];
  $scope.user_tools = [];

  $http.get('getLanguages')
  .success(function (response) {
    $scope.languages=response.languages;
  })
  .error(function(response) {
    console.log('Error getLanguages');
    console.log(response);
  });

  $http.get('getTools')
  .success(function (response) {
    $scope.tools=response.tools;
  })
  .error(function(response) {
    console.log('Error getTools');
    console.log(response);
  });

  $http.get('getAccount')
  .success(function (response) {
    $scope.accounts=response.accounts;
  })
  .error(function(response) {
    console.log('Error getAccount');
    console.log(response);
  });

  $http.get('getSubAccount')
  .success(function (response) {
    $scope.sub_accounts=response.sub_accounts;
  })
  .error(function(response) {
    console.log('Error getSubAccount');
    console.log(response);
  });

  $scope.searchText = "";

  $scope.status = [
  {value: "Active", text: "Active"},
  {value: "Inactive", text: "Inactive"}
  ];

  $scope.computers = [
  {value: "Desktop", text: "Desktop"},
  {value: "Laptop", text: "Laptop"},
  {value: "Both", text: "Both"}
  ];

  $scope.grades = [
  {value: "A", text: "A"},
  {value: "B", text: "B"},
  {value: "C", text: "C"},
  {value: "D", text: "D"}
  ];

  $scope.jobes = [
  {value: "Director", text: "Director"},
  {value: "OPS Manager", text: "OPS Manager"},
  {value: "SDS",text: "SDS"},
  {value: "SME", text: "SME"},
  {value: "Agent",text: "Agent"}
  ];

  $scope.roles = [
  {value: "Coach", text: "Coach"},
  {value: "CSR", text: "CSR"},
  {value: "KA", text: "KA"},
  {value: "N1", text: "N1"},
  {value: "N2", text: "N2"},
  {value: "QA", text: "QA"},
  {value: "SDM", text: "SDM"},
  {value: "SDS", text: "SDS"},
  {value: "SME", text: "SME"},
  {value: "TL", text: "TL"},
  {value: "Trainer", text: "Trainer"},
  {value: "WFA", text: "WFA"},
  ];

  $scope.editItem = function(item){
    $scope.selectedItem = item;
    item.originalItem = angular.copy(item);

    $http.post('getTeamName', {id:item.team})
    .then(function (response) {
      if(response.data.team_name){
        $scope.selectedItem.team_name=response.data.team_name.name;
      }
    })
    .then(function (response) {
      console.log('Error getTeamName');
      console.log(response);
    });

    $http.post('getUserLanguages', {id:item.Id})
    .then(function (response) {
      $scope.user_languages = response.data.languages;
    })
    .then(function (response) {
      console.log('Error getUserLanguages');
      console.log(response);
    });

    $http.post('getUserTools', {id:item.Id})
    .then(function (response) {
      $scope.user_tools=response.data.tools;
    })
    .then(function (response) {
      console.log('Error getUserTools');
      console.log(response);
    });

    $http.post('getUserTools', {id:item.Id})
    .then(function (response) {
      $scope.user_tools=response.data.tools;
    })
    .then(function (response) {
      console.log('Error getUserTools');
      console.log(response);
    });

    $http.post('polarData', {id:item.Id})
    .then(function (response) {
      console.log(response.data.data);
      showRadar(response.data.data);
    })
    .then(function (response) {
      console.log('Error polarData');
      console.log(response);
    });

  };

  $scope.editUser = function(id, attribut, data) {
    if (attribut != 'team' && data == '' ) {
      return attribut+" shouldn't be `empty`";
    }else{
      if(attribut == 'language'){
        $http.post('editUserLanguage', {Id:id, attr:attribut, add:data, remove:$scope.user_languages})
        .then(function (response) {
          $scope.user_languages = data;
        })
        .then(function (response) {
          console.log('Error getUserLanguages');
          console.log(response);
        });
      }else if(attribut == 'tool'){
        $http.post('editUserTools', {Id:id, attr:attribut, add:data, remove:$scope.user_tools})
        .then(function (response) {
          $scope.user_tools = data;
        })
        .then(function (response) {
         console.log('Error editUserTools');
         console.log(response);
       });
      }else{
        if(attribut == 'team'){
          $http.post('getTeamName', {id:data})
          .then(function (response) {
            if(response.data.team_name){
              $scope.selectedItem.team_name=response.data.team_name.name;
            }
          })
          .then(function (response) {
           console.log('Error getTeamName');
           console.log(response);
         });
        }
        $http.post('editUser', {Id:id, attr:attribut, value:data})
        .then(function (response) {
          $scope.selectedItem[attribut]=data;
          $scope.items=response.data.users;
        })
        .then(function (response) {
          console.log('Error editUser');
          console.log(response);
        });
      }
    }
  };

  $scope.showLanguages = function () {
   var selected = [];
   angular.forEach($scope.languages, function(s) { 
    if ($scope.user_languages.indexOf(s.Id) >= 0) {
      selected.push(s.name);
    }
  });
   return selected.length ? selected.join(', ')+'.' : 'Not set';
 };

 $scope.showTools = function () {
   var selected = [];
   angular.forEach($scope.tools, function(s) { 
    if ($scope.user_tools.indexOf(s.Id) >= 0) {
      selected.push(s.name);
    }
  });
   return selected.length ? selected.join(', ')+'.' : 'Not set';
 };

});