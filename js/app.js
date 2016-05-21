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
	/* login directive */
		app.directive('loginForm', function () {
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
		});
	/* dashboard directives */
		/* dashboard navbar directive */
			app.directive('dashNav', function () {
				return {
					restrict: 'E',
					templateUrl: 'templates/ui/dash-nav.html',
					controller: ['$scope', function ($scope) {
						$scope.active = false;
						$scope.toggleMenu = function () {
							$scope.active =!$scope.active;
						};
					}],
					controllerAs: 'navCtrl'
				};
			});
		/* dashboard services menu directive */
			app.directive('dashMenu', function () {
				return {
					restrict: 'E',
					templateUrl: 'templates/ui/dash-menu.html',
					controller: ['$scope', '$location', function ($scope, $location) {
						// $scope.active =
						console.log($location.path().slice(1));
					}],
					controllerAs: 'menuCtrl'
				};
			});
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