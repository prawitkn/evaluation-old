<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu ">

    <?php switch($s_userGroupCode){ case 3 : ?>
    <li class="header">เมนู</li>  
    <li><a href="evaluator.php"><i class="fa fa-chain"></i> <span>กำหนดการประเมิน</span></a></li>
    <li><a href="evaluate_result.php"><i class="fa fa-pie-chart"></i> <span>สรุปผลการประเมิน</span></a></li>
    <?php break; ?>
    <?php default : } //.switch ?>

    <?php switch($s_userGroupCode){ case 1 : case 3 : ?>
    <li class="header">ข้อมูล</li>  

    <li><a href="person_list.php"><i class="fa fa-list"></i> <span> พนักงาน</span></a></li>
    <li><a href="term_list.php"><i class="fa fa-list"></i> <span>ห้วงเวลาการประเมิน</span></a></li>
    <li><a href="topicGroup_list.php"><i class="fa fa-list"></i> <span>กลุ่มหัวข้อการประเมิน</span></a></li>
    <li><a href="topic_list.php"><i class="fa fa-list"></i> <span>หัวข้อประเมิน</span></a></li>

    <li><a href="section_list.php"><i class="fa fa-list"></i> <span>แผนก</span></a></li>
    <li><a href="positionRank_list.php"><i class="fa fa-list"></i> <span>ระดับตำแหน่ง</span></a></li>
    <li><a href="positionGroup_list.php"><i class="fa fa-list"></i> <span>กลุ่มตำแหน่ง</span></a></li>
    <li><a href="position_list.php"><i class="fa fa-list"></i> <span>ตำแหน่ง</span></a></li>
    <li><a href="grade_list.php"><i class="fa fa-list"></i> <span>เกรด</span></a></li>

    <?php break; ?>
    <?php default : } //.switch ?>

    
    <?php switch($s_userGroupCode){  case 1 : ?>
		<li class="header">เจ้าหน้าที่ดูแลระบบ</li>		
		<li><a href="user_list.php?"><i class="fa fa-user"></i> <span>User</span></a></li>	
		<li><a href="userGroup.php?"><i class="fa fa-users"></i> <span>User Group</span></a></li>	
    <li><a href="Config.php?"><i class="fa fa-cog"></i> <span>Config</span></a></li> 
    <?php break; ?>
    <?php default : } //.switch ?>
		
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>