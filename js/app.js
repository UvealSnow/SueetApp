/*
	Author: Kevin H Avila H (@UvealSnow)
	Client: Sueet

	This is the front-end manager of the sueetApp web application.

*/

/* module declaration */

	var app = angular.module('sueetApp', ['ui.router', 'ngCookies', 'base64']);

/* redirections */

	app.config(function ($stateProvider, $urlRouterProvider) {

		$urlRouterProvider.otherwise("/404");

		$stateProvider
			.state('home', { url: '/', templateUrl: 'templates/login.html', controller: 'loginCtrl', data: { requriesLogin: false } })
			.state('app', { url: '/admin', abstract: true, templateUrl: 'templates/app.html', data: { requriesLogin: true } })

			.state('app.dashboard', { url: '/dashboard', abstract: true, templateUrl: 'templates/app.dashboard.html', controller: 'dashCtrl' })
			.state('app.dashboard.main', { url: '/main', templateUrl: 'templates/app.dashboard.main.html' })
			.state('app.dashboard.units', { url: '/units', templateUrl: 'templates/app.dashboard.units.html' })

			.state('app.units', { url: '/units', templateUrl: 'templates/app.units.html', controller: 'unitCtrl' })
			.state('app.unit-map', { url: '/unit-map', templateUrl: 'templates/app.unit-map.html', controller: 'unitCtrl' })
			// .state('app.units.map', { url: '/map', templateUrl: 'templates/app.units.map.html' })
			// app.dashboard.units/:id => app.units.dashboard/:id ?

			.state('app.comms', { url: '/comms', templateUrl: 'templates/app.comms.html', controller: 'commCtrl' })
			.state('app.messages', { url: '/messages', templateUrl: 'templates/app.msgs.html', controller: 'msgsCtrl' })
			.state('app.requests', { url: '/requests', templateUrl: 'templates/app.requests.html', controller: 'reqsCtrl' })
			.state('app.amenities', { url: '/amenities', templateUrl: 'templates/app.amenities.html', controller: 'amntCtrl' })
			.state('app.workers', { url: '/workers', templateUrl: 'templates/app.workers.html', controller: 'wrkrCtrl' })
			.state('app.docs', { url: '/docs', templateUrl: 'templates/app.docs.html', controller: 'docsCtrl' })
			.state('app.calendar', { url: '/calendar', templateUrl: 'templates/app.calendar.html', controller: 'caleCtrl' })

			.state('app.new', { url: '/new', abstract: true, templateUrl: 'templates/new.html' })
			.state('app.new.units', { url: '/units', templateUrl: 'templates/new.units.html', controller: 'newUnitCtrl' });
	});

/* directives */

	app.directive('dashTools', function () {
		return {
			templateUrl: "templates/dash-tools.html"
		}
	});

	app.directive('newUnitMap', function() {
	    // directive link function
	    var link = function(scope, element, attrs) {
	        var map, infoWindow;
	        var markers = [];
	        
	        // map config
	        var mapOptions = {
	        	key: 'AIzaSyC7QQKsQO1rTuLwz8N48pmVElSvhbTF_o4',
	            center: new google.maps.LatLng(19.412, -99.174),
	            zoom: 10,
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
	            scrollwheel: true
	        };
	        
	        // init the map
	        function initMap() {
	            if (map === void 0) {
	                map = new google.maps.Map(element[0], mapOptions);
	            }
	        }    

	        var myMarker = new google.maps.Marker({
	            position: new google.maps.LatLng(19.412, -99.174),
	            draggable: true
	        });
	        
	        // show the map and place some markers
	        initMap();
	        
	        google.maps.event.addListener(myMarker, 'dragend', function (evt) {
	        	document.getElementById('lat').value = evt.latLng.lat();
	        	document.getElementById('lng').value = evt.latLng.lng();
	        });

	        map.setCenter(myMarker.position);
	        myMarker.setMap(map);

	    };
	    
	    return {
	        restrict: 'A',
	        template: '<div id="gmaps"></div>',
	        replace: true,
	        link: link
	    };
	});

	app.directive('seeMap', function() {
	    // directive link function
	    var link = function(scope, element, attrs) {
	        var map, infoWindow;
	        var markers = [];
	        
	        // map config
	        var mapOptions = {
	        	key: 'AIzaSyC7QQKsQO1rTuLwz8N48pmVElSvhbTF_o4',
	            center: new google.maps.LatLng(19.412, -99.174),
	            zoom: 10,
	            mapTypeId: google.maps.MapTypeId.ROADMAP,
	            scrollwheel: true
	        };
	        
	        // init the map
	        function initMap() {
	            if (map === void 0) {
	                map = new google.maps.Map(element[0], mapOptions);
	            }
	        }    
	        
	        // show the map and place some markers
	        initMap();

	        myMarker.setMap(map);

	    };
	    
	    return {
	        restrict: 'A',
	        template: '<div id="gmaps"></div>',
	        replace: true,
	        link: link
	    };
	});


	// app.directive('')

