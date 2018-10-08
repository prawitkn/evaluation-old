<?php
  include ("session.php");
	
  include 'head.php'; 
?>
    
</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php

   $rootPage = 'evaluate';
	$tb = '';

   	//$termPersonId=( isset($_GET['tpId']) ? $_GET['tpId'] : '' );

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
	 	,tp.evaluatorPersonId, tp.EvaluatorPersonId2, tp.evaluatorPersonId3
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


	   
   	//$userEvaluatorPersonId=$_GET['epId'];
   //	$arrItmId = array();

 
		  //$statusId=$row['statusId'];



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

<!-- To allow only admin to access the content -->      
    <div class="box box-primary">
        <div class="box-header with-border">
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> ผู้รับการประเมิน : 

        	</label>

        	<label style="font-size: 22px; color: blue;" id="evaluateFullName"><?=$row['personFullName'];?></label> / <?=$row['positionName'].' '.$row['sectionName'];?>

			<!--<a href="<?=$rootPage;?>_add.php?id=" class="btn btn-primary"><i class="fa fa-plus"></i> Add user group</a>-->
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          
        </div>
        <!-- /.box-tools -->

        </div>
        <!-- /.box-header -->

        <div class="box-body">
        	<?php //echo 'aaaaabc'.$termPersonId; 
        	$sql = "SELECT tp.id as termPersonId, CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
	 	,tp.evaluatorPersonId, tp.EvaluatorPersonId2, tp.evaluatorPersonId3
	 	,tp.score, tp.evaluatorTotal 
		  , pos.name as positionName, pos.positionRankId, pos.sectionId 
		  , sec.name as sectionName 
		  FROM eval_term_person tp
		  INNER JOIN eval_term t ON t.id=tp.termId 
		  INNER JOIN eval_person p ON p.id=tp.personId 
		  LEFT JOIN eval_position pos ON pos.id=p.positionId 
		  LEFT JOIN eval_section sec ON sec.id=pos.sectionId	  	
		  WHERE 1=1
		  AND tp.id=:termPersonId 
		  ";

		    $stmt = $pdo->prepare($sql);        
		  $stmt->bindParam(':termPersonId', $termPersonId);
		  $stmt->execute(); 
		  $row=$stmt->fetch();

		  $termPersonId=$row['termPersonId'];			
		  $evaluatorPersonId=$row['evaluatorPersonId'];
		  $evaluatorPersonId2=$row['evaluatorPersonId2'];
		  $evaluatorPersonId3=$row['evaluatorPersonId3'];

?>

			
			
			


	<div class="col-md-12">				
	<ul class="nav nav-pills">
		<!--<li class="active"><a data-toggle="pill" href="#home">ข้อมูลผู้รับการประเมิน <i class="fa fa-caret-right"></i></a></li>-->
		<li class="active"><a data-toggle="pill" href="#menu1">1. ปริมาณงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu2">2. คุณภาพงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu3">3. ทัศนคติและพฤติกรรม <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu4">4. การมาทำงาน <i class="fa fa-caret-right"></i></a></li><li><a data-toggle="pill" href="#menu5">5. ความคิดเห็น </a></li>
	</ul>

  <div class="tab-content">
    
    <div id="menu1" class="tab-pane fade in active">
    <form id="form1" action="#" method="post" class="form form-inline" validate >
    	<input type="hidden" name="action" value="evalSubmit" />
		<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />

      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>				
				<th>คำอธิบาย</th>
				<th style="width: 80px; text-align: center;">ระดับ 1 0-60</th>	
				<th style="width: 80px; text-align: center;">ระดับ 2 61-70</th>
				<th style="width: 80px; text-align: center;">ระดับ 3 71-80</th>
				<th style="width: 80px; text-align: center;">ระดับ 4 81-90</th>
				<th style="width: 80px; text-align: center;">ระดับ 5 91-100</th>	
				<th style="width: 100px; text-align: center;">คะแนนที่ได้ 0 - 100</th>	
			</tr>		
			<?php 
			$arrItmId = array();
			$sql = "SELECT t.`id`, t.`termPersonId`, t.`evalTypeId`, t.`evalTypeName`, t.`topicGroupId`, t.`topicGroupName`, t.`seqNo`, t.`topicId`, t.`topicName`
			, (SELECT rd.score FROM eval_result rh 
						INNER JOIN eval_result_detail rd ON rd.hdrId=rh.id 
						WHERE rh.termPersonId=t.termPersonId 
						AND rd.subjectId=t.id 						
						AND rh.evaluatorPersonId=:evaluatorPersonId) as score 
			FROM `eval_data` t 
			WHERE t.topicGroupId=1
			AND t.termPersonId=:termPersonId
			ORDER BY t.seqNo 
			"; 
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->bindParam(':evaluatorPersonId', $evaluatorId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td><?= $rowNo; ?></td>	
				<td><?= $row['topicName']; ?></td>	
				<td><?= $row['topicDesc']; ?></td>				
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>				
				<td>
					 <input type="text" name="<?=$row['id'];?>" class="form-control" style="width: 80px; text-align: right;" value="<?=$row['score'];?>" data-skm-msg="จำเป็น" required />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['id'];
			$rowNo +=1; }
			?>
		</table>

		<input type="hidden" name="arrItmId" value="<?=implode(",", $arrItmId);?>" />
	</form>
	<!--form-->


