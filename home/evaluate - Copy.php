<?php
  include ("session.php");
	
  include 'head.php'; 
?>

<?php 

	$rootPage = 'evaluate';
	$tb = '';

	$sectionId=( isset($_GET['sectionId']) ? $_GET['sectionId'] : '' );	
   	$termPersonId=( isset($_GET['tpId']) ? $_GET['tpId'] : '' );

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
   	$arrItmId = array();

	$sql = "SELECT `Id`, `TermId`, `PersonId`, `EvaluatorPersonId`, `EvaluatorPersonId2`, `EvaluatorPersonId3` 
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
        	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-list"></i> การประเมิน</label>

			<!--<a href="<?=$rootPage;?>_add.php?id=" class="btn btn-primary"><i class="fa fa-plus"></i> Add user group</a>-->
		
		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          
        </div>
        <!-- /.box-tools -->

        </div>
        <!-- /.box-header -->

        <div class="box-body">
			<form id="form1" action="#" method="post" class="form form-inline" validate >
			
			<input type="hidden" name="action" value="evalSubmit" />
			<input type="hidden" name="TermPersonId" value="<?=$termPersonId;?>" />
			<input type="hidden" name="UserEvaluatorPersonId" value="<?=$s_personId;?>" />


	<div class="col-md-12">				
	<ul class="nav nav-pills">
		<li class="active"><a data-toggle="pill" href="#home">ข้อมูลผู้รับการประเมิน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu1">1. ปริมาณงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu2">2. คุณภาพงาน <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu3">3. ทัศนคติและพฤติกรรม <i class="fa fa-caret-right"></i></a></li>
		<li><a data-toggle="pill" href="#menu4">4. การมาทำงาน <i class="fa fa-caret-right"></i></a></li><li><a data-toggle="pill" href="#menu5">5. ความคิดเห็น </a></li>
	</ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
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
    </div>
    <!--/.tab-pane-->
	
    <div id="menu1" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>
				<th>ระดับ 1</th>	
				<th>ระดับ 2</th>
				<th>ระดับ 3</th>
				<th>ระดับ 4</th>
				<th>ระดับ 5</th>	
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`
			FROM `eval_data` t 
			WHERE t.TopicGroupId=1 
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
					 <input type="radio" name="<?=$row['Id'];?>" value="1" data-smk-msg="ต้องระบุ" required />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="2" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="3" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="4" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="5" data-smk-msg="ต้องระบุ" />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['Id'];
			$rowNo +=1; }
			?>
		</table>
    </div>
    <!--/.tab-pane-->

    <div id="menu2" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>
				<th>ระดับ 1</th>	
				<th>ระดับ 2</th>
				<th>ระดับ 3</th>
				<th>ระดับ 4</th>
				<th>ระดับ 5</th>	
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`
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
					 <input type="radio" name="<?=$row['Id'];?>" value="1" data-smk-msg="ต้องระบุ" required />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="2" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="3" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="4" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="5" data-smk-msg="ต้องระบุ" />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['Id'];
			$rowNo +=1; }
			?>
		</table>
    </div>
    <!--/.tab-pane-->

    <div id="menu3" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>
				<th>ระดับ 1</th>	
				<th>ระดับ 2</th>
				<th>ระดับ 3</th>
				<th>ระดับ 4</th>
				<th>ระดับ 5</th>	
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`
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
					 <input type="radio" name="<?=$row['Id'];?>" value="1" data-smk-msg="ต้องระบุ" required />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="2" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="3" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="4" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="5" data-smk-msg="ต้องระบุ" />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['Id'];
			$rowNo +=1; }
			?>
		</table>
    </div>
    <!--/.tab-pane-->

    <div id="menu4" class="tab-pane fade">
      <table class="table table-striped">
			<tr>
				<th>No.</th>
				<th>หัวข้อการประเมิน</th>
				<th>ระดับ 1</th>	
				<th>ระดับ 2</th>
				<th>ระดับ 3</th>
				<th>ระดับ 4</th>
				<th>ระดับ 5</th>	
			</tr>		
			<?php 
			$sql = "SELECT `Id`, `TermPersonId`, `EvalTypeId`, `EvalTypeName`, `TopicGroupId`, `TopicGroupName`, `SeqNo`, `TopicId`, `TopicName`
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
					 <input type="radio" name="<?=$row['Id'];?>" value="1" data-smk-msg="ต้องระบุ" required />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="2" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="3" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="4" data-smk-msg="ต้องระบุ" />
				</td>			
				<td>
					 <input type="radio" name="<?=$row['Id'];?>" value="5" data-smk-msg="ต้องระบุ" />
				</td>
			</tr>
			<?php 
			$arrItmId[] = $row['Id'];
			$rowNo +=1; }
			?>
		</table>
    </div>
    <!--/.tab-pane-->
	

	<div id="menu5" class="tab-pane fade">
		
		<div class="col-md-12">
			<div class="col-md-2" style="text-align: left;">
		ความคิดเห็น : 	
			</div>
			<div class="col-md-3">
				
		<textarea name="Remark" class="form-control" cols="50" rows="5" ></textarea>
			</div>
			<div class="col-md-6">
			</div>
		</div>
    </div>	
    <!--/.tab-pane-->

  </div>
    <!--/.tab-content-->

  <div class="col-md-2 pull-right">
  	<input type="hidden" name="arrItmId" value="<?=implode(",", $arrItmId);?>" />

	<a href="#" name="btnSubmit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</a>
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
		if ($('#form1').smkValidate()) {
			$.post({
				url: '<?=$rootPage;?>_ajax.php',
				data: $("#form1").serialize(),
				dataType: 'json'
			}).done(function (data) { alert(data);					
				if (data.success === "success"){ 
					$.smkAlert({
						text: data.message+' : '+data.Id,
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
			//.ajax		
			e.preventDefault();
		}else{
			alert("บางรายการต้องระบุ");
		}  
		//end if 
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
