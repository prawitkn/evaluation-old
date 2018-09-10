<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu ">

    <?php switch($s_userGroupCode){  case 1 : case 3 : ?>
    <li class="header">เมนู</li>  
    <li><a href="evaluator.php"><i class="fa fa-chain"></i> <span>กำหนดการประเมิน</span></a></li>
    <li><a href="evaluate_summary.php"><i class="fa fa-pie-chart"></i> <span>สรุปผลการประเมิน</span></a></li>
    <?php break; ?>
    <?php default : } //.switch ?>

    
    <?php switch($s_userGroupCode){  case 1 : ?>
		<li class="header">เจ้าหน้าที่ดูแลระบบ</li>		
		<li><a href="User.php?"><i class="fa fa-user"></i> <span>User</span></a></li>	
		<li><a href="userGroup.php?"><i class="fa fa-users"></i> <span>User Group</span></a></li>	
    <li><a href="Config.php?"><i class="fa fa-cog"></i> <span>Config</span></a></li> 
    <?php break; ?>
    <?php default : } //.switch ?>
		
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>