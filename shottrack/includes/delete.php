<?php
		include('database.php');	  
        $idArr = $_POST['id'];
        foreach($idArr as $id){
            mysqli_query($conn,"UPDATE users SET is_deleted='1' WHERE id = $id");
		}
			echo "deleted successfully";
        
        	
?>