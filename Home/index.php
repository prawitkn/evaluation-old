<?php
    include ("session.php");
	
	//Check user roll.
	switch($s_userGroupCode){
		case 1 :
			header('Location: index2.php');
			break;
			exit();
		case 3 :
			header('Location: index3.php');
			break;
			exit();
		default : 
			header('Location: index2.php');
			break;
			exit();
	}
?>
