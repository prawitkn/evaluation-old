<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<?php 
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
	$rootPage = 'index';

  $personId=$_GET['personId'];
   ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-home"></i>
       หน้าแรก
        <small></small>
      </h1>
	  <ol class="breadcrumb">
        <li><a href="<?=$rootPage;?>.php"><i class="glyphicon glyphicon-list"></i>หน้าแรก</a></li>
		<!--<li><a href="#"><i class="glyphicon glyphicon-edit"></i>View</a></li>-->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img width="128" height="128" src="dist/img/<?php echo (empty($s_userPicture)? 'default-50x50.gif' : $s_userPicture) ?> " class="user-image" alt="<?= $s_userFullname ?>">

              <h3 class="profile-username text-center"><?=$s_username;?></h3>

              <p class="text-muted text-center">Software Engineer</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>ผู้ประเมิน</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>ผู้รับการประเมิน</b> <a class="pull-right">543</a>
                </li>
              </ul>

              <a href="evaluate.php?pId=<?=$s_personId;?>" class="btn btn-primary btn-block"><b>ประเมินตนเอง</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#evaluator" data-toggle="tab">ผู้ประเมิน</a></li>
              <li><a href="#evaluatetion" data-toggle="tab">ผู้รับการประเมิน</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="evaluator">
                
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="evaluatetion">

                <table class="table table-hover">
                <thead>
                  <tr>
                    <td>No.</td><td>Fullname</td><td>Department</td><td>Position Name</td><td>#</td>
                  </tr>
                </thead>
                <?php
                $EvaluatorPersonId=$_GET['EvaluatorPersonId'];
                $sql = "SELECT hdr.Id
                ,ps.Fullname, ps.PositionName, ps.DeptName
                FROM eval_term_person hdr 
                INNER JOIN eval_person ps ON ps.Id=hdr.PersonId
                WHERE 1=1
                AND ( hdr.EvaluatorPersonId=:EvaluatorPersonId OR 
                    hdr.EvaluatorPersonId2=:EvaluatorPersonId2 OR
                    hdr.EvaluatorPersonId3=:EvaluatorPersonId3 
              ) ";
                $stmt = $pdo->prepare($sql);    
                $stmt->bindParam(':EvaluatorPersonId', $s_personId); 
                $stmt->bindParam(':EvaluatorPersonId2', $s_personId);                 
                $stmt->bindParam(':EvaluatorPersonId3', $s_personId); 
                $stmt->execute(); 
                $rowNo=1; while ( $row = $stmt->fetch() ){
                  echo '<tr>
                  <td>'.$rowNo.'</td>
                  <td>'.$row['Fullname'].'</td>
                  <td>'.$row['DeptName'].'</td>
                  <td>'.$row['PositionName'].'</td>
                  <td>
                    <a href="evaluate.php?tpId='.$row['Id'].'"  
                        class="btn btn-primary"><i class="fa fa-edit"></i> ประเมิน</a>
                    <a href="evaluate_view.php?tpId='.$row['Id'].'"  
                        class="btn btn-primary"><i class="fa fa-search"></i> สรุป</a>'.
                  '</td>
                  </tr>';
                  $rowNo+=1;
                }
                ?>
              </div>
              <!-- /.tab-pane -->

              
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

	</section>
	<!--sec.content-->
	
	</div>
	<!--content-wrapper-->
	
	<!-- Main Footer -->
  <?php include'footer.php'; ?>
  
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
	$('#barcode').select();
	
	//setTimeout(function(){ getList(); }, 5000);
	
	//setInterval( getList(), 5000);
	/**
	 * Number.prototype.format(n, x)
	 * 
	 * @param integer n: length of decimal
	 * @param integer x: length of sections
	 */
	Number.prototype.format = function(n, x) {
	    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
	    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
	};
	
	function getList(){		
		var params = {
			action: 'getData'
		}; //alert(params.sendDate);
		/* Send the data using post and put the results in a div */
		  $.ajax({
			  url: "IndexAjax.php",
			  type: "post",
			  data: params,
			datatype: 'json',
			  success: function(data){	//alert(data);
					//data=$.parseJSON(data);
					var sumInviteTotal=0;
					var sumCountTotal=0;
					var sumPendingTotal=0;
					//alert(data);
					//alert(data.rowCount);
					switch(data.rowCount){
						case 0 : alert('Data not found.');
							//$('#tbl_items tbody').empty();
							return false; break;
						default : 							
							//$('#tbl_items tbody').empty();
							$('#tblData tbody').fadeOut('slow').empty();
							$rowNo=1;
							$.each($.parseJSON(data.data), function(key,value){
								$('#tblData').append(
									'<tr>'+
									'<td style="text-align: center;">'+$rowNo+'</td>'+
									'<td style="text-align: left;">'+value.GroupName+'</td>'+
									'<td style="text-align: center;">'+value.Qty+'</td>'+
									'<td style="text-align: center; font-weight: bold; font-size: 32px;"><span style="color: red;">'+value.Id+'</span> / <small>'+value.QueueTime+'</small></td></tr>');
								$rowNo+=1;
							});
							$('#tblData tbody').fadeIn('slow');

							$('#tblSummary tbody').fadeOut('slow');
							itm=$.parseJSON(data.data2);							
							qtyRemain=itm.totalQtyMax-itm.totalRegister;
							qtyCheckInRemain=itm.totalRegister-itm.totalCheckIn;
							$('#lblTotal').text(itm.totalQtyMax);
							$('#lblUnRegister').text(qtyRemain);
							$('#lblRegister').text(itm.totalRegister);
							$('#lblCheckIn').text(itm.totalCheckIn);
							$('#lblUnCheckIn').text(qtyCheckInRemain);							
							$('#tblSummary tbody').fadeIn('slow');

					}//.switch
			  }   
			}).error(function (response) {
				alert(response.responseText);
			}); 
	}
	
	getList();

    var counter = 0;
	var i = setInterval(function(){
	    // do your thing
	    getList();

	    counter++;
	    if(counter === 10) {
	        clearInterval(i);
	    }
	}, 20000);	//20Minute

});
//doc ready
</script>





</body>
</html>
