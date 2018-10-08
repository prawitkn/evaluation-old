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
   	//term sql
	  $termId=( isset($_GET['termId']) ? $_GET['termId'] : '' );

	  $sql = "SELECT hdr.id 
	  FROM eval_term hdr 
	  WHERE 1=1 
	  AND hdr.statusId=1 ";
	  if( $termId<> "" ){ $sql .= "AND hdr.id=:id "; }
	  $sql .= "ORDER BY hdr.isCurrent DESC, hdr.id DESC ";
	  $sql .= "LIMIT 1 ";

	  $stmt = $pdo->prepare($sql);  
	  if( $termId<> "" ){ $stmt->bindParam(':id', $termId); }      
	  //echo $sql;
	  $stmt->execute(); 
	  $termId=$stmt->fetch()['id'];




	  //personId 
	  $personId=( isset($_GET['personId']) ? $_GET['personId'] : $s_personId );

	  $evaluatorId=$s_personId; 
	  
	   $sql = "SELECT tp.id as termPersonId, CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
	 	,tp.evaluatorPersonId, tp.evaluatorPersonId2, tp.evaluatorPersonId3
	 	,tp.score, tp.evaluatorTotal 
		  , pos.name as positionName, pos.positionRankId, pos.sectionId 
		  , sec.name as sectionName 
		  FROM eval_term_person tp
		  INNER JOIN eval_term t ON t.id=tp.termId 
		  INNER JOIN eval_person p ON p.id=tp.personId 
		  LEFT JOIN eval_position pos ON pos.id=p.positionId 
		  LEFT JOIN eval_section sec ON sec.id=pos.sectionId	  	
		  WHERE 1=1
		   AND tp.termId=:termId 
	 	 AND tp.personId=:personId
		  ";

		    $stmt = $pdo->prepare($sql);        
		  $stmt->bindParam(':termId', $termId);
		  $stmt->bindParam(':personId', $personId);
		  $stmt->execute(); 
		  $row=$stmt->fetch();

		  $termPersonId=$row['termPersonId'];
		  $evaluatorPersonId=$row['evaluatorPersonId'];
		  $evaluatorPersonId2=$row['evaluatorPersonId2'];
		  $evaluatorPersonId3=$row['evaluatorPersonId3'];

		   $sql = "SELECT hd.id, hd.statusId 
		  FROM eval_result hd 
		  WHERE 1=1
		   AND hd.termPersonId=:termPersonId 
	 	 AND hd.evaluatorPersonId=:evaluatorPersonId
		  ";

		    $stm = $pdo->prepare($sql);        
		  $stm->bindParam(':termPersonId', $termPersonId);
		  $stm->bindParam(':evaluatorPersonId', $s_personId);
		  $stm->execute(); 
		  $r=$stm->fetch();

		  $hdrId=$r['id'];
		  $statusId=$r['statusId'];
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

			$isScore1=true;
			switch ($s_userGroupCode) {
				case 1 : case 2 :
					if ( $evaluatorPersonId != $s_personId  ) { $isScore1=false; } break;		
				default: 	
			}
			$isScore2=true;
			switch ($s_userGroupCode) {
				case 1 : case 2 :
					if ( $evaluatorPersonId2 != $s_personId  ) { $isScore2=false; } break;		
				default: 	
			}
			$isScore3=true;
			switch ($s_userGroupCode) {
				case 1 : case 2 :
					if ( $evaluatorPersonId3 != $s_personId  ) { $isScore3=false; } break;		
				default: 	
			}
			$isScoreAvg=true;
			switch ($s_userGroupCode) {
				case 3 : break;		
				default: 	
					$isScoreAvg=false; break;	
			}
    	?>
