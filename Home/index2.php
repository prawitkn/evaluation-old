<?php
  include ("session.php");
 
  include 'head.php'; 
?>

</head>
<body class="hold-transition skin-yellow sidebar-mini">    
  
<div class="wrapper">

  <!-- Main Header -->
  <?php include 'header.php'; ?>
  
  <!-- Left side column. contains the logo and sidebar -->
   <?php include 'leftside.php'; ?>

   <?php
	$rootPage = 'index';

  //term sql
  $termId=( isset($_GET['termId']) ? $_GET['termId'] : '' );

  $sql = "SELECT hdr.id 
  FROM eval_term hdr 
  WHERE 1=1 ";
  if( $termId<> "" ){ $sql .= "AND hdr.id=:id "; }
  $sql .= "ORDER BY hdr.isCurrent DESC, hdr.id DESC ";
  $sql .= "LIMIT 1 ";

  $stmt = $pdo->prepare($sql);  
  if( $termId<> "" ){ $stmt->bindParam(':id', $termId); }      
  
  $stmt->execute(); 
  $termId=$stmt->fetch()['id'];

  //personId 
  $personId=( isset($_GET['personId']) ? $_GET['personId'] : $s_personId );

  //get eval data
  $sql = "SELECT tp.id as termPersonId, CONCAT(t.term,'/',t.year) as termName, p.fullName as personFullName, p.positionId
  , pos.name as positionName, pos.positionRankId, pos.sectionId 
  , sec.name as sectionName 
  FROM eval_term_person tp
  INNER JOIN eval_term t ON t.id=tp.termId 
  INNER JOIN eval_person p ON p.id=tp.personId 
  LEFT JOIN eval_position pos ON pos.id=p.positionId 
  LEFT JOIN eval_section sec ON sec.id=pos.sectionId
  WHERE 1=1
  AND tp.termId=:termId 
  AND tp.personId=:personId ";

  $stmt = $pdo->prepare($sql);        
  $stmt->bindParam(':termId', $termId);
  $stmt->bindParam(':personId', $personId);
  $stmt->execute(); 
  $row=$stmt->fetch();

  $termPersonId=$row['termPersonId'];

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

              <h3 class="profile-username text-center"><?=$row['personFullName'];?></h3>

              <p class="text-muted text-center"><?=$row['positionName'];?></p>

              <!--<ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>ผู้ประเมิน</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>ผู้รับการประเมิน</b> <a class="pull-right">543</a>
                </li>
              </ul>-->

              <a href="evaluate.php?personId=<?=$s_personId;?>" class="btn btn-primary btn-block"><b>ประเมินตนเอง</b></a>
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
                   <table class="table table-hover">
                <thead>
                  <tr>
                    <td>ลำดับ</td>
                    <td>ชื่อ นามสกุล</td>
                    <td>ตำแหน่ง</td>
                  </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT hdr.id, ps.fullName as fullName, pos.name as positionName 
                FROM eval_term_person hdr 
                INNER JOIN eval_person ps ON ps.id=hdr.evaluatorPersonId
                LEFT JOIN eval_position pos ON pos.id=ps.positionId 
                WHERE 1=1
                AND hdr.id=:id 
                UNION
                SELECT hdr.id, ps.fullName as fullName, pos.name as positionName 
                FROM eval_term_person hdr 
                INNER JOIN eval_person ps ON ps.id=hdr.evaluatorPersonId2
                LEFT JOIN eval_position pos ON pos.id=ps.positionId 
                WHERE 1=1
                AND hdr.id=:id2 
                UNION
                SELECT hdr.id, ps.fullName as fullName, pos.name as positionName 
                FROM eval_term_person hdr 
                INNER JOIN eval_person ps ON ps.id=hdr.evaluatorPersonId3
                LEFT JOIN eval_position pos ON pos.id=ps.positionId 
                WHERE 1=1
                AND hdr.id=:id3 
              ";
                $stmt = $pdo->prepare($sql);    
                $stmt->bindParam(':id', $termPersonId); 
                $stmt->bindParam(':id2', $termPersonId); 
                $stmt->bindParam(':id3', $termPersonId); 
                $stmt->execute(); 
                $rowNo=1; while ( $row = $stmt->fetch() ){
                  echo '<tr>
                  <td>'.$rowNo.'</td>
                  <td>'.$row['fullName'].'</td>
                  <td>'.$row['positionName'].'</td>
                  </tr>';
                  $rowNo+=1;
                }
                ?>
              </tbody>
            </table>

              </div>
              <!-- /.tab-pane -->


              <div class="tab-pane" id="evaluatetion">

                <table class="table table-hover">
                <thead>
                  <tr>
                    <td>ลำดับ</td>
                    <td>ชื่อ นามสกุล</td>                    
                    <td>ตำแหน่ง</td>
                    <td>#</td>
                  </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT hdr.id, ps.fullName , pos.name as positionName 
                FROM eval_term_person hdr 
                INNER JOIN eval_person ps ON ps.id=hdr.personId                
                LEFT JOIN eval_position pos ON pos.id=hdr.personId
                WHERE 1=1
                AND ( hdr.evaluatorPersonId=:evaluatorPersonId OR 
                    hdr.evaluatorPersonId2=:evaluatorPersonId2 OR
                    hdr.evaluatorPersonId3=:evaluatorPersonId3 ) 
              ";
                $stmt = $pdo->prepare($sql);    
                $stmt->bindParam(':evaluatorPersonId', $personId); 
                $stmt->bindParam(':evaluatorPersonId2', $personId);                 
                $stmt->bindParam(':evaluatorPersonId3', $personId);           
                $stmt->execute(); 
                $rowNo=1; while ( $row = $stmt->fetch() ){
                  echo '<tr>
                  <td>'.$rowNo.'</td>
                  <td>'.$row['fullName'].'</td>
                  <td>'.$row['positionName'].'</td>
                  <td>
                    <a href="evaluate.php?personId='.$row['id'].'"  
                        class="btn btn-primary"><i class="fa fa-edit"></i> ประเมิน</a>
                    <a href="evaluate_view.php?tpId='.$row['id'].'" class="btn btn-primary"><i fa fa-static></i> รายละเอียด</a>'.
                  '</td>
                  </tr>';
                  $rowNo+=1;
                }
                ?>
              </tbody>
            </table>
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