<a href="#" name="btnSubmit1" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึก ปริมาณงาน</a>

    </div>
    <!--/.tab-pane-->

    <div id="menu2" class="tab-pane fade">
      <form id="form2" action="#" method="post" class="form form-inline" validate >
    	<input type="hidden" name="action" value="evalSubmit" />
			<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />

      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>				
				<th>คำอธิบาย</th>
				<th style="width: 80px; text-align: center;">ระดับ 1 0-60</th>	
				<th style="width: 80px; text-align: center;">ระดับ 2 61-70</th>
				<th style="width: 80px; text-align: center;">ระดับ 3 71-80</th>
				<th style="width: 80px; text-align: center;">ระดับ 4 81-90</th>
				<th style="width: 80px; text-align: center;">ระดับ 5 91-100</th>	
				<th style="width: 100px; text-align: center;">คะแนนที่ได้ 0 - 100</th>	
			</tr>		
			<?php 
			$arrItmId = array();

			$sql = "SELECT t.`id`, t.`termPersonId`, t.`evalTypeId`, t.`evalTypeName`, t.`topicGroupId`, t.`topicGroupName`, t.`seqNo`, t.`topicId`, t.`topicName`
			, (SELECT rd.score FROM eval_result rh 
						INNER JOIN eval_result_detail rd ON rd.hdrId=rh.id 
						WHERE rh.termPersonId=t.termPersonId 
						AND rd.subjectId=t.id 						
						AND rh.evaluatorPersonId=:evaluatorPersonId) as score 
			FROM `eval_data` t 
			WHERE t.topicGroupId=2 
			AND t.termPersonId=:termPersonId
			ORDER BY t.seqNo 
			"; 
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->bindParam(':evaluatorPersonId', $evaluatorId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td><?= $rowNo; ?></td>	
				<td><?= $row['topicName']; ?></td>	
				<td><?= $row['topicDesc']; ?></td>				
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>						
				<td>
					 <input type="text" name="<?=$row['id'];?>" class="form-control" style="width: 80px; text-align: right;" value="<?=$row['score'];?>" data-skm-msg="จำเป็น" required />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['id'];
			$rowNo +=1; }
			?>
		</table>

		<input type="hidden" name="arrItmId" value="<?=implode(",", $arrItmId);?>" />
	</form>
	<!--form-->


