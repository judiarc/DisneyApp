

(function () {
    'use strict';

   var userapp= angular
        .module('app')
        .controller('ForgotpasswordController', ForgotpasswordController);
		

    ForgotpasswordController.$inject = ['$cookieStore','$rootScope','$scope','UserService','FlashService','$location'];
    function ForgotpasswordController($cookieStore,$rootScope,$scope,UserService,FlashService,$location) {
			var vm = this;
		 	vm.forgotp = forgotp;		
    		initController();
			<!-- intialising all functions -->	   
        function initController() 
		{			
			forgotp();			
		}
		
	   function forgotp()
	   {
		 
			 $("#formforgotp").submit(function (e) {
    		 e.preventDefault();
             var formId = "submit";  // "this" is a reference to the submitted form
			 
        if (formId) {				
            		UserService.forgotpassword(vm.users).success(function (response) {
                    if (response == "authorized") {
                     FlashService.Success('new password has been send to your mail', true);
                     $location.path('/login');						
					}	
						
				   else 
				   {
			   
                        FlashService.Error('not a registerd email', true);
                        $location.path('/forgotpassword');				
					
				
			 			}		 				
					});
					
               	}
       
			})
    	};		  
  }
          

})();