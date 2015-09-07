'use strict';
var app = angular.module('languageApp', ["xeditable"]);
app.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});
app.run(function(editableOptions, editableThemes) {
	editableThemes.bs3.inputClass = 'input-sm';
	editableThemes.bs3.buttonsClass = 'btn-sm';
	editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
app.controller('languageCtrl', function($scope, $http){

	$http.get('getLanguages')
	.success(function (response) {
		$scope.languages=response.languages;
	});

	$scope.angle = function (id) {
		$(id).toggleClass('fa-angle-down fa-angle-up');
	};

	$scope.addLanguage = function (language) {
		console.log(language);
		$http.post('addlanguage', {'_token': language.token, 'name': language.name})
		.success(function(data, status, headers, config) {
			console.log(data);
			$('#myModal').modal('toggle');
			$('#language').val('');
			$scope.languages=data.languages;
		});
	};

	$scope.deleteLanguage = function(id) {
		$http.post('deletelanguage', {'id': id})
		.success(function(data, status, headers, config) {
			console.log(data);
			$scope.languages=data.languages;
		});
	};

	$scope.edit = function(data, id) {
		if (data == '') {
			return "language shouldn't be `empty`";
		}else{
			$http.post('editlanguage', {'id': id, 'name': data})
			.success(function(data, status, headers, config) {
				console.log(data);
			});
		}
	};
});