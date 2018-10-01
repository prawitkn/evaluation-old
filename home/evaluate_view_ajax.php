<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'evalResultSubmit' :		
				try{	
					$termPersonId= $_POST['termPersonId'];
					$statusId= $_POST['statusId'];
					
					$pdo->beginTransaction();	
					
					$sql = "SELECT id, statusId FROM eval_result WHERE termPersonId=:termPersonId AND evaluatorPersonId=:evaluatorPersonId LIMIT 1 
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':termPersonId', $termPersonId);
					$stmt->bindParam(':evaluatorPersonId', $s_personId);		
					$stmt->execute();	
					
					$row = $stmt->fetch();

					IF( $stmt->rowCount() != 1 ) {
						header('Content-Type: application/json');
						$errors = "Error : ไม่พบข้อมูล";
						echo json_encode(array('success' => 'danger', 'message' => $errors.$termPersonId.$s_personId));
						exit();
					/*}else{
						switch($row['statusId']){
							case 1 : 
								break;
							default : 								
								header('Content-Type: application/json');
								$errors = "Error : สถานะรายการไม่ถูกต้อง";
								echo json_encode(array('success' => 'danger', 'message' => $errors));
								exit();
						}*/
					}					
					$hdrId=$row['id'];

					//update remark
					$sql = "UPDATE eval_result SET `statusId`=:statusId
					,updateTime=NOW(), updateUserId=:updateUserId WHERE id=:id  
					";			
					$stmt = $pdo->prepare($sql);			
					$stmt->bindParam(':statusId', $statusId);
					$stmt->bindParam(':updateUserId', $s_userId);	
					$stmt->bindParam(':id', $hdrId);
					$stmt->execute();										

					//Commit the transaction.
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => 'success', 'message' => 'Data Inserted Complete.', 'id' => $hdrId));
				} 
				//Our catch block will handle any exceptions that are thrown.
				catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error : " . $e->getMessage();
					echo json_encode(array('success' => 'danger', 'message' => $errors));
				}				
				break;	
					
			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}