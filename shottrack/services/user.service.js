(function () {
    'use strict';

    angular
        .module('app')
        .factory('UserService', UserService);

    UserService.$inject = ['$http'];
    function UserService($http) {
        var service = {};
		
		service.getshotscount=getshotscount;
        service.GetAll = GetAll;
		service.GetAllemp = GetAllemp;
		service.GetAllempstatus=GetAllempstatus;
		service.GetAllempproj = GetAllempproj;
		service.getAllempshot = getAllempshot;
		service.GetAllempshotdet = GetAllempshotdet;
        service.getUsername = getUsername;
        service.getUserDetails = getUserDetails;
		service.getClientDetails = getClientDetails;
		service.getProjectDetails = getProjectDetails;
		service.getShotDetails = getShotdetails;
		service.getArtistselDetails = getArtistselDetails;
        service.Create = Create;
        service.addnewuser=addnewuser;
		service.CreateEmp = CreateEmp;
        service.Update = Update;
        service.Delete = Delete;
		service.Deleteemp = Deleteemp;
		service.Deleteproject = Deleteproject;
		service.Deleteprojalloc = Deleteprojalloc;
		service.Loginuser=Loginuser;	
		service.forgotpassword=forgotpassword;
		service.access=access;		
		service.getFields =getFields ;		
		service.addNewArtist=addNewArtist;
		service.getdepartments=getdepartments;
		service.getAllArtists=getAllArtists;
		service.updateArtist=updateArtist;
		service.getDashBoardDetails =getDashBoardDetails;
		service.getAccessDetails =getAccessDetails;	
		service.entity =entity;
		service.updateuser=updateuser;
		service.userentity =userentity;	
		service.getCountryDetails =getCountryDetails;	
		service.getCurrencyDetails =getCurrencyDetails;	
		service.getProjectShotid =getProjectShotid;	
	    service.getDeptArtistid =getDeptArtistid;
		service.getDeptvendorid =getDeptvendorid;
		service.getDeptfreelancerid =getDeptfreelancerid;
		service.getentityid	=getentityid;	
		service.getClientpaymentdetails=getClientpaymentdetails;
		service.Deleteshot=Deleteshot;	
		service.getshotallocdetails=getshotallocdetails;
		service.gettimedetails=gettimedetails;
		service.gettimedetailsvendor=gettimedetailsvendor;
		service.gettimedetailsfreelancer=gettimedetailsfreelancer;
		service.assignstatus=assignstatus;
		service.assignstatusroto=assignstatusroto;
		service.assignstatuspaint=assignstatuspaint;
		service.assignstatusmatchmove=assignstatusmatchmove;
		service.assignstatuscomp=assignstatuscomp;
		service.assignstatus3d=assignstatus3d;
		service.getuserrole=getuserrole;
		service.getProjectdeptid=getProjectdeptid;		
		service.getShotDetailsprojid=getShotDetailsprojid;
		service.getmaxshotstatusid=getmaxshotstatusid;
        service.getDept=getDept;
        service.getShotDet=getShotDet;
        service.getLeads=getLeads;
        service.getroles=getroles;
        service.getAllArtists=getAllArtists;
        service.getShotStatus=getShotStatus;
        service.getShotAllocWorkDetail=getShotAllocWorkDetail;
        service.saveDashBoard=saveDashBoard;
        service.startShotWork=startShotWork;
        service.pauseShotWork=pauseShotWork;
        service.completeShotWork=completeShotWork;        
        service.getDashBoardList=getDashBoardList;
        service.getArtistDetails=getArtistDetails;
        service.getCompanies=getCompanies;
        service.getlevels=getlevels;
        service.getstatus=getstatus;
        service.getProjectHead=getProjectHead;
        service.getSupervisors=getSupervisors;
  //      service.getSupervisors=getSupervisors;
        service.getUserRolefields=getUserRolefields;
        service.getApiCall=getApiCall;
        return service;

        function getLeads() {
            return $http.get("services/getLeads");
        }
        function getArtistDetails(artistid)
        {
        	return $http.get("services/artistdetails?artistid="+artistid);
        }
        function getCompanies()
        {
        	return $http.get("services/companies");
        	
        }

        function getlevels()
        {
        	return $http.get("services/getlevels");
        }
        function getdepartments()
        {
        	return $http.get("services/getdepartments");
        }
        function getstatus()
        {
        	return $http.get("services/getstatus");
        }
        
        function getProjectHead()
        {
        	return $http.get("services/getProjectHead");
        }
        function getUserRolefields(entity){
        	return $http.get("services/getUserRolefields?entity="+entity);
        	
        }
        function getSupervisors()
        {
        	return $http.get("services/getSupervisors");
        }
        
        function getAllArtists()
        {
        	   return $http.get('services/artist');
        }
        function getroles(){
        	return $http.get("services/getroles");
        }
        function getDept(entityid) {
            return $http.get("services/getDept?entityid="+entityid);
        }
		
		function getmaxshotstatusid(shotallocationid) {           
		       return $http.get("services/getmaxshotstatusid?entityid="+shotallocationid);
        }
		function getShotDetailsprojid(dv) {			
			 return $http.get("services/shotdetailsprojid?projname="+dv);
        }
		
		function getProjectdeptid(c) {			
			 return $http.get("services/clientdetailsprojdept?deptid="+c);
        }
		
		function getshotscount(projectidd) {           
		       return $http.get("services/getcountshots?projectiddd="+projectidd);
        }
		function assignstatus() {           
		       return $http.get('services/assignstatus');
        }
		function getuserrole() {           
		       return $http.get('services/getuserrole');
        }
		function assignstatusroto() {           
		       return $http.get('services/assignstatusroto');
        }
		function assignstatuspaint() {           
		       return $http.get('services/assignstatuspaint');
        }
		function assignstatusmatchmove() {           
		       return $http.get('services/assignstatusmatchmove');
        }
		function assignstatuscomp() {           
		       return $http.get('services/assignstatuscomp');
        }
		function assignstatus3d() {           
		       return $http.get('services/assignstatus3d');
        }
       function GetAll() {           
		       return $http.get('services/users');
        }
		function getentityid(name) {           
		       return $http.get("services/getentityid?entityname="+name);
        }
		function getClientpaymentdetails(clientid) {           
		       return $http.get("services/getClientpaymentdetails?clientid="+clientid);
        }
		function getProjectShotid(projectid) {           
		       return $http.get("services/getProjectShotid?projID="+projectid);
        }
		
		function getDashBoardList(){           
		       return $http.get("services/getDashBoardList");
        }
		function getDeptArtistid(departmentid) {           
		       return $http.get("services/getDeptArtistid?departmentid="+departmentid);
        }
		function getDeptvendorid(departmentid) {           
		       return $http.get("services/getDeptVendorid?departmentid="+departmentid);
        }
		function getDeptfreelancerid(departmentid) {           
		       return $http.get("services/getDeptfreelancerid?departmentid="+departmentid);
        }
		function GetAllemp(empID) {           
		       return $http.get("services/employee?empID="+empID);
        }
		function GetAllempstatus(empID) {           
		       return $http.get("services/employeestatus?empID="+empID);
        }
		function GetAllempproj(empID) {           
		       return $http.get("services/allprojdetails?empID="+empID);
        }
		function getAllempshot(empID, deptId, projectId) {           
		       return $http.get("services/allshotdetails?empID="+ empID +"&deptId="+ deptId +"&projectId=" + projectId);
        }
		function GetAllempshotdet(empID) {           
		       return $http.get("services/allshotdetailsdet?empID="+empID);
        }
		function getCountryDetails(regID) {           
		       return $http.get("services/getCountryDetails?regionid="+regID);
        }
		function getCurrencyDetails(couID) {           
		       return $http.get("services/getCurrencyDetails?countryid="+couID);
        }

		function getDashBoardDetails(dashboardname, userid) {           
		       return $http.get('services/getDashBoardDetails?dashboardname='+dashboardname+ '&userid='+userid);
        }
		function getAccessDetails(userid) {           
		       return $http.get("services/getAccessDetails?empID="+userid);
        }
		function access()
		{
			 return $http.get("services/access");
		}	
		function entity()
		{
			 return $http.get("services/entity");
		}
		function userentity(userid)
		{
			 return $http.get("services/userentity?userid="+userid);
		}		
      	function Loginuser(username,password)			
		{
					return $http.post('services/login', {username:username, password:password}).success(function (response){
					return response;
							});
		}
		function getFields(entity) {			
				 return $http.get("services/getfields?entity="+entity);
		} 	
        function getUserDetails(userid) {			
        	console.log("user service page//// "+userid);
			 return $http.get("services/user.php?function=getuserdetails");
			 
        }
		 function getClientDetails(clientidd,idname,name,entityname,urlid) {			
			 return $http.get('services/clientdetails?id='+clientidd+'&name='+name+'&entityname='+entityname+'&idname='+idname+'&urlid='+urlid);
        }
		function getProjectDetails(projectid,idname,name,entityname,urlid) {			
			 return $http.get('services/clientdetailsproj?id='+projectid+'&name='+name+'&entityname='+entityname+'&idname='+idname+'&urlid='+urlid);
        }
        function getApiCall(url) {			
			 return $http.get(url);
       }
		 function getShotdetails(shotid,idname,name,entityname,urlid) {			
			 return $http.get('services/clientdetails?id='+shotid+'&name='+name+'&entityname='+entityname+'&idname='+idname+'&urlid='+urlid);
		 }
		function getArtistselDetails(artistid,idname,name,entityname,urlid) {			
			 return $http.get('services/clientdetails?id='+artistid+'&name='+name+'&entityname='+entityname+'&idname='+idname+'&urlid='+urlid);
        }
		function getArtistDetails(artistid) {
			//replaced user into artist
			 return $http.get("services/artistdetails?artistid="+artistid);
        }
		function getshotallocdetails(shotallocationid,versionn,dept) {			
			 return $http.get('services/getshotallocdetails?shotallocationid='+shotallocationid+'&versionn='+versionn+'&dept='+dept);
        }

        function getShotDet(shotallocationid,versionn) { 
        	return $http.get('services/getShotDet?shotallocationid='+shotallocationid+'&versionn='+versionn);
        }
        
        function getShotAllocWorkDetail(shotallocationid,version) {        
            return $http.get('services/getShotAllocWorkDetail?shotallocationid='+shotallocationid+'&version='+version);
        }
        function getShotStatus() {          	
            return $http.get('services/getShotStatus');
        }
		function gettimedetails(shotallocationid,shstatusi,versionn,dept) {			
			 return $http.get('services/gettimedetails?shotallocationid='+shotallocationid+'&shstatusi='+shstatusi+'&versionn='+versionn+'&dept='+dept);
        }
		function gettimedetailsvendor(shotallocationid,shstatusi,versionn,dept) {			
			 return $http.get('services/gettimedetailsvendor?shotallocationid='+shotallocationid+'&shstatusi='+shstatusi+'&versionn='+versionn+'&dept='+dept);
        }
		function gettimedetailsfreelancer(shotallocationid,shstatusi,versionn,dept) {			
			 return $http.get('services/gettimedetailsfreelancer?shotallocationid='+shotallocationid+'&shstatusi='+shstatusi+'&versionn='+versionn+'&dept='+dept);
        }
		function getUsername(userid)			
		{
			    return $http.get('services/getusername?userid='+userid).success(function (response){
					return response;
							});
		}	
		function forgotpassword(usermail) {			
				 return $http.get("services/forgotpassword?usermail="+usermail);
		}
		    					
        function Create(user) {
            return $http.post('services/create', user).success(function (response){
					return response;
			});
		}
		  					
        function CreateEmp(user) {
            return $http.post('services/createemp', user).success(function (response){
					return response;
			});
		}		
        function Update(user) {
            return $http.post('services/updateuser', user).success(function (response){
            	
            	console.log("prinint response "+response);
					return response;
			});
        }
        function Delete(id) {
        	console.log("user service delete function calls");
            return $http.get('services/user.php?function=removeUser?userid='+id).success(function (response){
            	console.log("ends here");
					return response;
			});
        }
		function Deleteemp(id) {
            return $http.get('services/deleteemp?empid='+id).success(function (response){
					return response;
			});
        }
		function Deleteproject(id) {
            return $http.get('services/deleteproject?empid='+id).success(function (response){
					return response;
			});
        }
		function Deleteprojalloc(id,projid) {
            return $http.get('services/deleteprojalloc?empid='+id).success(function (response){
					return response;
			});
        }
		function Deleteshot(id) {
            return $http.get('services/deleteshot?empid='+id).success(function (response){
					return response;
			});
        }
		
		function saveDashBoard(dashboardName, dashboardRetrievalQuery, retrieveLevel) {
            return $http.get('services/process.php?function=saveDashBoard?dashboardName='+dashboardName+
            		'&dashboardRetrievalQuery='+dashboardRetrievalQuery+'&retrieveLevel='+retrieveLevel).success(function (response){
					return response;
			});
        }
		function updateuser(owner_name,user_id,user_name,user_password,user_email)
		{
			
			console.log("user service page");
            return $http.get('services/updateuser?loggedusername='+owner_name+'&newname='+user_name+'&password='+user_password+'&email='+user_email+'&userid='+user_id).success(function (response){
			return response;
            });
		}
		function addnewuser(loggeduser,username,password,email,role) {
			console.log("uesr service page"+username,password,email,role);
            return $http.get('services/addnewuser?loggedusername='+loggeduser+'&newname='+username+'&password='+password+'&email='+email+'&role='+role).success(function (response){
					return response;
			});
        }
		function addNewArtist()
		{
			return $http.get('services/addartist').success(function (response){
				//alert("addNewArtist in service call");
				
				return response;
			});
			
		}
		function updateArtist(loggeduser,artistid,addname,addemail,adddob,addmobile,addaddress1,addaddress2,addcity,addstate,addcountry,adddoj,addexperience,addgender,addctc,addrole,addsupervisor,addprojecthead,addlevel,addoutputmanday,adddol,addstatus,additional)
		{
			console.log("user service page entered in the name of "+loggeduser);
			return $http.get('services/editartist?loggeduser='+loggeduser+'&newname='+addname+'&artistid='+artistid+'&newemail='+addemail+'&newdob='+adddob+'&newmobile='+addmobile+'&newadd1='+addaddress1+'&newadd2='+addaddress2+'&newcity='+addcity+'&newstate='+addstate+'&newcountry='+addcountry+'&newdoj='+adddoj+'&newexperience='+addexperience+'&newegender='+addgender+'&newctc='+addctc+'&newrole='+addrole+'&newsupervisor='+addsupervisor+'&newprojecthead='+addprojecthead+'&newlevel='+addlevel+'&newoutput='+addoutputmanday+'&newdol='+adddol+'&newstatus='+addstatus+'&additionaldetails'+additional).success(function (response){
					return response;
			});
		}
		
		function startShotWork(shot_dept_artist_details_id) {
            return $http.get('services/startShotWork?shot_dept_artist_details_id=' +shot_dept_artist_details_id).success(function (response){
					return response;
			});
        }
		
		function pauseShotWork(shot_dept_artist_details_id,worked_hours) {
            return $http.get('services/pauseShotWork?shot_dept_artist_details_id=' +shot_dept_artist_details_id+'&operationFlag=pause').success(function (response){
					return response;
			});
        }
		
		function completeShotWork(shot_dept_artist_details_id, worked_hours) {
            return $http.get('services/pauseShotWork?shot_dept_artist_details_id=' +shot_dept_artist_details_id+'&operationFlag=complete').success(function (response){
					return response;
			});
        }	      
    }

})();
