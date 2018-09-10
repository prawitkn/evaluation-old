<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'danger', 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'add' :				
				try{					
					$code = $_POST['code'];
					$name = $_POST['name'];
					
					// Check duplication?
					$sql = "SELECT Id FROM `eval_user_group` WHERE Code=:code OR Name=:name LIMIT 1 ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':code', $code);
					$stmt->bindParam(':name', $name);
					$stmt->execute();
					if ($stmt->rowCount() == 1){
					  header('Content-Type: application/json');
						$errors = "Error on Data Insertion. Please try new username. ";
						echo json_encode(array('status' => 'warning', 'message' => $errors));
					}else{ 						
						$sql = "INSERT INTO `eval_user_group` (`Code`, `Name`, `StatusId`, `CreateTime`, `CreateUserId`)
						 VALUES (:code,:name,1,NOW(),:s_userId)";
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':code', $code);
						$stmt->bindParam(':name', $name);
						$stmt->bindParam(':s_userId', $s_userId);
						if ($stmt->execute()) {
							header('Content-Type: application/json');
							echo json_encode(array('status' => 'success', 'message' => 'Data Inserted Complete.'));
						} else {
							header('Content-Type: application/json');
							$errors = "Error on Data Insertion. Please try new username. ";
							echo json_encode(array('status' => 'danger', 'message' => $errors));
						}
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 			
				break;
			case 'edit' :
				try{					
					$Id = $_POST['Id'];
					$Code = $_POST['Code'];
					$Name = $_POST['Name'];
					$StatusId = $_POST['StatusId'];
					
					// Check user name duplication?
					$sql = "SELECT Id FROM `eval_user_group` WHERE (Code=:Code OR Name=:Name) AND Id<>:Id LIMIT 1 ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Code', $Code);
					$stmt->bindParam(':Name', $Name);
					$stmt->bindParam(':Id', $Id);
					$stmt->execute();				
					if ($stmt->rowCount() == 1){
					  header('Content-Type: application/json');
					  $errors = "Error on Data Insertion. Duplicate data, Please try new username. " . $pdo->errorInfo()[2];
					  echo json_encode(array('status' => 'warning', 'message' => $errors));  
					  exit;    
					} 	   
					
					//Sql
					$sql = "UPDATE `eval_user_group` SET `Code`=:Code 
					, `Name`=:Name
					, `StatusId`=:StatusId
					, `UpdateTime`=NOW()
					, `UpdateUserId`=:UpdateUserId
					WHERE Id=:Id 
					";	
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Code', $Code);
					$stmt->bindParam(':Name', $Name);
					$stmt->bindParam(':StatusId', $StatusId);
					$stmt->bindParam(':UpdateUserId', $s_userId);
					$stmt->bindParam(':Id', $Id);
					if ($stmt->execute()) {
						  header('Content-Type: application/json');
						  echo json_encode(array('status' => 'success', 'message' => 'Data Updated Complete.'));
					   } else {
						  header('Content-Type: application/json');
						  $errors = "Error on Data Update." . $pdo->errorInfo();
						  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 	
				break;
			case 'setActive' :				
				try{					
					$Id = $_POST['Id'];
					$StatusId = $_POST['StatusId'];	
					
					$sql = "UPDATE eval_user_group SET StatusId=:StatusId, UpdateTime=NOW(), UpdateUserId=:UpdateUserId WHERE id=:Id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':StatusId', $StatusId);
					$stmt->bindParam(':Id', $Id);
					$stmt->bindParam(':UpdateUserId', $s_userId);
					$stmt->execute();	
					if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('status' => 'success', 'message' => 'Data Updated Complete.'));
					} else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Update. Please try new data. " . $pdo->errorInfo();
					  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				} 					
				break;
			case 'remove' :
				try{					
					$Id = $_POST['Id'];	
					
					$sql = "UPDATE eval_user_group SET StatusId=3, UpdateTime=NOW(), UpdateUserId=:UpdateUserId WHERE Id=:Id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Id', $Id);
					$stmt->bindParam(':UpdateUserId', $s_userId);	
					if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('status' => 'success', 'message' => 'Data Removed Complete.'));
					} else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Remove. Please try new data. " . $pdo->errorInfo();
					  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				}
				break;
			case 'delete' :
				try{					
					$Id = $_POST['Id'];
					$StatusId = $_POST['StatusId'];	
					
					
					$sql = "DELETE FROM eval_user_group WHERE Id=:Id ";
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Id', $Id);
					$stmt->execute();	
					if ($stmt->execute()) {
					  header('Content-Type: application/json');
					  echo json_encode(array('status' => 'success', 'message' => 'Data Delete Complete.'));
					} else {
					  header('Content-Type: application/json');
					  $errors = "Error on Data Delete. " . $pdo->errorInfo();
					  echo json_encode(array('status' => 'danger', 'message' => $errors));
					}
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('status' => 'danger', 'message' => $errors));
				}
				break;
			default : 
				header('Content-Type: application/json');
				echo json_encode(array('status' => 'danger', 'message' => 'Unknow action.'));				
		}
	}