<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'evalSubmit' :		
				try{	
					$termPersonId= $_POST['termPersonId'];
					$arrItmId= $_POST['arrItmId'];
					
					$pdo->beginTransaction();	
					//`Id`, `TermPersonId`, `EvaluatorPersonId`, `Remark`, `CreateTime`, `CreateUserId`, `UpdateTime`, `UpdateUserId` FROM `eval_result`
					$sql = "SELECT id FROM eval_result WHERE termPersonId=:termPersonId AND evaluatorPersonId=:evaluatorPersonId LIMIT 1 
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':termPersonId', $termPersonId);
					$stmt->bindParam(':evaluatorPersonId', $s_personId);		
					$stmt->execute();	
					
					$hdrId=0;
					IF( $stmt->rowCount() !=1 ) {
						$sql = "INSERT INTO eval_result (`termPersonId`, `evaluatorPersonId`, `createTime`, `createUserId`) VALUES (:termPersonId, :evaluatorPersonId, NOW(), :createUserId)  
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':termPersonId', $termPersonId);
						$stmt->bindParam(':evaluatorPersonId', $s_personId);		
						$stmt->bindParam(':createUserId', $s_userId);	
						$stmt->execute();	
						$hdrId=$pdo->lastInsertId();
					}else{
						$row=$stmt->fetch();
						$hdrId=$row['id'];

						$sql = "UPDATE eval_result SET `updateTime`=NOW(), `updateUserId`=:updateUserId WHERE id=:id   
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':id', $hdrId);	
						$stmt->bindParam(':updateUserId', $s_userId);	
						$stmt->execute();	
					}

					//Insert new all score.
					$arrSubjectId = explode(",",$arrItmId);
					foreach($arrSubjectId as $subjectId) {
						$sql = "SELECT id FROM `eval_result_detail` WHERE hdrId=:hdrId AND subjectId=:subjectId LIMIT 1
						";		
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':hdrId', $hdrId);		
						$stmt->bindParam(':subjectId', $subjectId);
						$stmt->execute();	
						if ( $stmt->rowCount() >= 1 ){
							//Update
							$row=$stmt->fetch();
							$id=$row['id'];
							$sql = "UPDATE `eval_result_detail` SET score=:score WHERE id=:id 
							";		
							$stmt = $pdo->prepare($sql);	
							$stmt->bindParam(':id', $id);
							$stmt->bindParam(':score', $_POST["$subjectId"] );	
							$stmt->execute();	
						}else{
							//insert 
							$sql = "INSERT INTO `eval_result_detail` (`hdrId`, `subjectId`, `score`) 
							VALUES (:hdrId, :subjectId, :score)
							";		
							$stmt = $pdo->prepare($sql);	
							$stmt->bindParam(':hdrId', $hdrId);		
							$stmt->bindParam(':subjectId', $subjectId);
							$stmt->bindParam(':score', $_POST["$subjectId"] );	
							$stmt->execute();	
						}//rowCount
					   
					}//foreach

					//Update score by ratio
					$sql = "
					UPDATE eval_result_detail hdr
					INNER JOIN eval_data dt ON dt.id=hdr.subjectId 
					SET hdr.scoreByRatio=ROUND(hdr.score*dt.topicGroupRatio/100,2)
					WHERE hdr.id IN (".$arrItmId.")
					";		
					$stmt = $pdo->prepare($sql);
					$stmt->execute();			

					//Re-sum total score
					$sql = "
					UPDATE eval_result hdr 
					,(SELECT xh.id, SUM(xd.scoreByRatio) sumScore 
						FROM eval_result xh 
						INNER JOIN eval_result_detail xd ON xd.hdrId=xh.id 
						GROUP BY xh.id) AS xx
					SET hdr.score=xx.sumScore 
					WHERE hdr.id=xx.id 
					AND hdr.id=:id  
					";		
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':id', $hdrId);
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

			case 'evalSubmitRemark' :		
				try{	
					$termPersonId= $_POST['termPersonId'];
					$remark= $_POST['remark'];
					
					$pdo->beginTransaction();	
					//`Id`, `TermPersonId`, `EvaluatorPersonId`, `Remark`, `CreateTime`, `CreateUserId`, `UpdateTime`, `UpdateUserId` FROM `eval_result`
					$sql = "SELECT id FROM eval_result WHERE termPersonId=:termPersonId AND evaluatorPersonId=:evaluatorPersonId LIMIT 1 
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':termPersonId', $termPersonId);
					$stmt->bindParam(':evaluatorPersonId', $s_personId);		
					$stmt->execute();	
					$hdrId=$stmt->fetch()['id'];
					IF( $stmt->rowCount() !=1 ) {
						$sql = "INSERT INTO eval_result (`termPersonId`, `evaluatorPersonId`, `createTime`, `createUserId`) VALUES (:termPersonId, :evaluatorPersonId, NOW(), :createUserId)  
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':termPersonId', $termPersonId);
						$stmt->bindParam(':evaluatorPersonId', $s_personId);		
						$stmt->bindParam(':createUserId', $s_userId);	
						$stmt->execute();	
						$hdrId=$pdo->lastInsertId();
					}

					//update remark
					$sql = "UPDATE eval_result SET `remark`=:remark, `updateTime`=NOW(), `updateUserId`=:updateUserId WHERE id=:id   
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':remark', $remark);	
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

			case 'evalResultConfirm' :		
				try{	
					$termPersonId= $_POST['termPersonId'];
					$remark= $_POST['remark'];
					
					$pdo->beginTransaction();	
					//`Id`, `TermPersonId`, `EvaluatorPersonId`, `Remark`, `CreateTime`, `CreateUserId`, `UpdateTime`, `UpdateUserId` FROM `eval_result`
					$sql = "SELECT id FROM eval_result WHERE termPersonId=:termPersonId AND evaluatorPersonId=:evaluatorPersonId LIMIT 1 
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':termPersonId', $termPersonId);
					$stmt->bindParam(':evaluatorPersonId', $s_personId);		
					$stmt->execute();	
					$hdrId=$stmt->fetch()['id'];
					IF( $stmt->rowCount() !=1 ) {
						$sql = "INSERT INTO eval_result (`termPersonId`, `evaluatorPersonId`, `createTime`, `createUserId`) VALUES (:termPersonId, :evaluatorPersonId, NOW(), :createUserId)  
						";			
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':termPersonId', $termPersonId);
						$stmt->bindParam(':evaluatorPersonId', $s_personId);		
						$stmt->bindParam(':createUserId', $s_userId);	
						$stmt->execute();	
						$hdrId=$pdo->lastInsertId();
					}

					//update remark
					$sql = "UPDATE eval_result SET `remark`=:remark, `updateTime`=NOW(), `updateUserId`=:updateUserId WHERE id=:id   
					";			
					$stmt = $pdo->prepare($sql);	
					$stmt->bindParam(':remark', $remark);	
					$stmt->bindParam(':updateUserId', $s_userId);	
					$stmt->bindParam(':id', $hdrId);
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