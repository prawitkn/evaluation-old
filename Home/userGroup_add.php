<?php
  //  include '../db/database.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php include 'head.php'; 
$rootPage = 'userGroup';

//Check user roll.
switch($s_userGroupCode){
	case 1 : 
		break;
	default : 
		header('Location: access_denied.php');
		exit();
}
?>

</head>
<body class="hold-transition skin-yellow sidebar-mini sidebar-collapse">
	
<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-users"></i>
       User Group
        <small>User group management</small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="fa fa-list"></i>User Group List</a></li>
		<li><a href="#"><i class="fa fa-edit"></i>Add new user group</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
		<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-edit"></i> Add New User group</label>

		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body"> 
			<form id="form1" action="<?=$rootPage;?>_ajax.php" method="post" class="form" validate >
				<div class="row"> 
					<input type="hidden" name="action" value="add" />
					
					<div class="col-md-3">					
						<div class="form-group">
							<label for="code">User Group Code</label>
							<input id="code" type="text" class="form-control" name="code" data-smk-msg="Require user group code."required>
						</div>
					</div>
					<!--/.col-md-->
					<div class="col-md-3">                        
						<div class="form-group">
							<label for="name">User Group Name</label>
							<input id="name" type="text" class="form-control" name="name" data-smk-msg="Require uer group name" required>
						</div>
					</div>
					<!--/.col-md-->
				</div>
				<!--/.row-->   
				
				<div class="row col-md-12">                
					<button id="btnSubmit" type="submit" class="btn btn-defalut">Submit</button>
				</div>
				<!--/.row--> 
			</form>	
			<!--form1-->
				
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
	$("#code").focus();

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
				if (data.status === "success"){  
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
