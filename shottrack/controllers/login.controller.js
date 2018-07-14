(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'AuthenticationService', 'FlashService'];
    function LoginController($location, AuthenticationService, FlashService) {
	   var vm = this;

        vm.login = login;

        (function initController() {
            // reset login status
        	clearcookies();
        	
            AuthenticationService.ClearCredentials();
        })();
			 // login function
        function login() {
            vm.dataLoading = true;
            AuthenticationService.Login(vm.username, vm.password, function (response) {
                if (response.success) {
                    AuthenticationService.SetCredentials(vm.username, vm.password);
                    $location.path('/dashboard/1');
					
                } else {
                    FlashService.Error(response.message);
                    vm.dataLoading = false;
                }
               
            });
        };
        function clearcookies(){
        	document.cookie.split(";").forEach(function(c) { 
        		document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        		});
        }
    }

})();
