/*
	Author: Kevin H Avila H (@UvealSnow)
	Client: Sueet

	This is the front-end manager of the sueetApp web application.

	To do: 
		- Implement a back-end methnod to validate sessions to acces services
		- Make it so that services will reject clients that fail verification or use any other method (other than get/post)
		- Make most of the functions and forms of each view

	Currently working on:
		- dashboard/main building show/hide (front-end)
		- dashboard/main building show/hide (back-end)
*/

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
				controller: 'dashCtrl',
				resolve: {
					auth: ['$q', '$window', function ($q, $window) {
						var info = $window.sessionStorage["info"];
						if (info) {
							return $q.when(info);
						}
						else {
							return $q.reject({ log: false });
						}
					}]
				}
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
				controller: ['$scope', 'loginServ', function ($scope, loginServ) {
					$scope.login = function () {
						loginServ.login($scope.user, $scope.pass);
					}
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

					}],
					controllerAs: 'menuCtrl'
				};
			});
		/* dashboard multi toolbar */
			app.directive('dashTools', function () {
				return {
					restrict: 'E',
					templateUrl: 'templates/ui/dash-tools.html',
					controller: ['$scope', '$location', '$cookies', 'getHttp', function ($scope, $location, $cookies, getHttp) {
						var url = $location.path().slice(1).replace('/', '-');
						getHttp.getInfo('json/' + url + '.json').success(function (d) {
							$scope.res = d;
						});
						$scope.selectValue = function () {
							if ($scope.res) $cookies.put('dd', $scope.res.selected.val); // set cookie to w/e value was selected
							else $cookies.put('dd', 'all'); // no cookies set yet, default to show-all
						};
						$scope.selectValue();
					}],
					controllerAs: 'toolsCtrl'
				};
			});
/* controllers */
	/* Main dashboard controller */
		app.controller('dashCtrl', ['$scope', 'getHttp', '$routeParams', '$window', '$location', 'logoutServ', function ($scope, getHttp, $routeParams, $window, $location, logoutServ) {
			function init () {
				if ($window.sessionStorage['info']) info = JSON.parse($window.sessionStorage['info']);
			}
			init();
			var url = 'templates/partials/dash-';
			$scope.template = url+$routeParams.action+'.html';
			if (!$routeParams.action) $scope.template = url+'main.html';
			else {
				$scope.req = getHttp.getInfo($scope.template).then(function successCallback () {}, function errorCallback () {
					$scope.template = url+'404.html'; // not found, go to dash 404
				});
			};
			$scope.logout = function () { logoutServ.logout(); $location.path('/login'); }
		}]);
	/* dashboard-main controller */	
		app.controller('mainDashCtrl', ['$scope', '$http', '$window', '$cookies', function ($scope, $http, $window, $cookies) {
			$scope.populate = function () {
				var user = $window.sessionStorage['info'].id;
				var view = $cookies.get('dd');
				/*$http.post('php/services/properties/getProperties.php', { user: user , view: view })
				.then(function (res) {
					$scope.properties = res.data;
					console.log(res.data);
				}, function () {
					// to do: add error callback
				});*/
			};	
			console.log($window.sessionStorage["info"].id);
			$scope.populate();
		}]);
/* services */
	app.factory('getHttp', ['$http', function ($http) {
		return {
			getInfo: function (url) {
				return $http.get(url);
			}
		};
	}]);
	/* login service */
		app.factory('loginServ', ['$http', '$q', '$window', '$location', function ($http, $q, $window, $location) {
			var info;
			function login(user, pass) {
				var def = $q.defer();
				$http({
					method: 'GET',
					url: 'php/services/login/login.php',
					params: { user: user, pass: pass }
				}).then(function (res) {
					var info = {
						token: res.data.token,
						id: res.data.id, // THIS MUST BE PATCHED ASAP, THE CORRECT WAY TO DO IT SHOULD BE ENCRYPTED
						ttl: res.data.ttl,
						name: res.data.name
					};
					$window.sessionStorage['info'] = JSON.stringify(info);
					$location.path('/dashboard');
					def.resolve(info);
					console.log(res.data);
				}, function () {
					$location.path('/login');
					der.reject(error);
				});
			}
			return {
				login: login
			};
		}]);
	/* logout service */
		app.factory('logoutServ', ['$http', '$window', '$location', '$q', function ($http, $window, $location, $q) {
			function logout () { 
				var def = $q.defer();
				var token = $window.sessionStorage["info"].token;
				$http.post('php/services/login/logout.php', { token: token })
				.then(function (res) {
					$window.sessionStorage["info"] = null;
					info = null;
					def.resolve.result(result);
					$location.path('/login'); // this line is not working properly
					console.log($window.sessionStorage["info"]);
				}); // add an error callback, maybe?
			}
			return {
				logout: logout
			}
		}]);
/* root scope redirections */
	app.run(['$rootScope', '$location', function ($rootScope, $location) {
		$rootScope.$on('$routeChangeSuccess', function (info) {
			// console.log(info);
		});
		$rootScope.$on('$routeChangeError', function (e, c, p, o) {
			if (o.auth === false) {
				$location.path('/login');
			}
		});
	}]);