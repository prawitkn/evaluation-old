<?php 
include '../sys/db/db.php';

$userName = $_POST['userName'];
$userPassword = $_POST['userPassword'];

//Update last login time
$sql = "UPDATE ".$dtPrefix."user SET loginStatus=0 WHERE NOW() > lastLoginTime + INTERVAL 30 MINUTE   ";		
$stmt = $pdo->prepare($sql);
$stmt->execute();	
	
//Get user 
$sql = "SELECT * FROM ".$dtPrefix."user WHERE userName=:userName LIMIT 1";		
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userName', $userName);
$stmt->execute();
if($stmt->rowCount() == 1){
	session_start();
	
	$row_user = $stmt->fetch();	
	$s_userId=$row_user['userId'];
	$s_username=$row_user['userName'];	
	
	$_SESSION['userId'] = $s_userId;
	$_SESSION['userName'] = $s_username;
	
	// Encript Password
	$salt = "QAzzArVA38rTSm8ctnvrGyDT3ZDVPV88";
	$hashed_userPassword = hash_hmac('md5', $userPassword, $salt);
	
	header('Content-Type: application/json');
	echo json_encode(array('status' => 'success'));
	exit();
	
	if ( !hash_equals ($row_user['userPassword'], $hashed_userPassword) ) {
	    
		
		if($row_user['loginStatus']==0){
			//Set Login 
			//setcookie("loginWh", "1", time()+3600);	//3600=1Hour; 1800=30Min; 60=1Min
			$SID = session_id();
			setcookie("SID", $SID, time()+3600);	//3600=1Hour; 1800=30Min; 60=1Min
			
			$sql = "UPDATE ".$dtPrefix."user SET loginStatus=1, lastLoginTime=NOW(), SID=:SID WHERE userId=:s_userId ";		
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':SID', $SID);
			$stmt->bindParam(':s_userId', $s_userId);
			$stmt->execute();
			
			header('Content-Type: application/json');
			echo json_encode(array('status' => 'success'));
		}else{
			if(isset($_COOKIE['SID'])){
				$tmp=$_COOKIE['SID'];
				if( $tmp == $row_user['SID'] ){
					header('Content-Type: application/json');
					echo json_encode(array('status' => 'success'));
				}else{
					header('Content-Type: application/json');
					$errors = "Another user is logged in this username.". mysqli_error($link);
					echo json_encode(array('status' => 'danger', 'message' => $errors));    
				}
			}else{
				header('Content-Type: application/json');
					$errors = "Another user is logged in this username.". mysqli_error($link);
					echo json_encode(array('status' => 'danger', 'message' => $errors));    
			}			
		} 
	}          
} else {
    header('Content-Type: application/json');
    $errors = "Username or Password incorrect.". mysqli_error($link);
    echo json_encode(array('status' => 'danger', 'message' => $errors));    
}