<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> ผลการประเมิน แยกตามผู้ประเมิน</label>

			<label style="font-size: 22px; color: blue;" id="evaluateFullName"><?=$row['personFullName'];?></label> / <?=$row['positionName'];?>
		
			
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          
        </div>
        <!-- /.box-tools -->

        	<form id="form2" action="#" method="post" class="form form-inline" novalidate>
				<div class="pull-right">
					<input type="hidden" name="action" value="evalResultSubmit" />
					<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />	

					<?php if ( $statusId == 1 ) { ?>
						<input type="hidden" name="statusId" value="2" />
						<a href="#" name="btnStatusSubmit" class="btn btn-danger"><i class="fa fa-save"></i> ยืนยันผลการประเมิน</a>
					<?php }else{ ?>
						<input type="hidden" name="statusId" value="1" />
						<a href="#" name="btnStatusSubmit" class="btn btn-danger"><i class="fa fa-edit"></i> แก้ไขผลการประเมิน</a>

						<a href="#" name="" class="btn btn-success" disabled><i class="fa fa-check"></i> ยืนยันผลการประเมินแล้ว</a>
					<?php } ?>
				</div>
			</form>
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
   
	
    <div id="menu1" class="tab-pane fade in active"> 
      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	

			<?php if( $isScore1 ){ ?> <th>ผู้ประเมิน 1</th>	 <?php } ?>
			<?php if( $isScore2 ){ ?> <th>ผู้ประเมิน 2</th>	 <?php } ?>
			<?php if( $isScore3 ){ ?> <th>ผู้ประเมิน 3</th>	 <?php } ?>
			<?php if( $isScoreAvg ){ ?> <th>เฉลี่ย</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`, `topicDesc`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS scoreOwn

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
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId<>rHdr.evaluatorPersonId 
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
					 <?= $row['topicDesc']; ?>
				</td>				
				<td style="text-align: right;">
					 <?= $row['scoreOwn']; ?>
				</td>	
				<?php
			if( $isScore1 ){ ?> <td style="text-align: right;"><?=$row['score1'];?></td> <?php } 
			if( $isScore2 ){ ?> <td style="text-align: right;"><?=$row['score2'];?></td> <?php } 
			if( $isScore3 ){ ?> <td style="text-align: right;"><?=$row['score3'];?></td> <?php } 
			if( $isScoreAvg ){ ?> <td style="text-align: right;"><?=($row['score1']+$row['score2']+$row['score3'])/$row['evaluatorTotal'];?></td>	 <?php }
			?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
    </div>
    <!--/.tab-pane-->
	
	<div id="menu2" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php if( $isScore1 ){ ?> <th>ผู้ประเมิน 1</th>	 <?php } ?>
			<?php if( $isScore2 ){ ?> <th>ผู้ประเมิน 2</th>	 <?php } ?>
			<?php if( $isScore3 ){ ?> <th>ผู้ประเมิน 3</th>	 <?php } ?>
			<?php if( $isScoreAvg ){ ?> <th>เฉลี่ย</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS scoreOwn
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
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId<>rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ) AS evaluatorTotal
			FROM `eval_data` t 
			WHERE t.topicGroupId=2 
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
				<td style="text-align: right;">
					 <?= $row['scoreOwn']; ?>
				</td>	
				<?php
			if( $isScore1 ){ ?> <td style="text-align: right;"><?=$row['score1'];?></td> <?php } 
			if( $isScore2 ){ ?> <td style="text-align: right;"><?=$row['score2'];?></td> <?php } 
			if( $isScore3 ){ ?> <td style="text-align: right;"><?=$row['score3'];?></td> <?php } 
			if( $isScoreAvg ){ ?> <td style="text-align: right;"><?=($row['score1']+$row['score1']+$row['score1'])/$row['evaluatorTotal'];?></td>	 <?php }
			?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
    </div>
    <!--/.tab-pane-->   
	
	<div id="menu3" class="tab-pane fade">
      	<table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php if( $isScore1 ){ ?> <th>ผู้ประเมิน 1</th>	 <?php } ?>
			<?php if( $isScore2 ){ ?> <th>ผู้ประเมิน 2</th>	 <?php } ?>
			<?php if( $isScore3 ){ ?> <th>ผู้ประเมิน 3</th>	 <?php } ?>
			<?php if( $isScoreAvg ){ ?> <th>เฉลี่ย</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS scoreOwn
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
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId<>rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ) AS evaluatorTotal
			FROM `eval_data` t 
			WHERE t.topicGroupId=3 
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
			if( $isScore1 ){ ?> <td style="text-align: right;"><?=$row['score1'];?></td> <?php } 
			if( $isScore2 ){ ?> <td style="text-align: right;"><?=$row['score2'];?></td> <?php } 
			if( $isScore3 ){ ?> <td style="text-align: right;"><?=$row['score3'];?></td> <?php } 
			if( $isScoreAvg ){ ?> <td style="text-align: right;"><?=($row['score1']+$row['score1']+$row['score1'])/$row['evaluatorTotal'];?></td>	 <?php }
			?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
	</div>
	<!--/.tab-pane-->

	<div id="menu4" class="tab-pane fade">
      	<table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php if( $isScore1 ){ ?> <th>ผู้ประเมิน 1</th>	 <?php } ?>
			<?php if( $isScore2 ){ ?> <th>ผู้ประเมิน 2</th>	 <?php } ?>
			<?php if( $isScore3 ){ ?> <th>ผู้ประเมิน 3</th>	 <?php } ?>
			<?php if( $isScoreAvg ){ ?> <th>เฉลี่ย</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "SELECT `id`, `termPersonId`, `evalTypeId`, `evalTypeName`, `topicGroupId`, `topicGroupName`, `seqNo`, `topicId`, `topicName`
				,IFNULL((SELECT rDtl.score 
				FROM eval_result rHdr
				INNER JOIN eval_result_detail rDtl ON rDtl.hdrId=rHdr.id 
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId=rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ),0) AS scoreOwn
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
				INNER JOIN eval_term_person tp ON tp.id=rHdr.termPersonId AND tp.personId<>rHdr.evaluatorPersonId 
				WHERE rDtl.subjectId=t.id ) AS evaluatorTotal
			FROM `eval_data` t 
			WHERE t.topicGroupId=4 
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
				<td style="text-align: right;">
					 <?= $row['scoreOwn']; ?>
				</td>	
				<?php
			if( $isScore1 ){ ?> <td style="text-align: right;"><?=$row['score1'];?></td> <?php } 
			if( $isScore2 ){ ?> <td style="text-align: right;"><?=$row['score2'];?></td> <?php } 
			if( $isScore3 ){ ?> <td style="text-align: right;"><?=$row['score3'];?></td> <?php } 
			if( $isScoreAvg ){ ?> <td style="text-align: right;"><?=($row['score1']+$row['score1']+$row['score1'])/$evaluatorTotal;?></td>	 <?php }
			?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>
	</div>
	<!--/.tab-pane-->

	<div id="menu5" class="tab-pane fade">
		<table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>			
				<th>ประเมินตนเอง</th>	
			<?php if( $isScore1 ){ ?> <th>ผู้ประเมิน 1</th>	 <?php } ?>
			<?php if( $isScore2 ){ ?> <th>ผู้ประเมิน 2</th>	 <?php } ?>
			<?php if( $isScore3 ){ ?> <th>ผู้ประเมิน 3</th>	 <?php } ?>
		
			</tr>		
			<?php 
			$sql = "
			SELECT hd.id
			,(SELECT x.remark FROM eval_result x WHERE x.termPersonId=hd.id AND x.evaluatorPersonId=hd.personId ) as remarkOwn
			,(SELECT x.remark FROM eval_result x WHERE x.termPersonId=hd.id AND x.evaluatorPersonId=hd.evaluatorPersonId ) as remark1
			,(SELECT x.remark FROM eval_result x WHERE x.termPersonId=hd.id AND x.evaluatorPersonId=hd.evaluatorPersonId2 ) as remark2
			,(SELECT x.remark FROM eval_result x WHERE x.termPersonId=hd.id AND x.evaluatorPersonId=hd.evaluatorPersonId3 ) as remark3
			
			FROM eval_term_person hd 
			WHERE hd.id=:termPersonId 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			//$stmt->bindParam(':evaluatorPersonId', $evaluatorId);


		
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td>
					
				</td>	
				<td>
					
				</td>				
				<td>
					 <?= $row['remarkOwn']; ?>
				</td>	
				<?php
			if( $isScore1 ){ ?> <td><?=$row['remark1'];?></td> <?php } 
			if( $isScore2 ){ ?> <td><?=$row['remark2'];?></td> <?php } 
			if( $isScore3 ){ ?> <td><?=$row['remark3'];?></td> <?php } 
			?>
			</tr>
			<?php $rowNo +=1; } ?>
		</table>

		
    </div>	
    <!--/.tab-pane-->

  </div>
    <!--/.tab-content-->

  <div class="col-md-2 pull-right">
	
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

	$('a[name=btnStatusSubmit]').click(function(){
		$.post({
			url: '<?=$rootPage;?>_ajax.php',
			data: $("#form2").serialize(),
			dataType: 'json'
		}).done(function (data) { //alert(data);					
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
	//end btnConfirm
	
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
