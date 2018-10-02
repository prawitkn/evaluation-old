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

	$rootPage = 'user';
	$tb = 'eval_user';

?>	
</head>
<body class="hold-transition skin-yellow sidebar-mini">   

<?php
$Id=$_GET['Id'];

$sql = "SELECT hdr.`userId`, hdr.`userName`, hdr.`userPassword`, hdr.`userPin`, hdr.`userFullname`
, hdr.`userGroupCode`, hdr.`userDeptCode`, hdr.`userEmail`, hdr.`userTel`
, hdr.`userPicture`, hdr.`statusId`
, hdr.`CreateTime`, hdr.`CreateUserId`, hdr.`UpdateTime`, hdr.`UpdateUserId`

, uc.userFullname as CreateUserName 
, uu.userFullname as UpdateUserName 
FROM `eval_user` hdr 
LEFT JOIN `eval_user` uc on uc.userId=hdr.CreateUserId 
LEFT JOIN `eval_user` uu on uu.userId=hdr.UpdateUserId 
WHERE hdr.userId=:Id 
LIMIT 1  
";		
//$result = mysqli_query($link, $sql);
$stmt = $pdo->prepare($sql);	
$stmt->bindParam(':Id', $Id);	
$stmt->execute();	
$row=$stmt->fetch();	
?>
	
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
        <li><a href="<?=$rootPage;?>_list.php"><i class="fa fa-list"></i>User Group List</a></li>
		<li><a href="#"><i class="fa fa-edit"></i>Edit User Group</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
    <div class="box box-primary">
        <div class="box-header with-border">
       	<label class="box-tittle" style="font-size: 20px;"><i class="fa fa-edit"></i> Edit User group</label>

        <div class="box-tools pull-right">
          <!-- Buttons, labels, and many other things can be placed here! -->
          <!-- Here is a label for example -->
         
        </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">                
	        <form id="form1" method="post" class="form" validate>
				<div class="row"> 
					<input type="hidden" name="action" value="edit" />
					<input id="Id" type="hidden" name="Id" value="<?=$row['Id'];?>" />	
					
					<input type="hidden" name="action" value="edit" />
					<div class="row col-md-6">					
						<div class="form-group col-md-6">
							<label for="userName">Username</label>
							<input id="userName" type="text" class="form-control" name="userName" value="<?=$row['userName'];?>"  data-smk-msg="Require userName" required>
						</div>
						<div class="form-group col-md-6">
							<label for="userPassword">User Password</label>
							<input id="userPassword" type="text" class="form-control" name="userPassword" value="" >
						</div>

						<div class="form-group col-md-12">
							<label for="userFullname">User Fullname</label>
							<input id="userFullname" type="text" class="form-control" name="userFullname" value="<?=$row['userFullname'];?>"  data-smk-msg="Require userFullname."required>
						</div>                     
						
						<div class="form-group col-md-6">
							<label for="userEmail">User Email</label>
							<input id="userEmail" type="email" class="form-control" name="userEmail" value="<?=$row['userEmail'];?>" >
						</div>
						<div class="form-group col-md-6">
							<label for="userTel">User Telephone</label>
							<input id="userTel" type="text" class="form-control" name="userTel" value="<?=$row['userTel'];?>" >                        
						</div>
						
						
						<div class="form-group col-md-6">
							<label for="userPin">User PIN</label>
							<input id="userPin" type="text" class="form-control" name="userPin"  value="" >
						</div>
					</div>
					<!--/.col-md-->
					<div class="row col-md-6">
						<div class="form-group col-md-4">
							<label for="userGroupId">User Group</label>
							<select id="userGroupId" name="userGroupId" class="form-control"  data-smk-msg="Require User Group" required>
								<?php
								$sql = "SELECT `Id`, `Code`, `Name`, `StatusId`  FROM `eval_user_group` WHERE StatusId=1 ";							
								$stmt = $pdo->prepare($sql);		
								$stmt->execute();
								while($row = $stmt->fetch()){									
									$selected = ($rOption['Id']==$row['userGroupId']?' selected ':'');	
									echo '<option value="'.$row['Id'].'" 
										 >'.$row['Id'].' : ['.$row['Name'].']</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group col-md-4">
							<label for="userDeptId">User Department</label>
							<select id="userDeptId" name="userDeptId" class="form-control" >
								<option value="0"> -- All -- </option>
							</select>
						</div>
						<div class="form-group col-md-4">
		                    <label for="StatusId">Status</label><br/>
							<input type="radio" name="StatusId" value="1" <?php echo ($row['StatusId']==1?' checked ':'');?> > Active
							<input type="radio" name="StatusId" value="2" <?php echo ($row['StatusId']==2?' checked ':'');?> > Inactive
						</div>
						<!--/.form-group-->					
						<div class="form-group col-md-6">
							<label for="inputFile">User Image</label>
							<input type="hidden" name="curPhoto" id="curPhoto" value="<?=$row['userPicture'];?>" />
							<input type="file" name="inputFile" accept="image/*" multiple  onchange="showMyImage(this)" /> <br/>
							<img id="thumbnil" style="width:50%; margin-top:10px;"  src="dist/img/<?php echo (empty($row['userPicture'])? 'default.jpg' : $row['userPicture']); ?>" alt="" />
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
	$("#title").focus();

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
