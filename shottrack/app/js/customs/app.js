(function () {
    'use strict';

    var app=angular
        .module('app', ['ngRoute', 'ngCookies','angularUtils.directives.dirPagination','720kb.datepicker','ngMessages','datatables'],
            function($locationProvider){
                $locationProvider.html5Mode(true);
            }).config(config)
        .run(run);
		
		app.directive('datetimepicker', function(){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            //binding timepicker from here would be ensure that
            //element would be available only here.
            element.datetimepicker(); //your code would be here.
        }
    }
});

app.filter("commaBreak", 

    function () {

        return function ( value ) {

            if( value == null || !value.length ) return;

            return value.split(',');

		}
        });
		
	app.factory("editservice", ['$http', function($http) {
  		 var editservice = {};		
		   editservice.getCustomer = getCustomer;
		   editservice.getEmp = getEmp;
		   //editservice.getProjectdet = getProjectdet;
		   editservice.getProjectpay = getProjectpay;
		   editservice.getShotdet = getShotdet;
		   editservice.getShotdetshotstatusid = getShotdetshotstatusid;
		   editservice.assignstatus = assignstatus;
		 
		   
		  function getEmp(empid) 
		  	{			
			 return $http.get("services/empdetails?empid="+empid);
			}
	 	 function getCustomer(userid) {			
			 return $http.get("services/userdetails?userid="+userid);
   			 }
		 function getProjectdet(userid) {			
			 return $http.get("services/projdetails?userid="+userid);
   			 }
	     function getProjectpay(entityid,dueid) {			
			 return $http.get("services/paymentdetails?entityid="+entityid+'&dueid='+dueid);
   			 }
		 function getShotdet(userid,version) {
			 return $http.get("services/shotdetails?userid="+userid+'&version='+version);
   			 }
			  function getShotdetshotstatusid(shotallocid,version) {			
			 return $http.get("services/shotdetailsshotstatusid?shotallocid="+shotallocid+'&version='+version);
   			 }
			  function assignstatus() {			
			 return $http.get("services/assignstatus");
   			 }		 
		
	 return editservice;
}]);

    config.$inject = ['$routeProvider', '$locationProvider'];
    function config($routeProvider, $locationProvider) {
        $routeProvider
            .when('/users', {            	 
                controller: 'UsersController',
                templateUrl: 'views/UsersView.html',
                controllerAs: 'vm'
            })
             .when('/purchase',
			{
				controller: 'PurchaseController',
                templateUrl: 'views/PendingPurchaseOrder.html',
                controllerAs: 'vm'
			})
            .when('/raised_order',
			{
				controller: 'PurchaseController',
                templateUrl: 'views/RaisedPurchaseOrder.html',
                controllerAs: 'vm'
			})
			.when('/pending_order',
			{
				controller: 'PurchaseController',
                templateUrl: 'views/PendingPurchaseOrder.html',
                controllerAs: 'vm'
			})
			.when('/po_details/:po_id',
			{
				controller: 'PurchaseController',
                templateUrl: 'views/RaisedPurchaseOrder.html',
                controllerAs: 'vm'
			})
			.when('/invoice_details/:invoice_id',
			{
				controller: 'InvoiceController',
                templateUrl: 'views/RaisedInvoice.html',
                controllerAs: 'vm'
			})
			
			.when('/invoice',
			{
				controller: 'InvoiceController',
                templateUrl: 'views/PendingInvoice.html',
                controllerAs: 'vm'
			})
			.when('/raised_invoice',
			{
				controller: 'InvoiceController',
                templateUrl: 'views/RaisedInvoice.html',
                controllerAs: 'vm'
			})
			.when('/pending_invoice',
			{
				controller: 'InvoiceController',
                templateUrl: 'views/PendingInvoice.html',
                controllerAs: 'vm'
			})
			.when('/edit_user', {
                controller: 'EditUserController',
                templateUrl: 'views/edit_user.view.html',
                controllerAs: 'vm' ,
				resolve: {
            customer: function(editservice, $route){
            var customerID = $route.current.params.user_id;            
			return 1;
		 			 }
				}
            }).when('/edit_user/:user_id', {
                controller: 'EditUserController',
                templateUrl: 'views/edit_user.view.html',
                controllerAs: 'vm',
                resolve: {
                    customer: function(editservice, $route){
                    var customerID = $route.current.params.user_id;                    
        			return 1;
        		 			 }
				}
            })
			.when('/view_user/:user_id', {
                controller: 'ViewUserController',
                templateUrl: 'views/view_user.html',
                controllerAs: 'vm' ,
				resolve: {
            customer: function(editservice, $route){
            var customerID = $route.current.params.user_id;           
			return 1;
		 			 }
				}
            })
			.when('/login', {
                controller: 'LoginController',
                templateUrl: 'views/login.view.html',				
                controllerAs: 'vm'
            })
            .when('/header', {
                controller: 'HeaderController',
                templateUrl: 'views/login.view.html',				
                controllerAs: 'vm'
            })
			.when('/sidebar', {
                controller: 'SidebarController',
                templateUrl: 'views/sidebar.php',				
                controllerAs: 'vm'
            })
			.when('/forgotpassword', {
                controller: 'ForgotpasswordController',
                templateUrl: 'views/forgotpassword.view.html',
                controllerAs: 'vm'
            })
			.when('/dashboard/1',
			{
				 controller: 'DashboardController',
                templateUrl: 'views/dashboard.view.html',
                controllerAs: 'vm'
			})
            .when('/register', {
                controller: 'RegisterController',
                templateUrl: 'views/register.view.html',
                controllerAs: 'vm'
            })
			 .when('/artist', {				
                controller: 'ArtistController',
                templateUrl: 'views/artist.view.html',
                controllerAs: 'vm'
            }) 
            .when('/edit_artist', {
                controller: 'EditArtistController',
                templateUrl: 'views/edit_artist.view.html',
                controllerAs: 'vm' ,
				resolve: {
            artist: function(editservice, $route){
            var artistID = $route.current.params.artist_id;
			return 1;
		 			 }
				}
            }).when('/edit_artist/:artist_id', {
                controller: 'EditArtistController',
                templateUrl: 'views/edit_artist.view.html',
                controllerAs: 'vm',
				resolve: {
					artist: function(editservice, $route){
			            var artistID = $route.current.params.artist_id;
						return 1;
		 			 }
				}
            })			   
			.when('/freelancers/:freelancers_id', {
                controller: 'FreelancersController',
                templateUrl: 'views/freelancers.view.html',
                controllerAs: 'vm'
            })
			.when('/edit_freelancers/:freelancers_id', {
                controller: 'EditFreelancersController',
                templateUrl: 'views/edit_freelancers.view.html',
                controllerAs: 'vm' ,
				resolve: {
            freelancers: function(editservice, $route){
            var freelancersID = $route.current.params.freelancers_id;
			return editservice.getEmp(freelancersID);
		 			 }
				}
            })
			.when('/view_freelancers/:freelancers_id', {
                controller: 'ViewFreelancersController',
                templateUrl: 'views/view_freelancers.html',
                controllerAs: 'vm' ,
				resolve: {
             freelancers: function(editservice, $route){
            var freelancersID = $route.current.params.freelancers_id;
			return editservice.getEmp(freelancersID);
		 			 }
				}
            }).when('/vendor', {
                controller: 'VendorController',
                templateUrl: 'views/vendor.view.html',
                controllerAs: 'vm'
            })
			.when('/edit_vendor', {
                controller: 'EditVendorController',
                templateUrl: 'views/edit_vendor.view.html',
                controllerAs: 'vm' ,
				resolve: {
            vendor: function(editservice, $route){
            var vendorID = $route.current.params.vendor_id;
			return 1;
		 			 }
				}
            })
            .when('/edit_vendorDepartDetails', {
                controller: 'EditVendorController',
                templateUrl: 'views/edit_vendor_department.view.html',
                controllerAs: 'vm' ,
				resolve: {
            vendor: function(editservice, $route){
            var vendorID = $route.current.params.vendor_id;
			return 1;
		 			 }
				}
            }).when('/edit_vendorDepartDetails/:vendor_id', {
                controller: 'EditVendorController',
                templateUrl: 'views/edit_vendor_department.view.html',
                controllerAs: 'vm' ,
				resolve: {
            vendor: function(editservice, $route){
            var vendorID = $route.current.params.vendor_id;
			return 1;
		 			 }
				}
            })
			.when('/edit_vendor/:vendor_id', {
                controller: 'EditVendorController',
                templateUrl: 'views/edit_vendor.view.html',
                controllerAs: 'vm' ,
				resolve: {
            vendor: function(editservice, $route){
            var vendorID = $route.current.params.vendor_id;
			return editservice.getEmp(vendorID);
		 			 }
				}
            })
			.when('/view_vendor/:vendor_id', {
                controller: 'ViewVendorController',
                templateUrl: 'views/view_vendor.html',
                controllerAs: 'vm' ,
				resolve: {
            vendor: function(editservice, $route){
            var vendorID = $route.current.params.vendor_id;
			return editservice.getEmp(vendorID);
		 			 }
				}
            })
			.when('/client', {
                controller: 'ClientController',
                templateUrl: 'views/client.view.html',
                controllerAs: 'vm'
            })
			.when('/edit_client', {
                controller: 'EditClientController',
                templateUrl: 'views/edit_client.view.html',
                controllerAs: 'vm',
				resolve: {
            client: function(editservice, $route){
            var clientID = $route.current.params.client_id;
			return editservice.getEmp(clientID);
		 			 }
				}
            }).when('/edit_client/:client_id', {
                controller: 'EditClientController',
                templateUrl: 'views/edit_client.view.html',
                controllerAs: 'vm',
				resolve: {
            client: function(editservice, $route){
            var clientID = $route.current.params.client_id;
			return editservice.getEmp(clientID);
		 			 }
				}
            })
			.when('/view_client/:client_id', {
                controller: 'ViewClientController',
                templateUrl: 'views/view_client.html',
                controllerAs: 'vm' ,
				resolve: {
            client: function(editservice, $route){
            var clientID = $route.current.params.client_id;
			return editservice.getEmp(clientID);
		 			 }
				}
            })
			.when('/view_payment/:client_id', {
                controller: 'ViewPaymentController',
                templateUrl: 'views/view_payment.html',
                controllerAs: 'vm' ,
            })
			.when('/projectdetails/:projectdet_id', {
                controller: 'ProjectdetailsController',
                templateUrl: 'views/projectdetails.view.html',
                controllerAs: 'vm'
            }).when('/projectdetails', {
                controller: 'ProjectdetailsController',
                templateUrl: 'views/projectdetails.view.html',
                controllerAs: 'vm'
            })
            .when('/edit_projectdet/', {
                controller: 'EditProjectdetailsController',
                templateUrl: 'views/edit_projectdetails.view.html',
                controllerAs: 'vm'
            })
			.when('/edit_projectdet/:projectdet_id', {
                controller: 'EditProjectdetailsController',
                templateUrl: 'views/edit_projectdetails.view.html',
                controllerAs: 'vm' 
            })
			.when('/edit_projectdetails/:entityid/dueid/:dueid', {
                controller: 'EditProjectpaymentController',
                templateUrl: 'views/edit_projectpayment.view.html',
                controllerAs: 'vm'
            })
			.when('/view_projectdet/:projectdet_id', {
                controller: 'ViewProjectdetController',
                templateUrl: 'views/view_projectdet.html',
                controllerAs: 'vm'
            })
            .when('/shotdetails', {
                controller: 'ShotallocController',
                templateUrl: 'views/shotalloc.view.html',
                controllerAs: 'vm'
            })
			.when('/shotdetails/:shotdet_id', {
                controller: 'ShotdetailsController',
                templateUrl: 'views/shotdetails.view.html',
                controllerAs: 'vm'
            })
            
			.when('/edit_shotdet/:shotdet_id', {
                controller: 'EditShotdetailsController',
                templateUrl: 'views/edit_shotdetails.view.html',
                controllerAs: 'vm' ,
				resolve: {
            shotdet: function(editservice, $route){
            var shotdetID = $route.current.params.shotdet_id;
			return editservice.getEmp(shotdetID);
		 			 }
				}
            })
			.when('/projectallocation/:projalloc_id', {
                controller: 'ProjallocController',
                templateUrl: 'views/projalloc.view.html',
                controllerAs: 'vm'
            })
			.when('/edit_projectallocation/:projalloc_id', {
                controller: 'EditProjallocController',
                templateUrl: 'views/edit_projalloc.view.html',
                controllerAs: 'vm' ,
				resolve: {
            projallocdet: function(editservice, $route){
            var projallocdetID = $route.current.params.projalloc_id;
			return editservice.getEmp(projallocdetID);
		 			 }
				}
            })
			.when('/projectstatus/:projstatus_id', {
                controller: 'ProjectstatusController',
                templateUrl: 'views/projstatus.view.html',
                controllerAs: 'vm'
            })
			.when('/resourceavailability/:resourceavailability_id', {
                controller: 'ResourceavailabilityyController',
                templateUrl: 'views/resourceavailability.view.html',
                controllerAs: 'vm'
            })
			.when('/resourceperformance/:resourceperformance_id', {
                controller: 'ResourceperformanceController',
                templateUrl: 'views/Resourceperformance.view.html',
                controllerAs: 'vm'
            })
          
			.when('/shotallocation/:shotalloc_id', {
                controller: 'ShotallocController',
                templateUrl: 'views/shotalloc.view.html',
                controllerAs: 'vm',
				resolve: {
           assignstatus: function(editservice, $route){            
			return editservice.assignstatus();			
		 			 }
				}
            })
			.when('/edit_shotallocation/:shotalloc_id', {
                controller: 'EditShotallocController',
                templateUrl: 'views/edit_shotalloc.view.html',
                controllerAs: 'vm' ,
				resolve: {
            shotallocdet: function(editservice, $route){            
            var shotallocID = $route.current.params.shotalloc_id;
			var version='1';
			return editservice.getShotdet(shotallocID,version);			
		 			 },
			shotallocdetshotstatusid: function(editservice, $route){
            var shotallocID = $route.current.params.shotalloc_id;
			var version='1';
			return editservice.getShotdetshotstatusid(shotallocID,version);			
		 			 },
			assignstatus: function(editservice, $route){            
			return editservice.assignstatus();			
		 			 }								
				}
				
            })
			.when('/edit_shotallocation/:shotalloc_id/version/:version', {
                controller: 'EditShotallocController',
                templateUrl: 'views/edit_shotalloc.view.html',
                controllerAs: 'vm' ,
				reload:true,				
				resolve: {
            shotallocdet: function(editservice, $route){
            var shotallocID = $route.current.params.shotalloc_id;
			 var version = $route.current.params.version;
			return editservice.getShotdet(shotallocID,version);			
		 			 },
			shotallocdetshotstatusid: function(editservice, $route){
            var shotallocID = $route.current.params.shotalloc_id;		
			 var version = $route.current.params.version;
			return editservice.getShotdetshotstatusid(shotallocID,version);			
		 			},
			assignstatus: function(editservice, $route){            
			return editservice.assignstatus();			
		 			 }				
				}
				
            })
			.when('/view_shotallocation/:shotalloc_id/version/:version', {
                controller: 'ViewShotallocationController',
                templateUrl: 'views/view_shotalloc.html',
                controllerAs: 'vm' ,
				resolve: {
            shotallocdet: function(editservice, $route){
            var shotallocID = $route.current.params.shotalloc_id;
			 var version = $route.current.params.version;
			return editservice.getShotdet(shotallocID,version);			
		 			 },
			shotallocdetshotstatusid: function(editservice, $route){
            var shotallocID = $route.current.params.shotalloc_id;			
			 var version = $route.current.params.version;
			return editservice.getShotdetshotstatusid(shotallocID,version);			
		 			},
			assignstatus: function(editservice, $route){            
			return editservice.assignstatus();			
		 			 }				
				}
            })
            .otherwise({ redirectTo: '/login' });
			
			$locationProvider.html5Mode(false);
    }
		
    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http'];
    function run($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray($location.path(), ['/login', '/register']) === -1;
			var forgotpasswordpage = $.inArray($location.path(),['/forgotpassword']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn && forgotpasswordpage) {
                $location.path('/login');
            }
        });
    }

})();