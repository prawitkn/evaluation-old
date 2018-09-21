<?php
  include ("session.php");
	
  include 'head.php'; 
?>

<?php 
	$rootPage = 'evaluate_view';
	$tb = '';

	//$searchWord=( isset($_GET['searchWord']) ? $_GET['searchWord'] : '' );
	//$positionRankId=( isset($_GET['positionRankId']) ? $_GET['positionRankId'] : '' );
?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse"> 

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php
   	$termPersonId=$_GET['tpId'];
   	//$userEvaluatorPersonId=$_GET['epId'];

	/*$sql = "SELECT `Id`, `TermId`, `PersonId`, `EvaluatorPersonId`, `EvaluatorPersonId2`, `EvaluatorPersonId3` 
	FROM `eval_term_person` 
	WHERE Id=:Id 
	";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':Id', $termPersonId);
	$stmt->execute();
	$row = $stmt->fetch();	
	$TermId=$row['TermId'];
	$PersonId=$row['PersonId'];
	$EvaluatorPersonId=$row['EvaluatorPersonId'];
	$EvaluatorPersonId2=$row['EvaluatorPersonId2'];
	$EvaluatorPersonId3=$row['EvaluatorPersonId3'];

	$EvaluatorTotal=0;
	if($EvaluatorPersonId<>0) $EvaluatorTotal+=1;
	if($EvaluatorPersonId2<>0) $EvaluatorTotal+=1;
	if($EvaluatorPersonId3<>0) $EvaluatorTotal+=1;*/
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-users"></i>
       ประเมิน
        <small>เมนู</small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-list"></i>ประเมิน</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    	<?php
    	 $sql = "SELECT tp.id as termPersonId, CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
    	 	,tp.evaluatorPersonId, tp.EvaluatorPersonId2, tp.evaluatorPersonId3
			  , pos.name as positionName, pos.positionRankId, pos.sectionId 
			  , sec.name as sectionName 
			  FROM eval_term_person tp
			  INNER JOIN eval_term t ON t.id=tp.termId 
			  INNER JOIN eval_person p ON p.id=tp.personId 
			  LEFT JOIN eval_position pos ON pos.id=p.positionId 
			  LEFT JOIN eval_section sec ON sec.id=pos.sectionId
			  WHERE 1=1
			  AND tp.id=:id 
			  ";

			    $stmt = $pdo->prepare($sql);        
			  $stmt->bindParam(':id', $termPersonId);
			  $stmt->execute(); 
			  $row=$stmt->fetch();

			  $termPersonId=$row['termPersonId'];			
			  $evaluatorPersonId=$row['evaluatorPersonId'];
			  $evaluatorPersonId2=$row['evaluatorPersonId2'];
			  $evaluatorPersonId3=$row['evaluatorPersonId3'];

    	?>
