

(function () {
    'use strict';

   var empapp= angular
        .module('app')
        .controller('ArtistController', ArtistController);
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
    
    ArtistController.$inject = ['$cookieStore','$routeParams','$rootScope','$scope','UserService','FlashService','Excel','$timeout','$http'];
    function ArtistController($cookieStore,$routeParams,$rootScope,$scope,UserService,FlashService,Excel,$timeout,$http) {
		
		var artistID = ($routeParams.artist_id) ? parseInt($routeParams.artist_id) : 0;
		var vm = this;
		vm.user = null;
		var userid = $cookieStore.get('userid');
		var username = $cookieStore.get('username');
		$scope.username = username;		
		vm.deleteEmp= deleteEmp;
			$scope.activeMenu = "artist";
		 	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
            var exportHref=Excel.tableToExcel(tableId,'WireWorkbenchDataExport');
            $timeout(function(){location.href=exportHref;},100); // trigger download
        }
		$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
		}
		$scope.artistid=artistID;
        initController();
        function initController() {
			getUserDetails();          
			retrieveArtist();
			//getuserrole();
			//getAccessDetails();
			//getLeads();
			//getSuperVisors();
        }
        
        function retrieveArtist() {
        	
			 $.ajax({
				    type        : 'POST',
				    url         : 'services/artist.php?function=retrieveArtist',
				    data        : $(this).serialize(),
					dataType    : 'json',  
				    success     : function(data) {		
				    	$scope.artists = data;		
				    	//alert($scope.artists);
				      }
				  });			
		 }

        
        function getLeads()
		{
			 UserService.getLeads().then(function (data) {				
				 $scope.leads = data.data;
			 });
		}
        

        function getSuperVisors()
		{
			 UserService.getSuperVisors().then(function (data) {				
				 $scope.supervisor = data.data;
			 });
		}
		function getUserDetails()
		{	
			 	 UserService.getSupervisors().then(function (data) {				
				 $scope.supervisor = data.data;
			 });
		}
		/* function loadAllEmp() {
			 UserService.GetAllemp(artistID).then(function (data) {
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
		}*/ 
		  
		  
		  function deleteEmp() {			
			 	var checkedValue = ''; 
			 	var inputElements = document.getElementsByClassName('checkbox');
			 	for(var i=0; inputElements[i]; ++i){
			 	      if(inputElements[i].checked){
			 	           checkedValue += inputElements[i].value + ",";
			 	      }
			 	}
			 	
			 	checkedValue = checkedValue.substr(0, checkedValue.length-1);			 	
			 	if(confirm("Are you sure to delete Artist")==true)
			 		$.ajax({
					    type        : 'POST',
					    url         : 'services/artist.php?function=removeArtist&artistId='+checkedValue,
					    data        : $(this).serialize(),
						dataType    : 'json',  
					    success     : function(data) {		
					    	if(data == 'success'){
						    	alert("Artist Id " + checkedValue + " Deleted Successfully");
						    	location.reload(true);
					    	}else {
					    		alert("Artist Id " + checkedValue + " was not deleted, due to technical issue");
					    	}			    	
					      }
					  });
      }

 
	<!-- this part is for multiple checkbox -->
		 
 
/*	$(document).ready(function(){
    $('#select_all').on('click',function(){
    	console.log("select all");
    	alert("checked");
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
 */
	$("#artistform").submit(function (e) {
		alert(1234);
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
 	});
 }

})();