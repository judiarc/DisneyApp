

(function () {
    'use strict';

   var empapp= angular
        .module('app')
        .controller('FreelancersController', FreelancersController);
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
    
    FreelancersController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http'];
    function FreelancersController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http) {
		
		var freelancersID = ($routeParams.freelancers_id) ? parseInt($routeParams.freelancers_id) : 0;
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');
		 var username = $cookieStore.get('username');
		     $scope.username = username;		
		vm.deleteEmp = deleteEmp;
		 $scope.activeMenu = "freelancers";
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		
		$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
	}
		$scope.freelancersid=freelancersID;
       initController();
	   
        function initController() {
			getUserDetails();          
			loadAllEmp();
			getuserrole();
			getAccessDetails();
        }
		function getUserDetails()
		{
			 UserService.getUserDetails(userid).then(function (user) {				
					vm.user = user;
			 });
		}
		 function loadAllEmp() {
			 UserService.GetAllemp(freelancersID).then(function (data) {
					$scope.users = data.data;
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
		 function deleteEmp(id) {
			    if(confirm("Are you sure to delete user")==true)
           		 UserService.Deleteemp(id).success(function (response) {
             			if (response.success == 'true') {
				 FlashService.Success(response.message);
                 setTimeout("location.reload(true);",1000);
                } else {
                    FlashService.Error(response.message);
                    vm.dataLoading = false;
                }
			 });
        }

 
	<!-- this part is for multiple checkbox -->
		 
 
	$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        	}
    	});
	});
 
 
 
 
	 $("#empform").submit(function (e) {
    e.preventDefault();
    var formId = "submit";
	 $(formId)
 	{
	   
	 var ids = [];
     $(".checkbox").each(function () {
        if ($(this).is(":checked")) {
            ids.push($(this).val());
        }
    });
    if (ids.length) {
		 if(confirm("Are you sure to delete user")==true)
        $.ajax({
            type: 'POST',
            url: "includes/deleteemp.php",
            data: {
                id: ids
            },
            success: function (data) {
                alert(data);
				 setTimeout("location.reload(true);",1000);
                           }
        });
    } else {
        alert("Please select items.");
    		}
 		}
 	})
 }

})();