/*
	Author: Kevin H Avila H (@UvealSnow)
	Client: Sueet

	This is the front-end manager of the sueetApp web application.

*/

/* module declaration */

	var app = angular.module('sueetApp', ['ui.router', 'ngCookies']);

/* redirections */
	app.config(function ($stateProvider) {

		$stateProvider
			.state('app', { url: '/', abstract: true, templateUrl: 'templates/main.html', data: { requireLogin: true } })
			// .state('app.dashboard', { url: '/dashboard', abstract: true, templateUrl: 'template.html', controller: 'controller', data: { requireLogin: true } })
			.state('login', { url: '/login', templateUrl: 'templates/misc/forms/login.html', controller: 'loginCtrl', data: { requireLogin: false } })
			.state('404', { url: '/404', templateUrl: 'templates/misc/layout/404.html', data: { requireLogin: false } });
	});

/* directives */

/* controllers */
	
	app.controller('loginCtrl', ['$scope', '$http', '$cookies', '$window', function ($scope, $http, $cookies, $window) {

		// console.log('loginCtrl loaded');

		$scope.login = function () {
			var info = { user: $scope.user, pass: $scope.pass };
			var url = 'http://sueetapp.dev/SueetApi/public/login';

			$http.post(url, info).then( function (res) { 
				$cookies.put('token', res.data);
				$window.location.href = '/';
			}, function (res) { 
				$cookies.put('error', res.status);
				$window.location.href = '/login';
			});
		};

	}]);

/* services */

	app.run(function($rootScope) {
	  	$rootScope.$on("$stateChangeError", console.log.bind(console));
	});

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