<a href="#" name="btnSubmit2" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึก คุณภาพงาน</a>
    </div>
    <!--/.tab-pane-->

    <div id="menu3" class="tab-pane fade">
      <form id="form3" action="#" method="post" class="form form-inline" validate >
    	<input type="hidden" name="action" value="evalSubmit" />
			<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />

      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>				
				<th>คำอธิบาย</th>
				<th style="width: 80px; text-align: center;">ระดับ 1 0-60</th>	
				<th style="width: 80px; text-align: center;">ระดับ 2 61-70</th>
				<th style="width: 80px; text-align: center;">ระดับ 3 71-80</th>
				<th style="width: 80px; text-align: center;">ระดับ 4 81-90</th>
				<th style="width: 80px; text-align: center;">ระดับ 5 91-100</th>	
				<th style="width: 100px; text-align: center;">คะแนนที่ได้ 0 - 100</th>	
			</tr>		
			<?php 
			$arrItmId = array();

			$sql = "SELECT t.`id`, t.`termPersonId`, t.`evalTypeId`, t.`evalTypeName`, t.`topicGroupId`, t.`topicGroupName`, t.`seqNo`, t.`topicId`, t.`topicName`
			, (SELECT rd.score FROM eval_result rh 
						INNER JOIN eval_result_detail rd ON rd.hdrId=rh.id 
						WHERE rh.termPersonId=t.termPersonId 
						AND rd.subjectId=t.id 						
						AND rh.evaluatorPersonId=:evaluatorPersonId) as score 
			FROM `eval_data` t 
			WHERE t.topicGroupId=3 
			AND t.termPersonId=:termPersonId
			ORDER BY t.seqNo 
			"; 
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->bindParam(':evaluatorPersonId', $evaluatorId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td><?= $rowNo; ?></td>	
				<td><?= $row['topicName']; ?></td>	
				<td><?= $row['topicDesc']; ?></td>				
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>				
				<td>
					 <input type="text" name="<?=$row['id'];?>" class="form-control" style="width: 80px; text-align: right;" value="<?=$row['score'];?>" data-skm-msg="จำเป็น" required />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['id'];
			$rowNo +=1; }
			?>
		</table>

		<input type="hidden" name="arrItmId" value="<?=implode(",", $arrItmId);?>" />
	</form>
	<!--form-->


<a href="#" name="btnSubmit3" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึก ทัศนคติและพฤติกรรม</a>
    </div>
    <!--/.tab-pane-->

    <div id="menu4" class="tab-pane fade">
      <form id="form4" action="#" method="post" class="form form-inline" validate >
    	<input type="hidden" name="action" value="evalSubmit" />
			<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />

      <table class="table table-striped">
			<tr>
				<th>ลำดับ</th>
				<th>หัวข้อการประเมิน</th>				
				<th>คำอธิบาย</th>
				<th style="width: 80px; text-align: center;">ระดับ 1 0-60</th>	
				<th style="width: 80px; text-align: center;">ระดับ 2 61-70</th>
				<th style="width: 80px; text-align: center;">ระดับ 3 71-80</th>
				<th style="width: 80px; text-align: center;">ระดับ 4 81-90</th>
				<th style="width: 80px; text-align: center;">ระดับ 5 91-100</th>	
				<th style="width: 100px; text-align: center;">คะแนนที่ได้ 0 - 100</th>	
			</tr>		
			<?php 
			$arrItmId = array();

			$sql = "SELECT t.`id`, t.`termPersonId`, t.`evalTypeId`, t.`evalTypeName`, t.`topicGroupId`, t.`topicGroupName`, t.`seqNo`, t.`topicId`, t.`topicName`
			, (SELECT rd.score FROM eval_result rh 
						INNER JOIN eval_result_detail rd ON rd.hdrId=rh.id 
						WHERE rh.termPersonId=t.termPersonId 
						AND rd.subjectId=t.id 						
						AND rh.evaluatorPersonId=:evaluatorPersonId) as score 
			FROM `eval_data` t 
			WHERE t.topicGroupId=4
			AND t.termPersonId=:termPersonId
			ORDER BY t.seqNo 
			"; 
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->bindParam(':evaluatorPersonId', $evaluatorId);
			$stmt->execute();
			$rowNo=1; while ($row = $stmt->fetch() ) { 			
			?>
			<tr>
				<td><?= $rowNo; ?></td>	
				<td><?= $row['topicName']; ?></td>	
				<td><?= $row['topicDesc']; ?></td>				
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>			
				<td> </td>				
				<td>
					 <input type="text" name="<?=$row['id'];?>" class="form-control" style="width: 80px; text-align: right;" value="<?=$row['score'];?>" data-skm-msg="จำเป็น" required />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['id'];
			$rowNo +=1; }
			?>
		</table>

		<input type="hidden" name="arrItmId" value="<?=implode(",", $arrItmId);?>" />
	</form>
	<!--form-->


