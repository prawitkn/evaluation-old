<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getListTotal' :
				try{	

					$sectionId=$_POST['sectionId'];
					
					$sql = "SELECT hdr.id 
					FROM eval_term_person hdr
					INNER JOIN eval_term t ON t.id=hdr.termId AND t.isCurrent=1 
					INNER JOIN eval_person ps ON ps.id=hdr.personId ";
					if( $sectionId <> "" ) { $sql .= "WHERE ps.sectionId=:sectionId "; }
					$stmt = $pdo->prepare($sql);				
					if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount ) );
				}catch(Exception $e){
				  	$errors = "Error : " . $e->getMessage();

					header('Content-Type: application/json');
				  	echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'getList' :
				try{
					$start=$_POST['start'];
					$rows=$_POST['rows'];
					$sectionId=$_POST['sectionId'];

					$sql = "
					SELECT *, ROUND((score+score2+score3)/evaluatorTotal, 2) as avgScore 
					FROM (
						SELECT hdr.`id`, hdr.`termId`, hdr.`personId`
						,IFNULL((SELECT xh.score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId),0) as score
						,IFNULL((SELECT xh.score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId2),0) as score2
						,IFNULL((SELECT xh.Score FROM eval_result xh 
						WHERE xh.termPersonId=hdr.Id 
						AND xh.evaluatorPersonId=hdr.evaluatorPersonId3),0) as score3
						,IFNULL((SELECT COUNT(xh.id) FROM eval_result xh
						WHERE xh.evaluatorPersonId<>hdr.personId),1) as evaluatorTotal
						, ps.code, ps.fullName, ps.positionId
						, pos.name as positionName 
						FROM eval_term_person hdr
						INNER JOIN eval_term t ON t.id=hdr.termId AND t.isCurrent=1 
						INNER JOIN eval_person ps ON ps.id=hdr.personId 
						LEFT JOIN eval_position pos ON pos.id=ps.positionId 
						WHERE 1=1 
						";
						if( $sectionId <> "" ) { $sql .= "AND ps.sectionId=:sectionId "; }
					$sql .= ") as tmp "; 
					$sql .= "ORDER BY tmp.id ASC ";

					$stmt = $pdo->prepare($sql);					
					if( $sectionId <> "" ) { $stmt->bindParam(':sectionId', $sectionId); }
						
					$stmt->execute();					
					$rowCount=$stmt->rowCount();

					$jsonData = array();
					while ( $row=$stmt->fetch() ) {						
							$jsonData[] = $row;
					}
					
					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData) ) );
				}catch(Exception $e){
					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors));
				} 
				break;	

			case 'autoGrading' :
				try{
					$pdo->beginTransaction();	

					//reset grade rank
					$sql = "
					UPDATE eval_term_person hdr
					SET hdr.score=0
					, hdr.evaluatorTotal=0
					, hdr.gradeRankId=0
					WHERE hdr.termId = (SELECT x.id FROM eval_term x ORDER BY x.isCurrent DESC LIMIT 1 )
						";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();

					//set score & evalator total
					$sql = "
					UPDATE eval_term_person hdr
					LEFT JOIN (
						SELECT xh.termPersonId
						, COUNT(xh.id) as evaluatorTotal, SUM(xh.score) as sumScore 
						FROM eval_result xh 
						WHERE xh.isSelfEvaluate=0 
						GROUP BY xh.termPersonId 
					) tmp 
					ON tmp.termPersonId=hdr.id

					SET hdr.score=IFNULL((tmp.sumScore/tmp.evaluatorTotal),0)
					, hdr.evaluatorTotal=tmp.evaluatorTotal
					WHERE hdr.termId = (SELECT x.id FROM eval_term x ORDER BY x.isCurrent DESC LIMIT 1 )
						";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();

					//set grade rank id 
					$sql = "
					UPDATE eval_term_person hdr
					SET hdr.gradeRankId=(SELECT gr.id FROM eval_grade_rank gr 
										WHERE hdr.score >= gr.maxScore  
										ORDER BY gr.maxScore DESC  
										LIMIT 1 )
					WHERE hdr.termId = (SELECT x.id FROM eval_term x ORDER BY x.isCurrent DESC LIMIT 1 )
					";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();			
					






					//reset grade 
					$sql = "
					UPDATE eval_term_person hdr
					SET hdr.gradeId=0
					WHERE hdr.termId = (SELECT x.id FROM eval_term x ORDER BY x.isCurrent DESC LIMIT 1 )
						";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();


					//get section list
					$sql = "SELECT id FROM eval_section WHERE statusId=1 ORDER BY seqNo				
					";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();

					while ( $sec = $stmt->fetch() ) {
						//get by operation total
						$sql = "SELECT COUNT(hdr.id) as countTotal 
						FROM eval_term_person hdr
						INNER JOIN eval_person per ON per.id=hdr.personId
						INNER JOIN eval_position pos ON pos.id=per.positionId AND pos.positionRankId=7 AND pos.sectionId=:sectionId
						";
						$stmt2 = $pdo->prepare($sql);	
						$stmt2->bindParam(':sectionId', $sectionId);	
						$stmt2->execute();
						$countTotal=$stmt2->fetch()['countTotal'];

						//get grade A,B,C,D,E list
						$sql = "SELECT id, name, ratio FROM eval_grade WHERE statusId=1 ORDER BY seqNo	
						";
						$stmt2 = $pdo->prepare($sql);		
						$stmt2->execute();
						while ( $gr = $stmt2->fetch() ) {
							$tmpLimit = floor($countTotal*$gr['ratio']/100);
							$sql = "
							UPDATE eval_term_person tp  
							INNER JOIN ( 
									SELECT hdr.id FROM eval_term_person hdr
									INNER JOIN eval_person per ON per.id=hdr.personId
									INNER JOIN eval_position pos ON pos.id=per.positionId AND pos.positionRankId=7 AND pos.sectionId=:sectionId
									WHERE hdr.gradeId IS NULL OR hdr.gradeId=0 
									LIMIT ".$tmpLimit."
									) tmp ON tmp.id=tp.id 						
							SET tp.gradeId=:gradeId 
							";
							$stmt3 = $pdo->prepare($sql);
							$stmt3->bindParam(':sectionId', $sec['id']);
							$stmt3->bindParam(':gradeId', $gr['id']);	
							//$stmt3->bindParam(':ratio', $gr['ratio']);		
							$stmt3->execute();
						}
						//while grade list
					}		
					//while section list



					//get position rank  list
					$sql = "SELECT id FROM eval_position_rank WHERE statusId=1 AND id BETWEEN 1 AND 6 ORDER BY seqNo				
					";
					$stmt = $pdo->prepare($sql);		
					$stmt->execute();

					while ( $pos = $stmt->fetch() ) {
						//get by operation total
						$sql = "SELECT COUNT(hdr.id) as countTotal 
						FROM eval_term_person hdr
						INNER JOIN eval_person per ON per.id=hdr.personId
						INNER JOIN eval_position pos ON pos.id=per.positionId AND pos.positionRankId=:positionRankId 
						";
						$stmt2 = $pdo->prepare($sql);	
						$stmt2->bindParam(':positionRankId', $pos['id']);	
						$stmt2->execute();
						$countTotal=$stmt2->fetch()['countTotal'];

						//get grade A,B,C,D,E list
						$sql = "SELECT id, name, ratio FROM eval_grade WHERE statusId=1 ORDER BY seqNo	
						";
						$stmt2 = $pdo->prepare($sql);		
						$stmt2->execute();
						while ( $gr = $stmt2->fetch() ) {
							$tmpLimit = floor($countTotal*$gr['ratio']/100);
							$sql = "
							UPDATE eval_term_person tp 							
							INNER JOIN ( 
									SELECT hdr.id FROM eval_term_person hdr
									INNER JOIN eval_person per ON per.id=hdr.personId
									INNER JOIN eval_position pos ON pos.id=per.positionId AND pos.positionRankId=:positionRankId 
									WHERE hdr.gradeId IS NULL OR hdr.gradeId=0 
									LIMIT ".$tmpLimit."
									) tmp ON tmp.id=tp.id 						
							SET tp.gradeId=:gradeId 
							";
							$stmt3 = $pdo->prepare($sql);
							$stmt3->bindParam(':positionRankId', $pos['id']);
							$stmt3->bindParam(':gradeId', $gr['id']);	
							//$stmt3->bindParam(':ratio', $gr['ratio']);		
							$stmt3->execute();
						}
						//while grade list
					}		
					//while section list	

					$pdo->commit();

					header('Content-Type: application/json');				
					echo json_encode( array('success' => 'success', 'message' => 'Successs.' ) );					
				}catch(Exception $e){

					$pdo->rollBack();

					header('Content-Type: application/json');
				  $errors = "Error : " . $e->getMessage();
				  echo json_encode(array('success' => 'danger', 'message' => $errors.$sql));
				} 
				break;	

			case 'itemSubmit' :
				try{	
					$pdo->beginTransaction();
					
					foreach($_POST['itmId'] as $index => $item )
					{	
						$sql = "UPDATE eval_term_person set gradeId=:gradeId WHERE id=:id 
						";						
						$stmt = $pdo->prepare($sql);	
						$stmt->bindParam(':gradeId', $_POST['gradeId'][$index]);
						$stmt->bindParam(':id', $item);		
						$stmt->execute();			
					}
					
					$pdo->commit();
					
					header('Content-Type: application/json');
					echo json_encode(array('success' => true, 'message' => 'Data Updated Completed.'));
				}catch(Exception $e){
					//Rollback the transaction.
					$pdo->rollBack();
					//return JSON
					header('Content-Type: application/json');
					$errors = "Error : " . $e->getMessage();
					echo json_encode(array('success' => false, 'message' => $errors));
				}
				break;

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}