<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php 	
	//$year = date('Y');
	//$month = "0";//date('m');
	//if(isset($_GET['year'])) $year = $_GET['year'];
	//if(isset($_GET['month'])) $month = $_GET['month'];
?>
<?php include 'head.php'; ?>


</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">

<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>
   <?php
	$rootPage = 'config';
	$tb="";
	$resetDb=1;
	if(isset($_GET['resetDb'])){
		if($_GET['resetDb']==1){
			try{
				$queueTime=$_GET['queueTime'];
				$queueTime=date('Y-m-d H:i', strtotime($queueTime));

				$pdo->beginTransaction();
				$arr = array("TRUNCATE TABLE jit_data"
				, "UPDATE `jit_time` SET `QueueTime`='".$queueTime."', `QtyTotal`=0, `InBit`=0, `OutBit`=0 "		
				);
				foreach ($arr as $value) {
					$stmt = $pdo->prepare($value);
					echo $stmt->execute();	
				}	
				$pdo->commit();
			}catch(Exception $e){
				$pdo->rollBack();
				echo $e;
			}
		$resetDb=0;
		}//is resetDb=1
	}//isset resetDb
	
	$reInvite=0;
	if(isset($_GET['reInvite'])){
		$sql = "UPDATE `".$tb."` SET isInvite=0 WHERE group2Name LIKE '%เสียชีวิต%' ";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		
		$sql = "UPDATE `".$tb."` SET isInvite=1 WHERE group2Name NOT LIKE '%เสียชีวิต%' ";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
		$reInvite=0;
	}//isset reInvite
	
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="glyphicon glyphicon-setting"></i>
       Check in Config
        <small>Check in Config management</small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="glyphicon glyphicon-list"></i>Check in Config List</a></li>
		<li><a href="#"><i class="glyphicon glyphicon-edit"></i>Check in Config</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
	<div class="box box-primary">
        <div class="box-header with-border">
        <h3 class="box-title">Check in Config</h3>
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">            
            <div class="row">                
					<div class="col-md-6">
						<h3>Reset Database for Setup</h3>		
						<form id="form1"  onsubmit="return confirm('Do you really want to submit the form?');" >
						<input type="hidden" name="resetDb" value="<?=$resetDb;?>" />

						<div class="form-group col-md-6">
                            <label for="queueTime">Queue Time : Y-m-d H:i</label>
                            <input id="queueTime" type="text" class="form-control" name="queueTime" data-smk-msg="Require Queue Time" placeholder="2018-09-22 13:30" required>
                        </div>

                        <div class="form-group col-md-6">
                       	 <button id="btn_reset_check_in" type="submit" class="btn btn-primary">Reset Database</button>
                        </div>

						</form>
					</div>
					<div class="col-md-6">
						<h3>Update Next Queue Time : </h3>		
						<form id="form1"  onsubmit="return confirm('Do you really want to submit the form?');" >						
						<input type="hidden" name="resetDb" value="<?=$resetDb;?>" />

						<div class="form-group col-md-6">
                            <label for="queueTime">Queue Time : Y-m-d H:i</label>
                            <input id="queueTime" type="text" class="form-control" name="queueTime" data-smk-msg="Require Queue Time" placeholder="2018-09-22 13:30" required>
                        </div>

                        <div class="form-group col-md-6">
                       	 <button id="btn_reset_check_in" type="submit" class="btn btn-primary">Reset Database</button>
                        </div>

						</form>
					</div>
					<!--/.col-md-->
                </div>
                <!--/.row-->       
            </div>
			<!--.body-->    
    </div>
	<!-- /.box box-primary -->
	

	</section>
	<!--sec.content-->
	
	</div>
	<!--content-wrapper-->

</div>
<!--warpper-->

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
$(document).ready(function() {
	
});
//doc ready
</script>





</body>
</html>
