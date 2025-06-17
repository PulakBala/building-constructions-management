<?php
if (isset($_SESSION['sa_crm_drs_id']) and $_SESSION['sa_crm_drs_id'] == true) {
  // User is logged in
} else {
  error_log("Redirecting to login: sa_crm_drs_id not set or false");
  header("Location: index.php");
  die();
}
?>
<div class="sidebar left " style="overflow-y: auto;">

   <div class="user-panel">
    <div class="pull-left image">
      <img src="img/logo.jpg" class="mt-1 rounded-cercle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>Sobarmart</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <ul class="list-sidebar bg-defoult">
    <li> <a href="dashboard.php"> <i class="fa fa-dashboard"></i> <span class="nav-label"> DASHBOARD </span></a></li>

    <li><a href="allflatdata.php"><i class="fas fa-users"></i> ALL MANAGER</a></li>
    <li><a href="monthly-coll.php"><i class="fas fa-file-invoice"></i> BILL REPORTS</a></li>
    <li><a href="previeus_month_du.php"><i class="fas fa-calendar-alt"></i> PREVIOUS MONTH DASHBOARD</a></li>
     <li><a href="previous_bill_reports.php"><i class="fas fa-calendar-alt"></i> PREVIOUS REPORTS</a></li>



    <li> <a href="display_new_building.php"><i class="fa fa-home"></i> <span class="nav-label">BUILDING DETAILS</span></a> </li>


    <li> <a href="personal_expense.php"><i class="fa fa-home"></i> <span class="nav-label">PERSONAL EXPENSE</span></a> </li>
    <li> <a href="add-expense.php"><i class="fa fa-home"></i> <span class="nav-label">MANAGER EXPENSE</span></a> </li>
    <li><a href="others_expense_display.php"><i class="fa fa-eye"></i> OTHER EXPENSE</a></li>
    <li><a href="bank_list.php"><i class="fa fa-list-alt"></i> Bank List</a></li>
    <li><a href="bank_form.php"><i class="fa fa-university"></i> Bank</a></li>
    <li> <a href="display_main_assets.php"><i class="fa fa-home"></i> <span class="nav-label">ASSETS</span></a> </li>

    <li><a href="create.php"><i class="fas fa-user-plus"></i> ADD MANAGER</a></li>
    <li> <a href="add_new_building.php"><i class="fa fa-external-link"></i> <span class="nav-label">ADD NEW BUILDING</span></a> </li>
    <li> <a href="create_main_assets.php"><i class="fa fa-home"></i> <span class="nav-label">ADD NEW ASSETS</span></a> </li>
    <li><a href="add_others_expense.php"><i class="fa fa-plus"></i> ADD OTHERS EXPENSE</a></li>
    
    <li><a href="contact.php"><i class="fa fa-plus"></i> Contact</a></li>
    <li><a href="contact_info.php"><i class="fa fa-plus"></i> Contact Info</a></li>

    <li> <a href="#" data-toggle="collapse" data-target="#construction-tables" class="collapsed active"><i class="fa fa-briefcase"></i>
        <span class="nav-label">CONSTRUCTION</span><span class="fa fa-chevron-left pull-right"></span></a>
      <ul class="sub-menu collapse" id="construction-tables">
         <li><a href="add_project.php">ADD PROJECT</a></li>
      <li><a href="project_display.php">PROJECT DISPLAY</a></li>
     
       

      </ul>
    </li>



    <!--<li> <a href="flat-details.php"><i class="fa fa-pie-chart"></i> <span class="nav-label">FLAT DETAILS</span> </a></li>-->
    <!--<li> <a href="#" data-toggle="collapse" data-target="#accounts-tables" class="collapsed active"><i class="fa fa-briefcase"></i>-->
    <!--    <span class="nav-label">ACCOUNTS</span><span class="fa fa-chevron-left pull-right"></span></a>-->
    <!--  <ul class="sub-menu collapse" id="accounts-tables">-->
    <!--    <li><a href="accounts.php">ACCOUNTS</a></li>-->
        <!--<li><a href="collection.php">COLLECTIONS</a></li>-->
        <!-- <li><a href="add-expense.php">ADD EXPENSE</a></li> -->
    <!--    <li><a href="monthly-coll.php">BILL REPORTS</a></li>-->
    <!--    <li><a href="previeus_month_du.php">PREVIOUS MONTH DUE</a></li>-->

    <!--  </ul>-->
    <!--</li>-->





    <li> <a href="logout.php"><i class="fa fa-sign-out"></i> <span class="nav-label">LOGOUT</span></a> </li>



  </ul>
</div>