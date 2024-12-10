<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sobarmarth";
//$servername = "localhost";$username = "root"; $password = ""; $dbname = "proj_daussalam";  


$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");

function sendGSMS($senderID, $recipient_no, $message, $api_key, $sms_type = 'text&contacts')
{
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
    'recipient' => '88' . $recipient_no,
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




function get_acc($FltID, $month, $year, $cmd)
{
  global $conn;

  if ($cmd == 'ALL') {
    $fetchQuery = "SELECT sum(f_total) as Total_am FROM flat_bill WHERE f_status = 'Received' ";
    $result = mysqli_query($conn, $fetchQuery);
    $row = mysqli_fetch_assoc($result);
    echo number_format($row['Total_am'], 0);
  }

  if ($cmd == 'MONTH') {
    $fetchQuery = "SELECT sum(f_paid_amount) as Total_am FROM flat_bill WHERE f_month = '{$month}' AND f_year = '{$year}' AND f_status = 'Received' ";
    $result = mysqli_query($conn, $fetchQuery);
    $row = mysqli_fetch_assoc($result);
    echo number_format((float)$row['Total_am'], 0);
  }

  if ($cmd == 'COUNT-TR-MONTH') {
    $fetchQuery = "SELECT count(f_total) as Total_am FROM flat_bill WHERE f_month = '{$month}' AND f_year = '{$year}' AND f_status = 'Received' ";
    $result = mysqli_query($conn, $fetchQuery);
    $row = mysqli_fetch_assoc($result);
    echo number_format((float)$row['Total_am'], 0);
  }
}


function getFlatBillSummary($month, $year) {
  global $conn;
  $query = "
      SELECT 
          fb.f_flatId,
          f.flatname,
          f.flat_number,
          SUM(fb.f_total) AS total_collected,
          f_due
      FROM 
          flat_bill fb
      LEFT JOIN 
          flats f ON fb.f_flatId = f.id
      WHERE 
          fb.f_month = '{$month}' AND fb.f_year = '{$year}' AND fb.f_status = 'Received'
      GROUP BY 
          fb.f_flatId, f.flatname, f.flat_number, f.rent
  ";
  $result = mysqli_query($conn, $query);
  if (!$result) {
      die("Query Error: " . mysqli_error($conn)); // Debugging for query issues
  }
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}








function get_expense_sum($cmd)
{
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

$fnc_expense_type = array(
  'Salary' => 'Salary',
  'Transport' => 'Transport',
  'Snacks' => 'Snacks',
  'Purchase' => 'Purchase',
  'Mobile-Bill' => 'Mobile Bill',
  'Marketing' => 'Marketing',
  'Stationary' => 'Stationary',
  'Office-Expense' => 'Office Expense',
  'Accessories' => 'Accessories',
  'entertainment' => 'Entertainment',
  'internet-Bill' => 'Internet Bill',
  'bonus' => 'Bonus',
  'cashout' => 'Cashout Cost'
);

function numberToWords($number) {
  $hyphen = '-';
  $conjunction = ' and ';
  $separator = ', ';
  $negative = 'negative ';
  $decimal = ' point ';
  $dictionary = [
      0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
      6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten',
      11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
      15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
      20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty',
      70 => 'seventy', 80 => 'eighty', 90 => 'ninety',
      100 => 'hundred', 1000 => 'thousand', 100000 => 'lakh', 10000000 => 'crore'
  ];

  if (!is_numeric($number)) {
      return false;
  }

  if ($number < 0) {
      return $negative . numberToWords(abs($number));
  }

  $string = $fraction = null;

  if (strpos((string)$number, '.') !== false) {
      list($number, $fraction) = explode('.', (string)$number);
  }

  switch (true) {
      case $number < 21:
          $string = $dictionary[$number];
          break;
      case $number < 100:
          $tens = ((int)($number / 10)) * 10;
          $units = $number % 10;
          $string = $dictionary[$tens];
          if ($units) {
              $string .= $hyphen . $dictionary[$units];
          }
          break;
      case $number < 1000:
          $hundreds = (int)($number / 100);
          $remainder = $number % 100;
          $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
          if ($remainder) {
              $string .= $conjunction . numberToWords($remainder);
          }
          break;
      default:
          foreach (array_reverse($dictionary, true) as $value => $word) {
              // Skip zero to prevent infinite loop
              if ($value == 0) continue;

              if ($number >= $value) {
                  $numBaseUnits = (int)($number / $value);
                  $remainder = $number % $value;
                  $string = numberToWords($numBaseUnits) . ' ' . $word;
                  if ($remainder) {
                      $string .= $remainder < 100 ? $conjunction : $separator;
                      $string .= numberToWords($remainder);
                  }
                  break;
              }
          }
  }

  if ($fraction !== null && is_numeric($fraction)) {
      $string .= $decimal;
      $words = [];
      foreach (str_split((string)$fraction) as $digit) {
          $words[] = $dictionary[$digit];
      }
      $string .= implode(' ', $words);
  }

  return $string;
}


?>