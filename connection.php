<?php
session_start();
$servername = "localhost"; $username = "root"; $password = ""; $dbname = "sobarmarth";  
//$servername = "localhost";$username = "root"; $password = ""; $dbname = "proj_daussalam";  


$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");

function sendGSMS($senderID, $recipient_no, $message,$api_key,$sms_type = 'text&contacts'){
  $senderID = " 8809601003682";	 
  $api_key = "39|QEHGxxmparbleyM2yb3M2QNGX3hpIPT25TodHf2c";
  $message = $message;
  $url = "https://login.esms.com.bd/api/v3/sms/send";
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $headers = array(
     "Accept: application/json",
     "Authorization: Bearer  $api_key",
     "Content-Type: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  
  $data = [
    'recipient' => '88'.$recipient_no,
    'sender_id' => $senderID,
    'message' => urldecode($message),
  ];
  
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
  //print_r $curl;
  //for debug only!
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  
  $resp = curl_exec($curl);
  curl_close($curl);
  
  return $resp;
   
  }




  function get_acc($FltID,$month,$year,$cmd){
    global $conn;
    
    if($cmd == 'ALL'){
        $fetchQuery = "SELECT sum(f_total) as Total_am FROM flat_bill WHERE f_status = 'Received' ";
    $result = mysqli_query($conn, $fetchQuery);
    $row = mysqli_fetch_assoc($result);
    echo number_format($row['Total_am'],0);
    }

    if($cmd == 'MONTH'){
      $fetchQuery = "SELECT sum(f_total) as Total_am FROM flat_bill WHERE f_month = '{$month}' AND f_year = '{$year}' AND f_status = 'Received' ";
   $result = mysqli_query($conn, $fetchQuery);
   $row = mysqli_fetch_assoc($result);
    echo number_format((float)$row['Total_am'],0);
    
    }
	
	if($cmd == 'COUNT-TR-MONTH'){
      $fetchQuery = "SELECT count(f_total) as Total_am FROM flat_bill WHERE f_month = '{$month}' AND f_year = '{$year}' AND f_status = 'Received' ";
   $result = mysqli_query($conn, $fetchQuery);
   $row = mysqli_fetch_assoc($result);
    echo number_format((float)$row['Total_am'],0);
    
    }
	
    }
    
       function get_expense_sum($cmd) {
    global $conn;

    if ($cmd == 'ALL') {
        $fetchQuery = "SELECT SUM(ex_amount) AS Total_exp FROM expenses";
        $result = mysqli_query($conn, $fetchQuery);
        $row = mysqli_fetch_assoc($result);
        echo number_format((float)$row['Total_exp'], 2, '.', ',');
		
    } 
	
	if ($cmd == 'THIS-MONTH') {
		$gm = date('m');
		$gy = date('Y');
        $fetchQuery = "SELECT SUM(ex_amount) AS Total_exp FROM expenses WHERE ex_month = '{$gm}' AND ex_year ='{$gy}'";
        $result = mysqli_query($conn, $fetchQuery);
        $row = mysqli_fetch_assoc($result);
        echo number_format((float)$row['Total_exp'], 0);
		//echo $fetchQuery;
    } 
	
   } 
   
$fnc_expense_type = array('Salary' => 'Salary','Transport' => 'Transport','Snacks' => 'Snacks','Purchase' => 'Purchase',
'Mobile-Bill' => 'Mobile Bill','Marketing' => 'Marketing'
			,'Stationary' => 'Stationary','Office-Expense' => 'Office Expense'
			,'Accessories' => 'Accessories','entertainment' => 'Entertainment'
			,'internet-Bill' => 'Internet Bill','bonus' => 'Bonus',
			'cashout' => 'Cashout Cost');

?>