<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> ผลการประเมิน ตามผู้ประเมิน</label>

			<label style="font-size: 22px; color: blue;" id="evaluateFullName"><?=$row['personFullName'];?></label>
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          
        </div>
        <!-- /.box-tools -->

        </div>
        <!-- /.box-header -->

        <div class="box-body">
			<form id="form1" action="#" method="post" class="form form-inline" novalidate>
			
			<input type="hidden" name="action" value="evalSubmit" />
			<input type="hidden" name="UserEvaluatorPersonId" value="<?=$s_personId;?>" />


	<div class="col-md-12">	
		

	<ul class="nav nav-pills">
		<!--<li class="active"><a data-toggle="pill" href="#home">ข้อมูลผู้รับการประเมิน <i class="fa fa-caret-right"></i></a></li>-->
		<li><a data-toggle="pill" href="#menu1">1. ปริมาณงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu2">2. คุณภาพงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu3">3. ทัศนคติและพฤติกรรม <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu4">4. การมาทำงาน <i class="fa fa-caret-right"></i></a></li><li><a data-toggle="pill" href="#menu5">5. ความคิดเห็น <i class="fa fa-caret-right"></i></a></li>
	</ul>

  <div class="tab-content">
    <!--<div id="home" class="tab-pane fade in active">
	  <?php 

		$sql = "SELECT `Id`, `Code`, `Fullname`, `StartDate`, `DeptName`, `PositionName` 
		FROM `eval_person`
		WHERE Id=:Id 
		";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':Id', $PersonId);
		$stmt->execute();
		$row = $stmt->fetch();	

		?>
	  <div class="row col-md-12">
		<div class="col-md-4">
			<br/>
			<img width="250" height="250" src="dist/img/<?php echo (empty($s_userPicture)? 'avatar5.png' : $s_userPicture) ?> " class="img-circle" alt="">
		</div>
		<div class="col-md-8">
			<h3><?=$row['Code'].' : '.$row['Fullname'];?></h3>
			<h3><?=$row['DeptName'];?></h3>
			<h3><?=$row['PositionName'];?></h3>

			<h3>Evaluator Person ID : <?=$s_personId;?></h3>
		</div>
	  </div>
    </div>-->
    <!--/.tab-pane-->
	
    <div id="menu1" class="tab-pane fade in active"> <?php echo $s_personId.' - '.$s_userGroupCode;?>
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php
			$isScore1=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId == $s_personId  ) { $isScore1=true; } break;		
				default: 	
			}
			if( $isScore1 ){ ?> <th>คะแนน 1</th>	 <?php } ?>

			<?php
			$isScore2=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId2 == $s_personId  ) { $isScore2=true; } break;		
				default: 	
			}
			if( $isScore2 ){ ?> <th>คะแนน 2</th>	 <?php } ?>
		
			<?php
			$isScore3=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId3 == $s_personId  ) { $isScore3=true; } break;		
				default: 	
			}
			if( $isScore3 ){ ?> <th>คะแนน 3</th>	 <?php } ?>
				
			<?php
			$isScoreAvg=false;
			switch ($s_userGroupCode) {
				case 1 : case 3 :
					$isScoreAvg=true; break;		
				default: 	
			}
			if( $isScoreAvg ){ ?> <th>เฉลี่ย</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS ScoreOwn
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score1
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId2=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score2
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId3=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS score3
				,(SELECT IF(COUNT(rHdr.id)=0,1,COUNT(*)) 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.evaluatorPersonId3=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ) AS evaluatorTotal
			FROM `eval_data` t 
			WHERE t.topicGroupId=1 
			AND t.termPersonId=:termPersonId
			ORDER BY t.SeqNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					 <?= $rowNo; ?>
				</td>	
				<td>
					 <?= $row['topicName']; ?>
				</td>				
				<td>
					 <?= $row['scoreOwn']; ?>
				</td>	
				<?php
			$isScore1=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId == $s_personId  ) { $isScore1=true; } break;		
				default: 	
			}
			if( $isScore1 ){ ?> <td><?=$row['score1'];?></td> <?php } ?>

			<?php
			$isScore2=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId2 == $s_personId  ) { $isScore2=true; } break;		
				default: 	
			}
			if( $isScore2 ){ ?> <td><?=$row['score2'];?></td> <?php } ?>
		
			<?php
			$isScore3=false;
			switch ($s_userGroupCode) {
				case 2 :
					if ( $evaluatorPersonId3 == $s_personId  ) { $isScore3=true; } break;		
				default: 	
			}
			if( $isScore3 ){ ?> <td><?=$row['score3'];?></td> <?php } ?>
				
			<?php
			$isScoreAvg=false;
			switch ($s_userGroupCode) {
				case 1 : case 3 :
					$isScoreAvg=true; break;		
				default: 	
			}
			if( $isScoreAvg ){ ?> <td><?=($row['score1']+$row['score1']+$row['score1'])/$evaluatorTotal;?></td>	 <?php } ?>
				
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
    </div>
    <!--/.tab-pane-->
	
	<div id="menu2" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 1</th>	 <?php 
					break;
			}
			?>

			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId2 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 2</th>	 <?php 
					break;
			}
			?>
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId3 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 3</th>	 <?php 
					break;
			}
			?>			
			<?php
			switch ($s_userGroupCode) {
				case 1 :
					?> <th>เฉลี่ย</th>	 <?php 		
				default: 						
					break;
			}
			?>
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`, `MinRank1`, `MinRank2`, `MinRank3`, `MinRank4`, `MinRank5`
				,IFNULL((SELECT rDtl.Score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.PersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS ScoreOwn
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score1
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId2=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score2
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId3=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score3
			FROM `eval_data` t 
			WHERE t.TopicGroupId=2 
			AND t.TermPersonId=:TermPersonId
			ORDER BY t.SeqNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':TermPersonId', $termPersonId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					 <?= $rowNo; ?>
				</td>	
				<td>
					 <?= $row['TopicName']; ?>
				</td>				
				<td>
					 <?= $row['ScoreOwn']; ?>
				</td>				
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score1'];?></td>	 <?php 
						break;
				}
				?>

				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId2 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score2'];?></td>	 <?php 
						break;
				}
				?>
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId3 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score3'];?></td>	 <?php 
						break;
				}
				?>			
				<?php
				switch ($s_userGroupCode) {
					case 1 :
						?> <td><?=($row['Score1']+$row['Score2']+$row['Score3'])/$EvaluatorTotal;?></td>	 <?php 		
					default: 						
						break;
				}
				?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
    </div>
    <!--/.tab-pane-->   
	
	<div id="menu3" class="tab-pane fade">
      	<table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 1</th>	 <?php 
					break;
			}
			?>

			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId2 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 2</th>	 <?php 
					break;
			}
			?>
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId3 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 3</th>	 <?php 
					break;
			}
			?>			
			<?php
			switch ($s_userGroupCode) {
				case 1 :
					?> <th>เฉลี่ย</th>	 <?php 		
				default: 						
					break;
			}
			?>
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`, `MinRank1`, `MinRank2`, `MinRank3`, `MinRank4`, `MinRank5`
				,IFNULL((SELECT rDtl.Score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.PersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS ScoreOwn
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score1
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId2=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score2
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId3=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score3
			FROM `eval_data` t 
			WHERE t.TopicGroupId=3 
			AND t.TermPersonId=:TermPersonId
			ORDER BY t.SeqNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':TermPersonId', $termPersonId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					 <?= $rowNo; ?>
				</td>	
				<td>
					 <?= $row['TopicName']; ?>
				</td>				
				<td>
					 <?= $row['ScoreOwn']; ?>
				</td>				
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score1'];?></td>	 <?php 
						break;
				}
				?>

				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId2 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score2'];?></td>	 <?php 
						break;
				}
				?>
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId3 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score3'];?></td>	 <?php 
						break;
				}
				?>			
				<?php
				switch ($s_userGroupCode) {
					case 1 :
						?> <td><?=($row['Score1']+$row['Score2']+$row['Score3'])/$row['evaluatorTotal'];?></td>	 <?php 		
					default: 						
						break;
				}
				?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
	</div>
	<!--/.tab-pane-->

	<div id="menu4" class="tab-pane fade">
      	<table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 1</th>	 <?php 
					break;
			}
			?>

			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId2 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 2</th>	 <?php 
					break;
			}
			?>
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId3 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 3</th>	 <?php 
					break;
			}
			?>			
			<?php
			switch ($s_userGroupCode) {
				case 1 :
					?> <th>เฉลี่ย</th>	 <?php 		
				default: 						
					break;
			}
			?>
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`, `MinRank1`, `MinRank2`, `MinRank3`, `MinRank4`, `MinRank5`
				,IFNULL((SELECT rDtl.Score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.PersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS ScoreOwn
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score1
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId2=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score2
				,IFNULL((SELECT rDtl.Score FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.Id AND rDtl.SubjectId
				INNER JOIN eval_term_person tp ON tp.Id=rHdr.TermPersonId AND tp.EvaluatorPersonId3=rHdr.EvaluatorPersonId 
				WHERE rDtl.SubjectId=t.Id 
				),0) AS Score3
			FROM `eval_data` t 
			WHERE t.TopicGroupId=4 
			AND t.TermPersonId=:TermPersonId
			ORDER BY t.SeqNo 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':TermPersonId', $termPersonId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					 <?= $rowNo; ?>
				</td>	
				<td>
					 <?= $row['TopicName']; ?>
				</td>				
				<td>
					 <?= $row['ScoreOwn']; ?>
				</td>				
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score1'];?></td>	 <?php 
						break;
				}
				?>

				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId2 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score2'];?></td>	 <?php 
						break;
				}
				?>
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId3 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Score3'];?></td>	 <?php 
						break;
				}
				?>			
				<?php
				switch ($s_userGroupCode) {
					case 1 :
						?> <td><?=($row['Score1']+$row['Score2']+$row['Score3'])/$EvaluatorTotal;?></td>	 <?php 		
					default: 						
						break;
				}
				?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
	</div>
	<!--/.tab-pane-->

	<div id="menu5" class="tab-pane fade">
		<table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 1</th>	 <?php 
					break;
			}
			?>

			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId2 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 2</th>	 <?php 
					break;
			}
			?>
			<?php
			switch ($s_userGroupCode) {
				case 2 :
					if ( $EvaluatorPersonId3 != $s_personId  ) break;				
				default: 	
					?> <th>คะแนน 3</th>	 <?php 
					break;
			}
			?>	
			</tr>		
			<?php 
			$sql = "SELECT t.Remark as RemarkOwn, r2.Remark as Remark1, r3.Remark as Remark2, r4.Remark as Remark3 
			FROM `eval_result` t 
            LEFT JOIN eval_term_person tp ON tp.Id=t.TermPersonId AND t.EvaluatorPersonId=tp.PersonId 
			LEFT JOIN eval_result r2 ON r2.Id=t.Id AND r2.EvaluatorPersonId=t.EvaluatorPersonId 
			LEFT JOIN eval_result r3 ON r3.Id=t.Id AND r3.EvaluatorPersonId=t.EvaluatorPersonId 
			LEFT JOIN eval_result r4 ON r4.Id=t.Id AND r4.EvaluatorPersonId=t.EvaluatorPersonId
			WHERE t.TermPersonId=:TermPersonId
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':TermPersonId', $termPersonId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					 <?= $rowNo; ?>
				</td>	
				<td>
					 ความคิดเห็น : 
				</td>
				<td>
					 <?=$row['RemarkOwn']; ?>
				</td>				
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Remark1'];?></td>	 <?php 
						break;
				}
				?>

				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId2 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Remark2'];?></td>	 <?php 
						break;
				}
				?>
				<?php
				switch ($s_userGroupCode) {
					case 2 :
						if ( $EvaluatorPersonId3 != $s_personId  ) break;				
					default: 	
						?> <td><?=$row['Remark3'];?></td>	 <?php 
						break;
				}
				?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
    </div>	
    <!--/.tab-pane-->

  </div>
    <!--/.tab-content-->

  <div class="col-md-2 pull-right">
	<a href="#" name="btnConfirm" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยันผลการประเมิน</a>
	</div>
  </div>
  <!--/.col-md-12-->

	</form>
	<!--/.form1-->
			
           
    </div>
    <!-- /.box-body -->
  <div class="box-footer">           
    <!--The footer of the box -->

  </div>
  <!-- box-footer -->
</div>
<!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include'footer.php'; ?>
  
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>

<script src="bootstrap/js/smoke.min.js"></script>
<script>
$(document).ready(function() {

	$('a[name=btnSubmit]').click(function(){
		$.post({
			url: '<?=$rootPage;?>_ajax.php',
			data: $("#form1").serialize(),
			dataType: 'json'
		}).done(function (data) { alert(data);					
			if (data.success === "success"){ 
				$.smkAlert({
					text: data.message,
					type: data.success,
					position:'top-center'
				});
				location.reload();
			} else {
				alert(data.message);
				$.smkAlert({
					text: data.message,
					type: data.success,
					position:'top-center'
				});
			}
		}).error(function (response) {
			alert(response.responseText);
		}); 
		e.preventDefault();
	});
	//end btnSubmit2
	
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