/* controllers */

	app.controller('loginCtrl', ['$scope', '$http', '$cookies', '$window', '$base64', function ($scope, $http, $cookies, $window, $base64) {
		$scope.error = $cookies.get('error');
		// console.log('loginCtrl loaded');

		$scope.login = function () {
			$cookies.remove('error');

			var info = { user: $scope.user, pass: $scope.pass };
			var url = 'SueetApi/public/login';

			$http.post(url, info).then( function (res) { 
				$scope.token = res.data.split('.');
				$scope.decoded = JSON.parse(decodeURIComponent(escape($base64.decode($scope.token[1]))));
				$cookies.putObject('token', $scope.decoded);
				$window.location.href = '#/admin/dashboard/main';
			}, function (res) { 
				$cookies.put('error', res.status);
				$window.location.href = '#/';
			});
		};
	}]);

	app.controller('menuCtrl', ['$scope', function ($scope) {
		$scope.active = false;
	}]);

	app.controller('unitCtrl', ['$scope', '$http', '$cookies', '$base64', function ($scope, $http, $cookies, $base64) {
		var token = JSON.parse($cookies.get('token'));
		// console.log(token);
		var uid = token.uid;
		var apiUrl = 'SueetApi/public/users/'+uid+'/units';
		$http({
			method: 'GET',
			url: apiUrl,
		}).then(function (res) {
			$scope.properties = res.data;
			// console.log($scope.properties);
		}, function (res) {
			console.log('error: ' + res.status);
		});
	}]);

	app.controller('newUnitCtrl', ['$scope', '$http', '$cookies', '$base64', function ($scope, $http, $cookies, $base64) {
		$scope.token = JSON.parse($cookies.get('token'));
		// console.log($scope.token);

		$http({
			method: "GET",
			url: "SueetApi/public/users/"+$scope.token.uid,
		}).then(function (res) {
			$scope.data = res.data;
			// console.log(res.data);
		}, function (res) {
			console.log("error: " + res.status);
		})
		
		$scope.type = true;
		$scope.towers = [{}];

		$scope.changeType = function () {
			$scope.type = !$scope.type;
		}

		$scope.addTower = function () {
			$scope.towers.push({});
		}

		$scope.removeTower = function () {
			if ($scope.towers.length > 1) $scope.towers.pop();
		}

	}]);

	/* useless */
		app.controller('dashCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('commCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('msgsCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('reqsCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('amntCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('wrkrCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('docsCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

		app.controller('caleCtrl', ['$scope', function ($scope) {
			console.log('main ctrl');
		}]);

/* services */

	/*
	app.run(function ($rootScope, $state, $cookies) {
	  	$rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
	    	var requireLogin = toState.data.requireLogin;
	    	var token = $cookies.get('token');
	    	console.log('token: '+token);

		    if (requireLogin) {
		      	// event.preventDefault();
		      	console.log('this page requires login');
		    }
	  	});
	});
	*/
