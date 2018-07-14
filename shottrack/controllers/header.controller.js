(function () {
    'use strict';

    angular
        .module('app')
        .controller('HeaderController', HeaderController);

    HeaderController.$inject = ['$window','$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http','$location'];
   function HeaderController($window,$cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http,$location) {
		var vm = this;
		/*vm.user = null;
		var userid = $cookieStore.get('userid');*/
		 var username = $cookieStore.get('username');
		$scope.username = username;		
			
		var userid = $cookieStore.get('userid');
		$scope.loggedId=userid;

	       
		
		
}

})();
