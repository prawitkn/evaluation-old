<?php
  include ("session.php");
	//Check user roll.
	switch($s_userGroupCode){
		case 1 :  
			break;
		default : 
			header('Location: access_denied.php');
			exit();
	}  
  include 'head.php'; 
?>

<?php 
	$rootPage = 'person';
	$tb = 'eval_person';
?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini ">    

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php    
	$id=( isset( $_GET['id'] ) ? $_GET['id'] : '' );
   	//query 
	$sql = "SELECT `id`, `code`, `fullName`, `sectionId`, `seqNo`, `startDate`, `positionId`, `deptName`, `PositionName`, `statusId` FROM ".$tb." WHERE id=:id ";
    $stmt = $pdo->prepare($sql);	
    $stmt->bindParam(':id', $id);
	$stmt->execute();	//echo $sql;
	$row = $stmt->fetch();
	$sectionId=$row['sectionId'];
	$positionId=$row['positionId'];
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <section class="content-header">
		<h1><i class="fa fa-th-list"></i>
       พนักงาน
        <small>การจัดการข้อมูลหลัก</small>
      </h1>


      <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-home"></i>หน้าแรก</a></li>
       <li><a href="<?=$rootPage;?>_list.php"><i class="fa fa-list"></i>รายการ พนักงาน</a></li>
      </ol>
    </section>
   
     <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
        <?php if ( $id=="" ) { ?>
        	<h3 class="box-title">เพิ่ม พนักงาน</h3>
    	<?php }else{ ?>
    		 <h3 class="box-title">แก้ไข พนักงาน <span style="color: blue;"><?php echo $id.' : '.$row['fullName']; ?></span></h3>
    	<?php } //.if id==0 ?>


        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">            
            <div class="row col-md-12">                
                <form id="form1" action="#" method="post" class="form" validate >				
    				<input type="hidden" name="action" value="save" />	
    				<input type="hidden" name="id" value="<?=$id;?>" />

				
				<div class="row">					
					<div class="col-md-3">
						<div class="form-group">
	                        <label for="sectionId">แผนก</label>
							<select id="sectionId" name="sectionId" class="form-control"  data-smk-msg="จำเป็น" required>
								<?php
								$sql = "SELECT `id`, `code`, `name`, `statusId`  FROM `eval_section` WHERE StatusId=1 ";		
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($itm = $stmt->fetch()){
									$selected=( $sectionId==$itm['id'] ? ' selected ' : '' );
									echo '<option value="'.$itm['id'].'" '.$selected.'
										 >'.$itm['name'].'</option>';
								}
								?>
							</select>
	                    </div>		                    
					</div>
					<!--/.col-md-->

					<div class="col-md-3">
						<div class="form-group">
	                        <label for="positionId">ตำแหน่ง</label>
							<select id="positionId" name="positionId" class="form-control" >
								<option value="">--ทั้งหมด--</option>
								<?php
								$sql = "SELECT `id`, `code`, `name`, `statusId`  FROM `eval_position` WHERE StatusId=1 ";		
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($itm = $stmt->fetch()){
									$selected=( $positionId==$itm['id'] ? ' selected ' : '' );
									echo '<option value="'.$itm['id'].'" '.$selected.'
										 >'.$itm['name'].'</option>';
								}
								?>
							</select>
	                    </div>		                    
					</div>
					<!--/.col-md-->

					<div class="col-md-2" <?php echo ( $id=="" ? ' style="display: none;" ' : '' );?> >
						<div class="form-group">
	                        <label for="statusId">สถานะ</label><br/>
							<input type="radio" name="statusId" value="1" <?php echo ($row['statusId']==1 ?' checked ':'');?> > เปิดใช้งาน
							<input type="radio" name="statusId" value="0" <?php echo ($row['statusId']==0 ?' checked ':'');?> > ปิดใช้งาน
	                    </div>
	                   <!--form-group-->	                    
					</div>
					<!--/.col-md-->

				</div>
				<!--row-->

				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
	                        <label for="code">รหัสพนักงาน</label>

							<input type="text" name="code" class="form-control"  data-smk-msg="จำเป็น" required value="<?=$row['code'];?>" /> 
	                    </div>	
	                    
					</div>
					<!--/.col-md-->

					<div class="col-md-5">
						<div class="form-group">
	                        <label for="fullName">ชื่อ นามสกุล</label>

							<input type="text" name="fullName" class="form-control"  data-smk-msg="จำเป็น" required value="<?=$row['fullName'];?>" /> 
	                    </div>	
	                    
					</div>
					<!--/.col-md-->
					
				</div>
				<!--/.row-->	
				

				<div class="row col-md-12">
					<button id="btnSubmit" type="submit" class="btn btn-default"><i class="fa fa-save"> บันทึก</i></button>
				</div>
				<!--/.row-->

				
				
                </form>
            </div>
            <!--/.row-->       
            </div>
			<!--.body-->    
  <div class="box-footer">
  
    <!--The footer of the box -->
  </div><!-- box-footer -->
</div><!-- /.box -->

<div id="spin"></div>

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

<!-- Add Spinner feature -->
<script src="bootstrap/js/spin.min.js"></script>

<script> 
  // to start and stop spiner.  
$( document ).ajaxStart(function() {
	$("#spin").show();
}).ajaxStop(function() {
	$("#spin").hide();
});
//   

$(document).ready(function() {
	$("#name").focus();

	var spinner = new Spinner().spin();
	$("#spin").append(spinner.el);
	$("#spin").hide();
//           
	$('#form1').on("submit", function(e) {
		if ($('#form1').smkValidate()) {
			$.ajax({
			url: '<?=$rootPage;?>_ajax.php',
			type: 'POST',
			data: new FormData( this ),
			processData: false,
			contentType: false,
			dataType: 'json'
			}).done(function (data) {
				if (data.status==='success'){  
					$.smkAlert({
						text: data.message,
						type: data.status,
						position:'top-center'
					});
					setTimeout(function(){history.back();}, 1000);
				}else{
					$.smkAlert({
						text: data.message,
						type: data.status,
						position:'top-center'
					});
				}
			})
			.error(function (response) {
				  alert(response.responseText);
			});  
			//.ajax		
			e.preventDefault();
		}   
		//end if 
		e.preventDefault();
	});
	//form.submit
});
//doc ready
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
