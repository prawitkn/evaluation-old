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
$rootPage = 'User';

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
       User
        <small>User management</small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="fa fa-list"></i>User List</a></li>
		<li><a href="#"><i class="fa fa-edit"></i>Add new user</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
		<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-edit"></i> Add New User</label>

		
        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">  <form id="form1" method="post" class="form" enctype="multipart/form-data" validate>
			<form id="form1" action="<?=$rootPage;?>Ajax.php" method="post" class="form" validate >
				<div class="row"> 
					<input type="hidden" name="action" value="add" />
					
					<input type="hidden" name="action" value="add" />
					<div class="row col-md-6">					
                        <div class="form-group col-md-6">
                            <label for="userName">Username</label>
                            <input id="userName" type="text" class="form-control" name="userName" data-smk-msg="Require userName" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="userPassword">User Password</label>
                            <input id="userPassword" type="text" class="form-control" name="userPassword" data-smk-msg= "Require userPassword" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="userFullname">User Fullname</label>
                            <input id="userFullname" type="text" class="form-control" name="userFullname" data-smk-msg="Require userFullname."required>
                        </div>                     
                        
                        <div class="form-group col-md-6">
                            <label for="userEmail">User Email</label>
                            <input id="userEmail" type="email" class="form-control" name="userEmail" data-smk-msg="Require userEmail" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="userTel">User Telephone</label>
                            <input id="userTel" type="text" class="form-control" name="userTel" data-smk-msg="Require Telephone number" required>                        
						</div>
						
						
                        <div class="form-group col-md-6">
                            <label for="userPin">User PIN</label>
                            <input id="userPin" type="text" class="form-control" name="userPin" data-smk-msg= "Require userPin" required>
                        </div>
					</div>
					<!--/.col-md-->
					<div class="row col-md-6">
						<div class="form-group col-md-6">
							<label for="userGroupCode">User Group</label>
							<select id="userGroupCode" name="userGroupCode" class="form-control"  data-smk-msg="Require User Group" required>
								<?php
								$sql = "SELECT `Id`, `Code`, `Name`, `StatusId`  FROM `".$dtPrefix."user_group` WHERE StatusId=1 ";							
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($row = $stmt->fetch()){
									echo '<option value="'.$row['Id'].'" 
										 >'.$row['Id'].' : ['.$row['Name'].']</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="userDeptCode">User Department</label>
							<select id="userDeptCode" name="userDeptCode" class="form-control"  data-smk-msg="Require User Department" required>
								<option value="0"> -- All -- </option>
							</select>
						</div>							
						<div class="form-group col-md-6">
							<label for="inputFile">User Image</label>
							<input type="hidden" name="curPhoto" id="curPhoto" value="<?=$row['photo'];?>" />
							<input type="file" name="inputFile" accept="image/*" multiple  onchange="showMyImage(this)" /> <br/>
							<img id="thumbnil" style="width:50%; margin-top:10px;"  src="dist/img/<?php echo (empty($row['photo'])? 'default.jpg' : $row['photo']); ?>" alt="" />
						</div>
					</div>
					<!--/.col-md-->
				</div>
				<!--/.row-->   
				
				<div class="row">
					<div class="col-md-6">    
					<button id="btnSubmit" type="submit" class="btn btn-defalut">Submit</button>
					</div>
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
			url: '<?=$rootPage;?>Ajax.php',
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

<script>
function showMyImage(fileInput) {
	var files = fileInput.files;
	for (var i = 0; i < files.length; i++) {           
		var file = files[i];
		var imageType = /image.*/;     
		if (!file.type.match(imageType)) {
			continue;
		}           
		var img=document.getElementById("thumbnil");            
		img.file = file;    
		var reader = new FileReader();
		reader.onload = (function(aImg) { 
			return function(e) { 
				aImg.src = e.target.result; 
			}; 
		})(img);
		reader.readAsDataURL(file);
	}    
}
</script>

</body>
</html>
