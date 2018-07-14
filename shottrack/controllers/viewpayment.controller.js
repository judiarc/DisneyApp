

(function () {
    'use strict';

   var empapp= angular
        .module('app')
        .controller('ViewPaymentController', ViewPaymentController);
	<!-- creating own service to export as excel -->	
		empapp.factory('Excel',function($window){
        	var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>			<x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        	return {
            tableToExcel:function(tableId,worksheetName){
                	var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
            }
        };
    })
    
    ViewPaymentController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http','DTOptionsBuilder'];
    function ViewPaymentController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http,DTOptionsBuilder) {
		
		var clientID = ($routeParams.client_id) ? parseInt($routeParams.client_id) : 0;
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');	
		 var username = $cookieStore.get('username');
		     $scope.username = username;		
		
		$scope.activeMenu = "client";
	
		$scope.clientid=clientID;
		// DataTables configurable options
    	$scope.dtOptions = DTOptionsBuilder.newOptions()       
        .withOption('scrollX', true);
		
       initController();
	   
        function initController() {
			getClientpaymentdetails();
			getAccessDetails(); 
			getuserrole();         
			
        }
		function getClientpaymentdetails()
		{
			 UserService.getClientpaymentdetails(clientID).then(function (data) {				
					$scope.clientpayments = data.data;
			 });
		}
		function getuserrole()
		{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
		}
		 function getAccessDetails()
		{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		} 
 
 }

})();