<a href="#" name="btnSubmit4" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึก การมาทำงาน</a>
    </div>
    <!--/.tab-pane-->
	

	<div id="menu5" class="tab-pane fade">
		<form id="formRemark" action="#" method="post" class="form form-inline" validate >
    	<input type="hidden" name="action" value="evalSubmitRemark" />
			<input type="hidden" name="termPersonId" value="<?=$termPersonId;?>" />
		<div class="col-md-12">
			<div class="col-md-2" style="text-align: left;">
		ความคิดเห็น : 	
			</div>
			<div class="col-md-3">
		<?php		
			$sql = "
			SELECT hd.id
			,(SELECT x.remark FROM eval_result x WHERE x.termPersonId=hd.id AND x.evaluatorPersonId=:evaluatorPersonId ) as remark
			FROM eval_term_person hd 
			WHERE hd.id=:termPersonId 
			";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':termPersonId', $termPersonId);
			$stmt->bindParam(':evaluatorPersonId', $evaluatorId);
			$stmt->execute();
			$row=$stmt->fetch();
			?>
		<textarea name="remark" class="form-control" cols="50" rows="5" ><?=$row['remark'];?></textarea>
			</div>
		</div>

		<div class="col-md-12">
			<a href="#" name="btnSubmitRemark" class="btn btn-primary pull-right"><i class="fa fa-save"></i> บันทึก ความคิดเห็น</a>	
		</div>
    </div>	
    <!--/.tab-pane-->

  </div>
    <!--/.tab-content-->

</div>
<!--/.col-md-12-->

	
			
           
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

	$('a[name=btnSubmit1]').click(function(){
		if ($('#form1').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form1").serialize(),
				dataType: 'json'
			}).done(function (data) { 		
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.success,
						position:'top-center'
					});
					//next tab pills
					$('.nav-pills a[href="#menu2"]').tab('show');
					//location.reload();
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
		e.preventDefault();
	});
	//end btnSubmit1
	
	$('a[name=btnSubmit2]').click(function(){
		if ($('#form2').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form2").serialize(),
				dataType: 'json'
			}).done(function (data) { 				
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.success,
						position:'top-center'
					});
					//next tab pills
					$('.nav-pills a[href="#menu3"]').tab('show');
					//location.reload();
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
		e.preventDefault();
	});
	//end btnSubmit2

	$('a[name=btnSubmit3]').click(function(){
		if ($('#form3').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form3").serialize(),
				dataType: 'json'
			}).done(function (data) { 				
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.success,
						position:'top-center'
					});
					//next tab pills
					$('.nav-pills a[href="#menu4"]').tab('show');
					//location.reload();
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
		e.preventDefault();
	});
	//end btnSubmit3

	$('a[name=btnSubmit4]').click(function(){
		if ($('#form4').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form4").serialize(),
				dataType: 'json'
			}).done(function (data) { 				
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.success,
						position:'top-center'
					});
					//next tab pills
					$('.nav-pills a[href="#menu5"]').tab('show');
					//location.reload();
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
		e.preventDefault();
	});
	//end btnSubmit4

	$('a[name=btnSubmitRemark]').click(function(){
		if ($('#formRemark').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#formRemark").serialize(),
				dataType: 'json'
			}).done(function (data) { 				
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message,
						type: data.success,
						position:'top-center'
					});
					location.href = "<?=$rootPage;?>_view.php?personId=<?=$personId;?>";
					//location.reload();
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
		e.preventDefault();
	});
	//end btnSubmitRemark
});
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
