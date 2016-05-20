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
	/* dashboard directives */
		app.directive('dashNav', function () {
			return {
				restrict: 'E',
				templateUrl: 'templates/ui/navbar.html'
			};
		});
	/* login directive */
		app.directive('loginForm', ['$scope', '$http', '$cookies', function ($scope, $http, $cookies) {
			return {
				restrict: 'E',
				templateUrl: 'templates/forms/login-main.html',
				controller: ['$scope', 'postHttp', '$cookies', function ($scope, postHttp, $cookies) {
					var info = { user: $scope.user, pass: $scope.pass };
					$scope.login = function () {
						$scope.res = postHttp.sendInfo(info, 'login.php');
						if ($scope.res) console.log('login'); // go to dash
						else console.log('error'); // don't leave login, raise flag			
					};
				}],
				controllerAs: 'loginFormCtrl'
			};
		}]);
/* controllers */
	app.controller('dashCtrl', ['$scope', 'getHttp', '$routeParams', function ($scope, getHttp, $routeParams) {
		var url = 'templates/partials/dash-';
		$scope.template = url+$routeParams.action+'.html';
		if (!$routeParams.action) $scope.template = url+'main.html';
		else {
			$scope.req = getHttp.getInfo($scope.template).then(function successCallback () {}, function errorCallback () {
				$scope.template = url+'404.html'; // not found, go to dash 404
			});
		};
	}]);
/* services */
	app.factory('postHttp', ['$http', function ($http) {
		return {
			sendInfo: function (info, url) {
				return $http.post(url, info);
			}
		};
	}]);
	app.factory('getHttp', ['$http', function ($http) {
		return {
			getInfo: function (url) {
				return $http.get(url);
			}
		};
	}]);