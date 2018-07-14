(function () {
    'use strict';
    angular
           .module('app')
           .controller('ViewShotallocationController', ViewShotallocationController);
		    ViewShotallocationController.$inject = ['$cookieStore','$routeParams','$route','$rootScope','$scope','UserService','FlashService','$location','shotallocdet','shotallocdetshotstatusid'];
		    function ViewShotallocationController($cookieStore,$routeParams,$route,$rootScope,$scope,UserService,FlashService,$location,shotallocdet,shotallocdetshotstatusid) {
		
			
			var shotallocID = ($routeParams.shotalloc_id) ? parseInt($routeParams.shotalloc_id) : 0;//edit userid
			var shotstatusid = ($routeParams.shotstatusid) ? parseInt($routeParams.shotstatusid) : 0;//shotstatusid
			var version = ($routeParams.version) ? parseInt($routeParams.version) : 0;//shotstatusid
		
    	    $rootScope.title = (shotallocID > 0) ? 'Edit ShotAllocation' : 'Add ShotAllocation';
    	    $scope.buttonText = (shotallocID > 0) ? 'Update ShotAllocation' : 'Add New ShotAllocation';
		
			
			var userid = $cookieStore.get('userid');
			 var username = $cookieStore.get('username');
		     $scope.username = username;		
			$scope.activeMenu = "shotallocation";
			$scope.shotstatusidd=shotstatusid;
			
			var original = shotallocdet.data;
			var shotstatusoriginal = shotallocdetshotstatusid.data;
			var rotoartist=getartistroto.data;
			
			if(original || shotstatusoriginal )
			{			
			$scope.entitys= angular.copy(original);
			$scope.entityshotstatus= angular.copy(shotstatusoriginal);			
			$scope.entity = (shotstatusid == '2')? $scope.entityshotstatus : $scope.entitys;			 
			}
			else
			{
				$scope.entity= {};
			}			
		$scope.editshotallocid = shotallocID;		
	
		$scope.shotallocationstatusid = [{shotallocationstatusid:'1',shotallocationstatusname: 'completed'}, {shotallocationstatusid:'2',shotallocationstatusname: 'Redo'}]
		
		$scope.shotallocationresourcestatusid=[{shotallocationresourcestatusid:'1',name:'assigned'},{shotallocationresourcestatusid:'2',name:'free'}]	
		
		$scope.rototeamtypeid=[{rototeamtypeid:'1',name:'In House'},{rototeamtypeid:'2',name:'Vendor'},{rototeamtypeid:'3',name:'Freelancers'}]	
		$scope.paintteamtypeid=[{paintteamtypeid:'1',name:'In House'},{paintteamtypeid:'2',name:'Vendor'},{paintteamtypeid:'3',name:'Freelancers'}]	
		$scope.matchmoveteamtypeid=[{matchmoveteamtypeid:'1',name:'In House'},{matchmoveteamtypeid:'2',name:'Vendor'},{matchmoveteamtypeid:'3',name:'Freelancers'}]	
		$scope.compteamtypeid=[{compteamtypeid:'1',name:'In House'},{compteamtypeid:'2',name:'Vendor'},{compteamtypeid:'3',name:'Freelancers'}]	
		$scope.dteamtypeid=[{dteamtypeid:'1',name:'In House'},{dteamtypeid:'2',name:'Vendor'},{dteamtypeid:'3',name:'Freelancers'}]	
	
		$scope.shotallocationdepartmentid=[{shotallocationdepartmentid:'1',name :'Roto'},{shotallocationdepartmentid:'2',name :'Paint'},{shotallocationdepartmentid:'3',name :'Match move'},{shotallocationdepartmentid:'4',name :'Compositing'},{shotallocationdepartmentid:'5',name :'3D'}]	
		
		$scope.complexityid =[{complexityid :'1',name :'easy'},{complexityid :'2',name :'average'},{complexityid :'3',name :'complex'}]
		
		$scope.shotallocationshotstatusid=[{shotallocationshotstatusid:'1',name:'assigned'},{shotallocationshotstatusid:'2',name:'inprogress'},{shotallocationshotstatusid:'3',name:'completed'},{shotallocationshotstatusid:'4',name:'Redo'},{shotallocationshotstatusid:'5',name:'needs fixing(or)feedback received'},{shotallocationshotstatusid:'6',name:'feedback fix & uploaded'},{shotallocationshotstatusid:'7',name:'on hold(Post delivery)'},{shotallocationshotstatusid:'8',name:'on hold(Pre delivery)'},{shotallocationshotstatusid:'9',name:'omitted'},{shotallocationshotstatusid:'10',name:'awaiting updated input'},{shotallocationshotstatusid:'11',name:'allocated and yet to start'},{shotallocationshotstatusid:'12',name:'Allocated and work in progress'},{shotallocationshotstatusid:'13',name:'uploaded and waiting for feedback'},{shotallocationshotstatusid:'14',name:'approved'}]			
			
			
				 $scope.selectAction = function () {
					 
					 
					 var selectedoption= $("#projectdetailsid option:selected").text();
				 $('#projectname').val(selectedoption);
			  
						
					var c= angular.element(document.getElementById("projectdetailsid")).val();
		
					 if(c !='')
					 {						
						 	UserService.getProjectShotid(c).then(function (data) {				
							$scope.shotallocationid = data.data;
						
			 			});
					 }
					 var d= angular.element(document.getElementById("shotallocationdepartmentid")).val();
					
					 if(d.indexOf('1') > -1)
					 {						
						 $("#roto").show();
						  $("#rotodept").show();
						    $("#interimrotoo").show();
							  $("#finalroto").show();
						 
					 }
					 if(d.indexOf('2') > -1)
					 {
						  $("#paint").show();
						   $("#paintdept").show();
						      $("#interimpaintt").show();
							   $("#finalpaint").show();
					 }
					  if(d.indexOf('3') > -1)
					 {
						  $("#matchmove").show();
						   $("#matchmovedept").show();
						        $("#interimatchmovee").show();
							   $("#finalmatchmove").show();
					 }
					  if(d.indexOf('4') > -1)
					 {
						  $("#comp").show();
						   $("#compdept").show();
						        $("#interimcompp").show();
							   $("#finalcomp").show();
					 }
					 if(d.indexOf('5') > -1)
					 {
						  $("#3d").show();
						   $("#3ddept").show();
						      $("#interim3dd").show();
							   $("#final3d").show();
					 }
				
					//  var e= angular.element(document.getElementById("rototeamtypeid")).val();
//					
//						if(e == 1)
//						{
//						 var f= angular.element(document.getElementById("shotallocationrotoartistid"))
//						 f.removeAttr('disabled');
//						 var g= angular.element(document.getElementById("vendorrotodepartmentid"))
//						 g.attr('disabled','disabled');	
//						 var h= angular.element(document.getElementById("freelancerrotodepartmentid"))
//						 h.attr('disabled','disabled');	
//						  var stdate= angular.element(document.getElementById("workstartdateroto"))
//						 stdate.attr('disabled','disabled');
//						  var stdate2= angular.element(document.getElementById("targettimeroto"))
//						 stdate2.attr('disabled','disabled');
//						 var stdate3= angular.element(document.getElementById("completedtimeroto"))
//						 stdate3.attr('disabled','disabled');		
//						}
//						else if(e == 2)
//						{
//						var i= angular.element(document.getElementById("vendorrotodepartmentid"))
//						i.removeAttr('disabled');
//						var j= angular.element(document.getElementById("shotallocationrotoartistid"))
//						j.attr('disabled','disabled');	
//						var k= angular.element(document.getElementById("freelancerrotodepartmentid"))
//						 k.attr('disabled','disabled');	
//						 var stdate= angular.element(document.getElementById("workstartdateroto"))
//						 stdate.removeAttr('disabled');
//						  var stdate2= angular.element(document.getElementById("targettimeroto"))
//						 stdate2.removeAttr('disabled');
//						 var stdate3= angular.element(document.getElementById("completedtimeroto"))
//						 stdate3.removeAttr('disabled');	
//					
//					 }
//					 else if(e == 3)
//						{
//							
//						var l= angular.element(document.getElementById("freelancerrotodepartmentid"))
//						l.removeAttr('disabled');
//						var m= angular.element(document.getElementById("shotallocationrotoartistid"))
//						m.attr('disabled','disabled');	
//						var n= angular.element(document.getElementById("vendorrotodepartmentid"))
//						 n.attr('disabled','disabled');	
//						 var stdate= angular.element(document.getElementById("workstartdateroto"))
//						 stdate.removeAttr('disabled');
//						  var stdate2= angular.element(document.getElementById("targettimeroto"))
//						 stdate2.removeAttr('disabled');
//						 var stdate3= angular.element(document.getElementById("completedtimeroto"))
//						 stdate3.removeAttr('disabled');	
//					
//					 }
//					  var ep= angular.element(document.getElementById("paintteamtypeid")).val();
//					
//						if(ep == 1)
//						{
//						 var fp= angular.element(document.getElementById("shotallocationpaintartistid"))
//						 fp.removeAttr('disabled');
//						 var gp= angular.element(document.getElementById("shotallocationpaintvendorid"))
//						 gp.attr('disabled','disabled');	
//						 var hp= angular.element(document.getElementById("freelancerpaintdepartmentid"))
//						 hp.attr('disabled','disabled');	
//						 var pstdate= angular.element(document.getElementById("workstartdatepaint"))
//						 pstdate.attr('disabled','disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimepaint"))
//						 pstdate2.attr('disabled','disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimepaint"))
//						 pstdate3.attr('disabled','disabled');	
//						}
//						else if(ep == 2)
//						{
//						var ip= angular.element(document.getElementById("shotallocationpaintvendorid"))
//						ip.removeAttr('disabled');
//						var jp= angular.element(document.getElementById("shotallocationpaintartistid"))
//						jp.attr('disabled','disabled');	
//						var kp= angular.element(document.getElementById("freelancerpaintdepartmentid"))
//						 kp.attr('disabled','disabled');	
//						  var pstdate1= angular.element(document.getElementById("workstartdatepaint"))
//						 pstdate2.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimepaint"))
//						 pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimepaint"))
//						pstdate3.removeAttr('disabled');		
//					
//					 }
//					 else if(ep == 3)
//						{
//							
//						var lp= angular.element(document.getElementById("freelancerpaintdepartmentid"))
//						lp.removeAttr('disabled');
//						var mp= angular.element(document.getElementById("shotallocationpaintartistid"))
//						mp.attr('disabled','disabled');	
//						var np= angular.element(document.getElementById("shotallocationpaintvendorid"))
//						 np.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdatepaint"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimepaint"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimepaint"))
//						 pstdate3.removeAttr('disabled');			
//					
//					 }
//					 var em= angular.element(document.getElementById("matchmoveteamtypeid")).val();
//					
//						if(em == 1)
//						{
//						 var fm= angular.element(document.getElementById("artistmatchmovedepartmentid"))
//						 fm.removeAttr('disabled');
//						 var gm= angular.element(document.getElementById("vendormatchmovedepartmentid"))
//						 gm.attr('disabled','disabled');	
//						 var hm= angular.element(document.getElementById("freelancermatchmovedepartmentid"))
//						 hm.attr('disabled','disabled');
//						 var pstdate= angular.element(document.getElementById("workstartdatematch"))
//						 pstdate.attr('disabled','disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimematch"))
//						 pstdate2.attr('disabled','disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimematch"))
//						 pstdate3.attr('disabled','disabled');		
//						}
//						else if(em == 2)
//						{
//						var im= angular.element(document.getElementById("vendormatchmovedepartmentid"))
//						im.removeAttr('disabled');
//						var jm= angular.element(document.getElementById("artistmatchmovedepartmentid"))
//						jm.attr('disabled','disabled');	
//						var km= angular.element(document.getElementById("freelancermatchmovedepartmentid"))
//						 km.attr('disabled','disabled');	
//						  var pstdate1= angular.element(document.getElementById("workstartdatematch"))
//						 pstdate2.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimematch"))
//						 pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimematch"))
//						pstdate3.removeAttr('disabled');	
//					
//					 }
//					 else if(em == 3)
//						{
//							
//						var lm= angular.element(document.getElementById("freelancermatchmovedepartmentid"))
//						lm.removeAttr('disabled');
//						var mm= angular.element(document.getElementById("artistmatchmovedepartmentid"))
//						mm.attr('disabled','disabled');	
//						var nm= angular.element(document.getElementById("vendormatchmovedepartmentid"))
//						 nm.attr('disabled','disabled');
//						 var pstdate= angular.element(document.getElementById("workstartdatematch"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimematch"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimematch"))
//						 pstdate3.removeAttr('disabled');			
//					
//					 }
//					 var ec= angular.element(document.getElementById("compteamtypeid")).val();
//					
//						if(ec == 1)
//						{
//						 var fc= angular.element(document.getElementById("artistcompdepartmentid"))
//						 fc.removeAttr('disabled');
//						 var gc= angular.element(document.getElementById("vendorcompdepartmentid"))
//						 gc.attr('disabled','disabled');	
//						 var hc= angular.element(document.getElementById("freelancercompdepartmentid"))
//						 hc.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdatecomp"))
//						 pstdate.attr('disabled','disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimecomp"))
//						 pstdate2.attr('disabled','disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimecomp"))
//						 pstdate3.attr('disabled','disabled');		
//						}
//						else if(ec == 2)
//						{
//						var ic= angular.element(document.getElementById("vendorcompdepartmentid"))
//						ic.removeAttr('disabled');
//						var jc= angular.element(document.getElementById("artistcompdepartmentid"))
//						jc.attr('disabled','disabled');	
//						var kc= angular.element(document.getElementById("freelancercompdepartmentid"))
//						 kc.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdatecomp"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimecomp"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimecomp"))
//						 pstdate3.removeAttr('disabled');		
//					
//					 }
//					 else if(ec == 3)
//						{
//							
//						var lc= angular.element(document.getElementById("freelancercompdepartmentid"))
//						lc.removeAttr('disabled');
//						var mc= angular.element(document.getElementById("artistcompdepartmentid"))
//						mc.attr('disabled','disabled');	
//						var nc= angular.element(document.getElementById("vendorcompdepartmentid"))
//						 nc.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdatecomp"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettimecomp"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtimecomp"))
//						 pstdate3.removeAttr('disabled');		
//					
//					 }
//					  var ed= angular.element(document.getElementById("3dteamtypeid")).val();
//					
//						if(ed == 1)
//						{
//						 var fd= angular.element(document.getElementById("artist3ddepartmentid"))
//						 fd.removeAttr('disabled');
//						 var gd= angular.element(document.getElementById("shotallocation3dvendorid"))
//						 gd.attr('disabled','disabled');	
//						 var hd= angular.element(document.getElementById("freelancer3ddepartmentid"))
//						 hd.attr('disabled','disabled');	
//						  var pstdate= angular.element(document.getElementById("workstartdate3d"))
//						 pstdate.attr('disabled','disabled');
//						  var pstdate2= angular.element(document.getElementById("targettime3d"))
//						 pstdate2.attr('disabled','disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtime3d"))
//						 pstdate3.attr('disabled','disabled');		
//						}
//						else if(ed == 2)
//						{
//						var id= angular.element(document.getElementById("shotallocation3dvendorid"))
//						id.removeAttr('disabled');
//						var jd= angular.element(document.getElementById("artist3ddepartmentid"))
//						jd.attr('disabled','disabled');	
//						var kd= angular.element(document.getElementById("freelancer3ddepartmentid"))
//						 kd.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdate3d"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettime3d"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtime3d"))
//						 pstdate3.removeAttr('disabled');		
//					
//					 }
//					 else if(ed == 3)
//						{
//							
//						var ld= angular.element(document.getElementById("freelancer3ddepartmentid"))
//						ld.removeAttr('disabled');
//						var md= angular.element(document.getElementById("artist3ddepartmentid"))
//						md.attr('disabled','disabled');	
//						var nd= angular.element(document.getElementById("shotallocation3dvendorid"))
//						 nd.attr('disabled','disabled');
//						  var pstdate= angular.element(document.getElementById("workstartdate3d"))
//						pstdate.removeAttr('disabled');
//						  var pstdate2= angular.element(document.getElementById("targettime3d"))
//						pstdate2.removeAttr('disabled');
//						 var pstdate3= angular.element(document.getElementById("completedtime3d"))
//						 pstdate3.removeAttr('disabled');		
//					
//					 }
				 }
				 
				 
			initController();
            function initController() {
			getshotstatusid();			
			getfieldsadd();
			getfieldsdept();
			getfieldsallocate();
			getfieldsstatus();
			getfieldsfinal();			
			getAccessDetails();  
			getprojectdetails();
			getartistroto();
			getartistpaint();
			getartistmatchmove();
			getartistcompositing();
			getartist3d();
			getvendorroto();
			getvendorpaint();
			getvendormatchmove();
			getvendorcompositing();
			getvendor3d();
			getfreelancerroto();
			getfreelancerpaint();
			getfreelancermatchmove();
			getfreelancercompositing();
			getfreelancer3d();
			getshotallocdetails();
			getentityid();   
			gettimedetails();
			assignstatus();  
			assignstatusroto();  
			assignstatuspaint();  
			assignstatusmatchmove();  
			assignstatuscomp();  
			assignstatus3d(); 
			getuserrole(); 									
			}
			function getshotstatusid()
			{
					var shotallocationid=shotallocID;
				UserService.getmaxshotstatusid(shotallocationid).then(function (data) {				
					$scope.shotstatusid = data.data;
					var maxshotstatusid=$scope.shotstatusid
						$scope.versionedit = (maxshotstatusid == '2' && version =='1') ? '0' : '1';	
			
			$scope.versioneditalert=$scope.versionedit;
			 });
				
			}
			function getuserrole()
			{
			 UserService.getuserrole().then(function (data) {				
					$scope.userroleid = data.data;
			 });
			}	
			function assignstatus()
			{
			UserService.assignstatus().then(function (data) {				
		    $scope.assignstatusss= data.data;
				 });
			}
			function assignstatusroto()
			{
			UserService.assignstatusroto().then(function (data) {				
		    $scope.assignstatusr= data.data;
				 });
			}
			function assignstatuspaint()
			{
			UserService.assignstatuspaint().then(function (data) {				
		    $scope.assignstatusp= data.data;
				 });
			}
			function assignstatusmatchmove()
			{
			UserService.assignstatusmatchmove().then(function (data) {				
		    $scope.assignstatusm= data.data;
				 });
			}
			function assignstatuscomp()
			{
			UserService.assignstatuscomp().then(function (data) {				
		    $scope.assignstatusc= data.data;
				 });
			}function assignstatus3d()
			{
			UserService.assignstatus3d().then(function (data) {				
		    $scope.assignstatusdS= data.data;
				 });
			}
			function getfieldsadd()
		    {
		    var shotdetails="shotdetails";
            UserService.getFields(shotdetails).then(function (data) {				
		    $scope.fieldsadd = data.data;			
            	});
         	}
			function getfieldsdept()
		    {
		    var deptdetails="deptdetails";
            UserService.getFields(deptdetails).then(function (data) {				
		    $scope.fieldsdept = data.data;			
            	});
         	}  	
			function getfieldsallocate()
		    {
		    var shotallc="projectallocation";
            UserService.getFields(shotallc).then(function (data) {				
		    $scope.fieldsallocate = data.data;			
            	});
         	}
			function getprojectdetails()
		    {
		    UserService.getProjectDetails().then(function (data) {				
		    $scope.shotallocationprojectdetailsid = data.data;			
            });
         } 	
			function getfieldsstatus()
		    {
		    var shotstatus="shotstatus";
            UserService.getFields(shotstatus).then(function (data) {				
		    $scope.fieldstatus = data.data;			
            	});
         	} 
			function getfieldsfinal()
		    {
		    var shotfinal="shotfinal";
            UserService.getFields(shotfinal).then(function (data) {				
		    $scope.final = data.data;			
            	});
         	} 			
		  
		 function getAccessDetails()
		{
			 UserService.getAccessDetails(userid).then(function (data) {				
					$scope.useraccess = data.data;
			 });
		} 
		function getentityid()
		{
			var name="shotallocation";
			 UserService.getentityid(name).then(function (data) {				
					$scope.entityid = data.data;
			 });
		} 	
		function getartistroto()
		{
			var roto='1';
			UserService.getDeptArtistid(roto).then(function (data) {				
							$scope.shotallocationrotoartistid = data.data;
						});
		} 	
		function getartistpaint()
		{
			var paint='2';
			UserService.getDeptArtistid(paint).then(function (data) {				
							$scope.shotallocationpaintartistid = data.data;
						});
		} 	
		function getartistmatchmove()
		{
			var matchmove='3';
			UserService.getDeptArtistid(matchmove).then(function (data) {				
							$scope.shotallocationmatchmoveartistid = data.data;
						});
		} 	
		function getartistcompositing()
		{
			var comp='4';
			UserService.getDeptArtistid(comp).then(function (data) {				
							$scope.shotallocationcompartistid = data.data;
						});
		} 	
		function getartist3d()
		{
			var threed='5';
			UserService.getDeptArtistid(threed).then(function (data) {				
							$scope.shotallocation3dartistid = data.data;
						});
		} 	
		function getvendorroto()
		{
			var roto='1';
			UserService.getDeptvendorid(roto).then(function (data) {				
							$scope.shotallocationrotovendorid = data.data;
						});
		} 	
		function getvendorpaint()
		{
			var paint='2';
			UserService.getDeptvendorid(paint).then(function (data) {				
							$scope.shotallocationpaintvendorid = data.data;
						});
		} 	
		function getvendormatchmove()
		{
			var matchmove='3';
			UserService.getDeptvendorid(matchmove).then(function (data) {				
							$scope.shotallocationmatchmovevendorid = data.data;
						});
		} 	
		function getvendorcompositing()
		{
			var comp='4';
			UserService.getDeptvendorid(comp).then(function (data) {				
							$scope.shotallocationcompvendorid = data.data;
						});
		} 	
		function getvendor3d()
		{
			var threed='5';
			UserService.getDeptvendorid(threed).then(function (data) {				
							$scope.shotallocation3dvendorid = data.data;
						});
		} 	
		function getfreelancerroto()
		{
			var roto='1';
			UserService.getDeptfreelancerid(roto).then(function (data) {				
							$scope.shotallocationrotofreelancerid= data.data;
						});
		} 	
		function getfreelancerpaint()
		{
			var paint='2';
			UserService.getDeptfreelancerid(paint).then(function (data) {				
							$scope.shotallocationpaintfreelancerid = data.data;
						});
		} 	
		function getfreelancermatchmove()
		{
			var matchmove='3';
			UserService.getDeptfreelancerid(matchmove).then(function (data) {				
							$scope.shotallocationmatchmovefreelancerid = data.data;
						});
		} 	
		function getfreelancercompositing()
		{
			var comp='4';
			UserService.getDeptfreelancerid(comp).then(function (data) {				
							$scope.shotallocationcompfreelancerid = data.data;
						});
		} 	
		function getfreelancer3d()
		{
			var threed='5';
			UserService.getDeptfreelancerid(threed).then(function (data) {				
							$scope.shotallocation3dfreelancerid = data.data;
						});
		} 
		function getshotallocdetails()
		{
			
			var shotallocationid=shotallocID;
			var shstatusi=shotstatusid;
			var versionn=version; 
			var dept1=1;
			var dept2=2;
			var dept3=3;
			var dept4=4;
			var dept5=5;
						UserService.getshotallocdetails(shotallocationid,versionn,dept1).then(function (data) {				
							$scope.entityshotalloc1= data.data;
							 $scope.entityids1=$scope.entityshotalloc1.shotallocationrotoartistid;
							 var es=$scope.entityids1;
							 if(es.indexOf(',') > -1){
								  var match = es.split(',');
								  console.log(match)
    						for (var a in match)
   							 {								
       							 var artistidds = match[a];
										 $("#shotallocationrotoartistid").val(artistidds).find("option[value=" + artistidds +"]").addClass('red');
											}
							  }
							  else
							  {
								   var matchw = es;
								   $("#shotallocationrotoartistid").val(matchw).find("option[value=" + matchw +"]").addClass('red');
							  }
							
							  
						});
						UserService.getshotallocdetails(shotallocationid,versionn,dept2).then(function (data) {				
							$scope.entityshotalloc2= data.data;
							 $scope.entityids2=$scope.entityshotalloc2.shotallocationpaintartistid;							
							 var es2=$scope.entityids2;
							 // alert(es2);
							  if(es2.indexOf(',') > -1){
								  var match2 = es2.split(',');
								   console.log(match2)
    								for (var a2 in match2)
   									 {								
       							 			var artistidds2 = match[a2];
										    $("#shotallocationpaintartistid").val(artistidds2).find("option[value=" + artistidds2 +"]").addClass('red');
											}
							  }
							  else
							  {
								   var matchw2 = es2;
								    $("#shotallocationpaintartistid").val(matchw2).find("option[value=" + matchw2 +"]").addClass('red');
							  }
							
							 
						});
						UserService.getshotallocdetails(shotallocationid,versionn,dept3).then(function (data) {				
							$scope.entityshotalloc3= data.data;
							 $scope.entityids3=$scope.entityshotalloc3.shotallocationmatchmoveartistid;
							  var es3=$scope.entityids3;
							   if(es3.indexOf(',') > -1){
								  var match3 = es3.split(',');
								  console.log(match3)
    							for (var a3 in match3)
   							 		{								
       							 		 var artistidds3 = match[a3];
										 $("#artistmatchmovedepartmentid").val(artistidds3).find("option[value=" + artistidds3 +"]").addClass('red');
											}
							  }
							  else
							  {
								   var matchw3 = es3;
								   $("#artistmatchmovedepartmentid").val(matchw3).find("option[value=" + matchw3 +"]").addClass('red');
							  }
							
							  
						});
						UserService.getshotallocdetails(shotallocationid,versionn,dept4).then(function (data) {				
							$scope.entityshotalloc4= data.data;
							 $scope.entityids4=$scope.entityshotalloc4.shotallocationcompartistid;
							  var es4=$scope.entityids4;
							   if(es4.indexOf(',') > -1){
								  var match4 = es4.split(',');
								  console.log(match4)
    						for (var a4 in match4)
   							 {								
       							         var artistidds4 = match[a4];
										 $("#artistcompdepartmentid").val(artistidds4).find("option[value=" + artistidds4 +"]").addClass('red');
											}
							  }
							  else
							  {
								   var matchw4 = es4;
								    $("#artistcompdepartmentid").val(matchw4).find("option[value=" + matchw4 +"]").addClass('red');
							  }
							
							
							  
						});
						UserService.getshotallocdetails(shotallocationid,versionn,dept5).then(function (data) {				
							$scope.entityshotalloc5= data.data;
							 $scope.entityids5=$scope.entityshotalloc5.shotallocation3dartistid;
							  var es5=$scope.entityids5;
							  if(es5.indexOf(',') > -1){
								 var match5 = es5.split(',');								 						
							   		console.log(match5)
    						for (var a5 in match5)
   							 {								
       							 var artistidds5 = match[a5];								
										 $("#artist3ddepartmentid").val(artistidds5).find("option[value=" + artistidds5 +"]").addClass('red');
											}
							  }
							  else
							  {
								  var artistidds5b = es5;								 
								  $("#artist3ddepartmentid").val(artistidds5b).find("option[value=" + artistidds5b + "]").addClass('red');
							  }
							
						});
		} 
		function gettimedetails()
		{
			
			var shotallocationid=shotallocID;
			var shstatusi=shotstatusid;
			var versionn=version; 
			var dept1=1;
			var dept2=2;
			var dept3=3;
			var dept4=4;
			var dept5=5;
			UserService.gettimedetails(shotallocationid,shstatusi,versionn,dept1).then(function (data) {				
							$scope.timedetailsroto= data.data;
							var timedet=$scope.timedetailsroto;
							if(timedet == 'notfound')
							{
								 $("#rototiming").show();
							}
							else
							{
								 $("#rototimingupdate").show()
							}
						});
						UserService.gettimedetails(shotallocationid,shstatusi,versionn,dept2).then(function (data) {				
							$scope.timedetailspaint= data.data;
							var timedetp=$scope.timedetailspaint;
							if(timedetp  == 'notfound')
							{
								 $("#painttiming").show();
							}
							else
							{
								 $("#painttimingupdate").show()
							}
						});
						UserService.gettimedetails(shotallocationid,shstatusi,versionn,dept3).then(function (data) {				
							$scope.timedetailsmatchmove= data.data;
							var timedetm=$scope.timedetailsmatchmove;
							if(timedetm   == 'notfound')
							{
								 $("#matchmovetiming").show();
							}
							else
							{
								 $("#matchmovetimingupdate").show()
							}
						});
						UserService.gettimedetails(shotallocationid,shstatusi,versionn,dept4).then(function (data) {				
							$scope.timedetailscomp= data.data;
							var timedetc=$scope.timedetailscomp;
							if(timedetc   == 'notfound')
							{
								 $("#comptiming").show();
							}
							else
							{
								 $("#comptimingupdate").show()
							}
						});
						UserService.gettimedetails(shotallocationid,shstatusi,versionn,dept5).then(function (data) {				
							$scope.timedetails3d= data.data;
							var	timedetd=$scope.timedetails3d;						
							if(timedetd  == 'notfound')
							{
								 $("#3dtiming").show();
							}
							else
							{
								 $("#3dtimingupdate").show()
							}
						});
						
		} 		
	}; 
})();