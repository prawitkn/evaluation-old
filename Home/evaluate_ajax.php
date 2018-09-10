<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'evalSubmit' :		
				try{	
					$TermPersonId= $_POST['TermPersonId'];
					$UserEvaluatorPersonId= $_POST['UserEvaluatorPersonId'];
					$arrItmId= $_POST['arrItmId'];
					
					$pdo->beginTransaction();	
					//`Id`, `TermPersonId`, `EvaluatorPersonId`, `Remark`, `CreateTime`, `CreateUserId`, `UpdateTime`, `UpdateUserId` FROM `eval_result`
					$sql = "SELECT Id FROM eval_result WHERE TermPersonId=:TermPersonId AND EvaluatorPersonId=:EvaluatorPersonId LIMIT 1 
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':TermPersonId', $TermPersonId);
					$stmt->bindParam(':EvaluatorPersonId', $UserEvaluatorPersonId);		
					$stmt->execute();	
					$hdrId=0;
					IF( $stmt->rowCount() !=1 ) {
						$sql = "INSERT INTO eval_result (`TermPersonId`, `EvaluatorPersonId`, `Remark`, `CreateTime`, `CreateUserId`) VALUES (:TermPersonId, :EvaluatorPersonId, :Remark, NOW(), :CreateUserId)  
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':TermPersonId', $TermPersonId);
						$stmt->bindParam(':EvaluatorPersonId', $UserEvaluatorPersonId);		
						$stmt->bindParam(':Remark', $Remark);	
						$stmt->bindParam(':CreateUserId', $s_userId);	
						$stmt->execute();	
						$hdrId=$pdo->lastInsertId();
					}else{
						$row=$stmt->fetch();
						$hdrId = $row['Id'];

						$sql = "UPDATE eval_result SET `UpdateTime`=NOW(), `UpdateUserId`=:UpdateUserId WHERE Id=:Id  
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':Id', $hdrId);	
						$stmt->bindParam(':UpdateUserId', $s_userId);	
						$stmt->execute();	
					}

					//Delete all Score
					$sql = "DELETE FROM `eval_result_detail` WHERE HdrId=:HdrId 
						";		
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':HdrId', $hdrId);	
					$stmt->execute();	

					//Insert new all score.
					$arrSubjectId = explode(",",$arrItmId);
					foreach($arrSubjectId as $SubjectId) {    
					   	$sql = "INSERT INTO `eval_result_detail` (`HdrId`, `SubjectId`, `Score`) 
						VALUES (:HdrId, :SubjectId, :Score)
						";		
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':HdrId', $hdrId);		
						$stmt->bindParam(':SubjectId', $SubjectId);
						$stmt->bindParam(':Score', $_POST["$SubjectId"] );	
						$stmt->execute();	
					}

					/*$sql = "
					UPDATE eval_result hdr 
					,(SELECT xh.Id, SUM(xd.Score) sumScore FROM eval_result xh 
						INNER JOIN eval_result_detail xd ON xd.HdrId=xh.Id 
						INNER JOIN eval_term_person xtp ON xtp.Id=xh.TermPersonId AND xtp.EvaluatorPersonId=xh.EvaluatorPersonId
						GROUP BY xh.Id) AS xx
					SET hdr.Score=xx.sumScore 
					WHERE hdr.Id=xx.Id 
					AND hdr.Id=:Id  
					";		
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Id', $hdrId);
					$stmt->execute(); */	

					$sql = "
					UPDATE eval_result hdr 
					,(SELECT xh.Id, SUM(xd.Score) sumScore FROM eval_result xh 
						INNER JOIN eval_result_detail xd ON xd.HdrId=xh.Id 
						GROUP BY xh.Id) AS xx
					SET hdr.Score=xx.sumScore 
					WHERE hdr.Id=xx.Id 
					AND hdr.Id=:Id  
					";		
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':Id', $hdrId);
					$stmt->execute();	

					

					//Commit the transaction.
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => 'success', 'message' => 'Data Inserted Complete.', 'Id' => $hdrId));
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