<?php
if (isset($_SESSION['sa_crm_drs_id']) and $_SESSION['sa_crm_drs_id'] == true) {
  // User is logged in
} else {
  error_log("Redirecting to login: sa_crm_drs_id not set or false");
  header("Location: login.php");
  die();
}
?>
<div class="sidebar left " style="overflow-y: auto;">
  <div class="user-panel">
    <div class="pull-left image">
      <img src="img/sobamarthltd.jpeg" class="mt-2 pt-1 rounded-cercle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>Sobarmart</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
  <ul class="list-sidebar bg-defoult">
    <li> <a href="index.php"> <i class="fa fa-dashboard"></i> <span class="nav-label"> DASHBOARD </span></a>

    </li>
    <li> <a href="create.php"><i class="fa fa-external-link"></i> <span class="nav-label">ADD NEW MANAGER</span></a> </li>
    <li> <a href="allflatdata.php"><i class="fa fa-home"></i> <span class="nav-label">ALL MANAGER</span></a> </li>


    <li> <a href="add_new_flat.php"><i class="fa fa-external-link"></i> <span class="nav-label">ADD NEW FLAT</span></a> </li>
    <li> <a href="all_flat_information.php"><i class="fa fa-home"></i> <span class="nav-label">FLAT INFORMATION</span></a> </li>


    <li> <a href="personal_expense.php"><i class="fa fa-home"></i> <span class="nav-label">PERSONAL EXPENSE</span></a> </li>
    <li> <a href="add-expense.php"><i class="fa fa-home"></i> <span class="nav-label">MANAGER EXPENSE</span></a> </li>


    <li> <a href="add_new_assets.php"><i class="fa fa-home"></i> <span class="nav-label">ADD NEW ASSETS</span></a> </li>


    <li> <a href="#" data-toggle="collapse" data-target="#construction-tables" class="collapsed active"><i class="fa fa-briefcase"></i>
        <span class="nav-label">CONSTRUCTION</span><span class="fa fa-chevron-left pull-right"></span></a>
      <ul class="sub-menu collapse" id="construction-tables">
        <li><a href="add_payment_con.php">ADD PAYMENT</a></li>
        <!--<li><a href="collection.php">COLLECTIONS</a></li>-->
        <li><a href="add_construction.php">ADD CONSTRUCTION</a></li>
        <li><a href="add_worker_details.php">ADD WOKER DETAILS</a></li>

      </ul>
    </li>



    <!--<li> <a href="flat-details.php"><i class="fa fa-pie-chart"></i> <span class="nav-label">FLAT DETAILS</span> </a></li>-->
    <li> <a href="#" data-toggle="collapse" data-target="#accounts-tables" class="collapsed active"><i class="fa fa-briefcase"></i>
        <span class="nav-label">ACCOUNTS</span><span class="fa fa-chevron-left pull-right"></span></a>
      <ul class="sub-menu collapse" id="accounts-tables">
        <li><a href="accounts.php">ACCOUNTS</a></li>
        <!--<li><a href="collection.php">COLLECTIONS</a></li>-->
        <!-- <li><a href="add-expense.php">ADD EXPENSE</a></li> -->
        <li><a href="monthly-coll.php">BILL REPORTS</a></li>
        <li><a href="previeus_month_du.php">PREVIOUS MONTH DUE</a></li>

      </ul>
    </li>





    <li> <a href="logout.php"><i class="fa fa-sign-out"></i> <span class="nav-label">LOGOUT</span></a> </li>



  </ul>
</div>