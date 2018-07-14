(function () {
    'use strict';
    angular
           .module('app')
           .controller('EditFreelancersController', EditFreelancersController);
		    EditFreelancersController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','$location','freelancers'];
		    function EditFreelancersController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,$location,freelancers) {
				
			var freelancersID = ($routeParams.freelancers_id) ? parseInt($routeParams.freelancers_id) : 0;//edit userid
		
    	    $rootScope.title = (freelancersID > 0) ? 'Edit Freelancers' : 'Add Freelancers';
    	    $scope.buttonText = (freelancersID > 0) ? 'Update Freelancers' : 'Add New Freelancers';
				
		
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;			
			$scope.activeMenu = "freelancers";
			var original = freelancers.data;
			if(original)
			{			
			$scope.entity= angular.copy(original);
			}
			else
			{
				$scope.entity= {};
			}
			
		    $scope.editfreelancersid = freelancersID;	
			$scope.freelanceraccessid = [{freelanceraccessid:'1',name: 'ftp'}, {freelanceraccessid: '2',name: 'aspera'}]	
			$scope.freelancerstatusid = [{freelancerstatusid:'1',name: 'active'}, {freelancerstatusid:'2',name: 'inactive'}]	
			$scope.freelancergenderid = [{freelancergenderid:'1',name: 'male'}, {freelancergenderid:'2',name: 'female'}]	
			$scope.freelancerlevelid =  [{freelancerlevelid:'1',name: 'high'}, {freelancerlevelid:'2',name: 'middle'},{freelancerslevelid:'3',name:'low'}]
			$scope.freelancerdepartmentid=[{freelancerdepartmentid:'1',name:'Roto'},{freelancerdepartmentid:'2',name:'Paint'},{freelancerdepartmentid:'3',name:'Match move'},{freelancerdepartmentid:'4',name:'Compositing'},{freelancerdepartmentid:'5',name:'3D'}]
			
			
			 $scope.selectaccess = function () {
			
                   // call jQuery functiona using angular.element
              var b= angular.element(document.getElementById("accessid")).val();
			 
					if(b == 2)
					{
						 var b= angular.element(document.getElementById("url"));
						 b.removeAttr('disabled');
						 
						 var e= angular.element(document.getElementById("hostname"))
						e.attr('disabled','disabled');
					}
					else if(b == 1)
					{
						var d= angular.element(document.getElementById("url"))
						d.attr('disabled','disabled');	
						
					 	var c= angular.element(document.getElementById("hostname"))
						c.removeAttr('disabled');
						
					}
					
			 }
			 
			 
			 
			 $scope.addChoice = function () 
			 {
				$("#email1").removeAttr('style');
			 }
			  $scope.addChoice1 = function () 
			 {
				$("#email2").removeAttr('style');
			 }
			  $scope.addChoice2 = function () 
			 {
				$("#email3").removeAttr('style');
			 }
			  $scope.addChoice3 = function () 
			 {
				$("#email4").removeAttr('style');
			 }
			  $scope.addmobile = function () 
			 {
				$("#mobile1").removeAttr('style');
			 }
			  $scope.addmobile1 = function () 
			 {
				$("#mobile2").removeAttr('style');
			 }
			  $scope.addmobile2 = function () 
			 {
				$("#mobile3").removeAttr('style');
			 }
			  $scope.addmobile3= function () 
			 {
				$("#mobile4").removeAttr('style');
			 }
			
			 $scope.selectAction = function () 
			 {
				 var b= angular.element(document.getElementById("freelancerdepartmentid")).val();
				
				 
				  var one = b.indexOf('1');	
				  var two = b.indexOf('2');	
				  var three = b.indexOf('3');	
				  var four = b.indexOf('4');	
				  var five = b.indexOf('5');	
						 
				 if(one > -1)
				 {
				 	$("#priceroto").removeAttr('style');
					$("#priorityroto").removeAttr('style');
				 }
				 else 
				 {
					 $("#priceroto").attr('style','display:none');
					$("#priorityroto").attr('style','display:none');
					
					
					 }
				if(two > -1)
				 {
					 $("#pricepaint").removeAttr('style');
					 $("#prioritypaint").removeAttr('style');
				 }
				  else 
				 {
					 $("#pricepaint").attr('style','display:none');
					$("#prioritypaint").attr('style','display:none');
					 }
				 if(three > -1)
				 {
					 $("#pricematch").removeAttr('style');
					 $("#prioritymatch").removeAttr('style');
				 }
				  else 
				 {
					 $("#pricematch").attr('style','display:none');
					$("#prioritymatch").attr('style','display:none');
					 }
				 if(four > -1)
				 {
					 $("#pricecomp").removeAttr('style');
					 $("#prioritycomp").removeAttr('style');
				 } 
				  else 
				 {
					 $("#pricecomp").attr('style','display:none');
					$("#prioritycomp").attr('style','display:none');
					 }
				 if(five > -1)
				 {
					 $("#price3d").removeAttr('style');
					 $("#priority3d").removeAttr('style');
				 }
				  else 
				 {
					 $("#price3d").attr('style','display:none');
					$("#priority3d").attr('style','display:none');
					 }
				 
             }
			initController();
				   
            function initController() {
						
			getfields();
			getfieldsbank();
			getfieldsftp();
			getAccessDetails(); 
			getentityid(); 
			getuserrole();     							
			}	
			
			function getfields()
		    {
		    var freelancers="freelancers";
            UserService.getFields(freelancers).then(function (data) {				
		    $scope.fields = data.data;			
            });
         	} 
			function getfieldsbank()
		    {
		    var bankdetails="bankdetails";
            UserService.getFields(bankdetails).then(function (data) {				
		    $scope.fieldsbank = data.data;			
            });
         	} 
			function getfieldsftp()
		    {
		    var ftp="ftp";
            UserService.getFields(ftp).then(function (data) {				
		    $scope.fieldsftp = data.data;			
            });
         	} 	
		   function getAccessDetails()
			{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
			} 
			function getuserrole()
		{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
		}
			function getentityid()
		{
			var name="freelancers";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 		 	
		}; 
})();