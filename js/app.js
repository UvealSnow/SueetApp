/* module declaration */
	var app = angular.module('sueetAdmin', ['ngRoute', 'ngCookies']);

/* redirections */
	app.config(function ($routeProvider) {
		$routeProvider
			.when('/login', {
				templateUrl: 'templates/layout/login.html'
			})
			.when('/dashboard/:action?', {
				templateUrl: 'templates/layout/dashboard.html',
				controller: 'dashCtrl'
			})
			.when('/404', {
				templateUrl: 'templates/layout/404.html'
			})
			.otherwise({
				redirectTo: '/404'
			});
	});
/* directives */
	app.directive('dashNav', function () {
		return {
			restrict: 'E',
			templateUrl: 'templates/ui/navbar.html'
		};
	});

/* controllers */
	app.controller('dashCtrl', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
		var url = 'templates/partials/dash-';
		$scope.template = url+$routeParams.action+'.html';
		if (!$routeParams.action) $scope.template = url+'main'+'.html';
		else {
			$http({
				method: 'GET',
				url: ''
			});
		};
	}]);