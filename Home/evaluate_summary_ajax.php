<?php
    include 'session.php';	
	
	if(!isset($_POST['action'])){		
		header('Content-Type: application/json');
		echo json_encode(array('success' => false, 'message' => 'No action.'));
	}else{
		switch($_POST['action']){
			case 'getListTotal' :
				try{	

					$DeptName=$_POST['DeptName'];
					
					$sql = "SELECT hdr.Id 
					FROM eval_term_person hdr
					INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
					if( $DeptName <> "" ) { $sql .= "WHERE ps.DeptName=:DeptName "; }
					$stmt = $pdo->prepare($sql);				
					if( $DeptName <> "" ) { $stmt->bindParam(':DeptName', $DeptName); }
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
					$DeptName=$_POST['DeptName'];

					$sql = "SELECT hdr.`Id`, hdr.`termId`, hdr.`personId`
					,IFNULL((SELECT xh.Score FROM eval_result xh 
					WHERE xh.TermPersonId=hdr.Id 
					AND xh.EvaluatorPersonId=hdr.EvaluatorPersonId),0) as Score
					,IFNULL((SELECT xh.Score FROM eval_result xh 
					WHERE xh.TermPersonId=hdr.Id 
					AND xh.EvaluatorPersonId=hdr.EvaluatorPersonId2),0) as Score2
					,IFNULL((SELECT xh.Score FROM eval_result xh 
					WHERE xh.TermPersonId=hdr.Id 
					AND xh.EvaluatorPersonId=hdr.EvaluatorPersonId3),0) as Score3
					, ps.Code, ps.Fullname, ps.DeptName, ps.PositionName 
					FROM eval_term_person hdr
					INNER JOIN eval_person ps ON ps.Id=hdr.personId ";
					if( $DeptName <> "" ) { $sql .= "WHERE ps.DeptName=:DeptName "; }
					$sql .= "ORDER BY hdr.Id ASC ";

					$stmt = $pdo->prepare($sql);					
					if( $DeptName <> "" ) { $stmt->bindParam(':DeptName', $DeptName); }
						
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

			default : 
				header('Content-Type: application/json');
				echo json_encode(array('success' => 'warning', 'message' => 'Unknow action.'));				
		}
	}