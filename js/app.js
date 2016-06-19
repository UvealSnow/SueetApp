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
	var app = angular.module('sueetAdmin', ['ngRoute', 'ngCookies', 'angular.css.injector']);
/* redirections */
	app.config(function ($routeProvider) {
		$routeProvider
			.when('/login', {
				templateUrl: 'templates/layout/login.html'
			})
			.when('/dashboard/:model?/:action?/:id?', {
				templateUrl: 'templates/layout/dashboard.html',
				controller: 'dashCtrl',
				resolve: {
					auth: ['$q', '$cookies', function ($q, $cookies) {
						var info = JSON.parse($cookies.getObject('info'));
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
			.when('/', {
				resolve: {
					auth: ['$cookies', '$location', function ($cookies, $location) {
						var info = JSON.parse($cookies.getObject('info'));
						if (info) {
							$location.path('/dashboard');
						}
						else {
							$location.path('/login');
						}
					}]
				}
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
		/* dashboard add form directive */
			/* add form directive */
				app.directive('addForm', function () {
					return {
						restrict: 'E',
						templateUrl: 'templates/forms/dashboard-add.html',
						controller: ['$scope', '$http', function ($scope, $http) {
							$scope.type = null;
							$scope.addProperty = function () {
								console.log('property added!');
							};
							$scope.changeType = function (t) {
								$scope.type = t;
								console.log($scope.type);
							}
						}], 
						controllerAs: 'addCtrl'
					};
				});
			/* new comm directive */
				app.directive('newComms', function () {
					return {
						restrict: 'E',
						templateUrl: 'templates/forms/dashboard-new_comm.html',
						controller: ['$scope', '$http', function ($scope, $http) {
							$scope.icon = 0;
							$scope.sendNew = function () {
								/* to do */
								$console.log('new comm added'); 
							}
							$scope.chooseIcon = function (icon) {
								$scope.icon = icon;
								console.log($scope.icon);
							}
						}],
						controllerAs: 'newCommsCtrl'
					};
				});
/* controllers */
	/* Main dashboard controller */
		app.controller('dashCtrl', ['$scope', 'getHttp', '$routeParams', '$cookies', '$location', 'logoutServ', function ($scope, getHttp, $routeParams, $cookies, $location, logoutServ) {
			function init () {
				if ($cookies.getObject('info')) $scope.info = JSON.parse($cookies.getObject('info'));
			}
			init();
			if ($routeParams.action != null && $routeParams.id != null) {
				$scope.template ='templates/partials/'+$routeParams.model+'-'+$routeParams.action+'.html';
				$scope.id = $routeParams.id;
			}
			else if ($routeParams.model != null) {
				$scope.template = 'templates/partials/dash-'+$routeParams.model+'.html';
			} 
			else if (!$routeParams.model) $scope.template = 'templates/partials/dash-main.html';
			else {
				$scope.req = getHttp.getInfo($scope.template).then(function successCallback () {}, function errorCallback () {
					$scope.template = url+'404.html'; // not found, go to dash 404
				});
			};
			$scope.logout = function () { logoutServ.logout(); $location.path('/login'); }
		}]);  
	/* dashboard-main controller */	
		app.controller('mainDashCtrl', ['$scope', '$http', '$cookies', '$cookies', function ($scope, $http, $cookies, $cookies) {
			$scope.populate = function () {
				// console.log('populate');
				$scope.user = JSON.parse($cookies.getObject('info')).id;
				$scope.view = $cookies.get('dd');
				$http({
					method: 'GET',
					url: 'php/services/properties/getProperties.php',
					params: { user: $scope.user, view: $scope.view }
				}).then (function (res) {
					$scope.properties = res.data;
					// console.log($scope.properties);
				});
			};	
			$scope.populate();
		}]);
	/* dashboard comms controller */
		app.controller('mainCommCtrl', ['$scope', function ($scope) {
			$scope.res = [
				{"id":1, "title":"Lobby", "text":"Mauris vel vehicula eros. Nullam nulla sapien, iaculis eget metus commodo, rutrum ornare libero. Integer quam leo, ullamcorper nec mauris sed, pulvinar molestie leo. Integer dignissim, augue sit amet aliquam elementum, purus libero facilisis orci, vel accumsan ligula ante non est. Vivamus sit amet purus id magna feugiat finibus nec sit amet leo. Etiam fringilla, dolor non condimentum laoreet.", "icon":0},
				{"id":2, "title":"Mantenimiento", "text":"Maecenas sodales eget orci sed ornare. Ut sed elementum lacus, vel molestie quam. Morbi scelerisque vehicula leo. Ut risus diam, tristique non urna non, fringilla sollicitudin metus. In quis dolor finibus, eleifend dolor vitae, semper sem. Donec sed mi lobortis, ornare elit sit amet, iaculis massa. Phasellus ullamcorper nunc eget suscipit ornare.", "icon":1},
				{"id":3, "title":"Amenidades", "text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vulputate felis augue, ut imperdiet est elementum a. Donec et arcu sagittis, accumsan eros quis, iaculis lacus. Quisque a cursus erat.", "icon":1},
			];
		}]);
		app.controller('mainMessCtrl', ['$scope', function ($scope) {
			$scope.res = [
				{"id":1, "name":"José", "number":"T4-506", "property":"Grand Polanco", "text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vulputate felis augue, ut imperdiet est elementum a. Donec et arcu sagittis, accumsan eros quis, iaculis lacus. Quisque a cursus erat.", "icon":"user-0"},
				{"id":2, "name":"Paola", "number":"02", "property":"Anzures #365", "text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vulputate felis augue, ut imperdiet est elementum a. Donec et arcu sagittis, accumsan eros quis, iaculis lacus. Quisque a cursus erat.", "icon":"user-1"},
				{"id":3, "name":"Carlos", "number":"3269", "property":"Carso", "text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vulputate felis augue, ut imperdiet est elementum a. Donec et arcu sagittis, accumsan eros quis, iaculis lacus. Quisque a cursus erat.", "icon":"user-2"},
				{"id":4, "name":"Fabiola", "number":"Administración", "property":"Grand Polanco", "text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vulputate felis augue, ut imperdiet est elementum a. Donec et arcu sagittis, accumsan eros quis, iaculis lacus. Quisque a cursus erat.", "icon":"user-3"},
			];
		}]);
		app.controller('mainAmmCtrl', ['$scope', function ($scope) {
			$scope.res = [
				{"id":1, "name":"Gimnasio", "property":"Habitacional 1", "open":"6:00", "close":"23:00", "responsible":"Juan Pérez", "img":"none"},
				{"id":2, "name":"Alberca", "property":"Habitacional 1", "open":"6:00", "close":"23:00", "responsible":"Juan Pérez", "img":"none"},
				{"id":3, "name":"Salón de Eventos", "property":"Habitacional 1", "open":"8:00", "close":"14:00", "responsible":"Juan Pérez", "img":"none"},
				{"id":4, "name":"Cancha de Tenis", "property":"Habitacional 1", "open":"6:00", "close":"23:00", "responsible":"Juan Pérez", "img":"none"}
			];
		}]);
		app.controller('mainDocs', ['$scope', function ($scope) {
			$scope.properties = [
				{"id": 1, "name":"Habitacional 1", "folders": 5, "img": "building.jpg"},
				{"id": 2, "name":"Comercial 1", "folders": 12, "img": "building.jpg"},
				{"id": 3, "name":"Habitacional 2", "folders": 9, "img": "building.jpg"},
				{"id": 4, "name":"Habitacional 3", "folders": 4, "img": "building.jpg"},
				{"id": 5, "name":"Comercial 2", "folders": 16, "img": "building.jpg"}
			];
			console.log($scope.properties);
		}]);
		/* show folder */
			app.controller('showFolder', ['$scope', function ($scope) {
				$scope.docs = [
					{"id": 1, "name":"Reglamento", "folders": 5, "ext": "doc"},
					{"id": 2, "name":"Mapa del condominio", "folders": 12, "ext": "ppt"},
					{"id": 3, "name":"Lista de empleados", "folders": 9, "ext": "xls"},
					{"id": 4, "name":"Carta abierta sobre reciclaje", "folders": 4, "ext": "png"},
					{"id": 5, "name":"Convocatoria a junta", "folders": 16, "ext": "pdf"}
				];
				console.log($scope.docs);
			}]);
	/* messages */
		app.controller('showMsg', ['$routeParams', '$scope', function ($routeParams, $scope) {
			$scope.id = $routeParams.id;
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
		app.factory('loginServ', ['$http', '$q', '$cookies', '$location', function ($http, $q, $cookies, $location) {
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
						name: res.data.name
					};
					$cookies.putObject('info', JSON.stringify(info));
					$location.path('/dashboard');
					def.resolve(info);
				}, function () {
					$location.path('/login');
					def.reject(error);
				});
			}
			return {
				login: login
			};
		}]);
	/* logout service */
		app.factory('logoutServ', ['$http', '$cookies', '$location', '$q', function ($http, $cookies, $location, $q) {
			function logout () { 
				var def = $q.defer();
				var token = $cookies.getObject('info').token;
				$http.post('php/services/login/logout.php', { token: token })
				.then(function (res) {
					$cookies.remove('info');
					def.resolve.result(res);
					$location.path('/login'); // this line is not working properly
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