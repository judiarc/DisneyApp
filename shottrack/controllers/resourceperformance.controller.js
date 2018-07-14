

(function () {
    'use strict';

   var empapp= angular
        .module('app')
        .controller('ResourceperformanceController', ResourceperformanceController);
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
    
    ResourceperformanceController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http','DTOptionsBuilder', 'DTColumnBuilder'];
    function ResourceperformanceController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http,DTOptionsBuilder, DTColumnBuilder) {
		
		
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		var userid = $cookieStore.get('userid');
		 var username = $cookieStore.get('username');
		     $scope.username = username;	
		$scope.activeMenu = "resourceperformance";
		$scope.vm = {};

    $scope.vm.dtOptions = DTOptionsBuilder.newOptions()
      .withOption('order', [0, 'asc']);
	  
       initController();
	   
        function initController() {
			getAllProjectDetails(); 
			getAccessDetails();         
			
        }
		function getAllProjectDetails()
		{
			var artistid='3';
			 UserService.GetAllemp(artistid).then(function (data) {				
					$scope.projects = data.data;
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