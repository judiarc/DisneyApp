<?php
		include('database.php');	  
        $idArr = $_POST['id'];		
        foreach($idArr as $id){			
            mysqli_query($conn,"UPDATE entity_det_table SET is_deleted='1' WHERE id = $id");
			mysqli_query($conn,"UPDATE project_details SET is_deleted='1' WHERE entity_id = $id");
		}
			echo "deleted successfully";
        
        	
?>