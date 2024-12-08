<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('connection.php');
$months = [
    'month1' => '',
];
$years = [
    'year' => ''
];

// Add this line to capture the current date
$invoiceDate = date('d F Y'); // e.g., 22 October 2024
// Initialize the variable
$newInvoiceNumber = null;

// If form is submitted, process the input
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $serviceCharge = isset($_GET['serviceCharge']) ? floatval($_GET['serviceCharge']) : 0;
    $internetBill = isset($_GET['internetBill']) ? floatval($_GET['internetBill']) : 0;
    $dishBill = isset($_GET['dishBill']) ? floatval($_GET['dishBill']) : 0;
    $flatRent = isset($_GET['flatRent']) ? floatval($_GET['flatRent']) : 0;
    $commonBill = isset($_GET['commonBill']) ? floatval($_GET['commonBill']) : 0;
    $centerRent = isset($_GET['centerRent']) ? floatval($_GET['centerRent']) : 0;
    $centerVarious = isset($_GET['centerVarious']) ? floatval($_GET['centerVarious']) : 0;
    $atticRent = isset($_GET['atticRent']) ? floatval($_GET['atticRent']) : 0;
    $donation = isset($_GET['donation']) ? floatval($_GET['donation']) : 0;
    $developmentVarious = isset($_GET['developmentVarious']) ? floatval($_GET['developmentVarious']) : 0;
    // Fetch month and year values
    $selectedMonth = isset($_GET['month1']) ? htmlspecialchars($_GET['month1']) : '';
    $selectedYear = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '';
    // Fetch flat details
    $ownerName = isset($_GET['ownerName']) ? htmlspecialchars($_GET['ownerName']) : '';
    $mobileNumber = isset($_GET['mobileNumber']) ? htmlspecialchars($_GET['mobileNumber']) : '';
    $flatNumber = isset($_GET['flatNumber']) ? htmlspecialchars($_GET['flatNumber']) : '';
    //flat id 
    $flatId = isset($_GET['flatId']) ? intval($_GET['flatId']) : 0;


    // Calculate total amount
    $totalAmount = $serviceCharge + $internetBill + $dishBill + $flatRent + $commonBill + $centerRent + $centerVarious + $atticRent + $donation + $developmentVarious;



    $msg = ' ServiceCharge - ' . $serviceCharge . '';
    $msg .= ' InternetBill - ' . $internetBill;
    $msg .= ' DishBill - ' . $dishBill;
    $msg .= ' FlatRent - ' . $flatRent;
    $msg .= ' CommonBill - ' . $commonBill;
    $msg .= ' CenterRent - ' . $centerRent;
    $msg .= ' CenterVarious - ' . $centerVarious;
    $msg .= ' AtticRent - ' . $atticRent;
    $msg .= ' Donation - ' . $donation;
    $msg .= ' DevelopmentVarious - ' . $developmentVarious;
    $msg .= ' Total  - ' . $totalAmount;
    $msg .= ' For ' . $selectedMonth . '-' . $selectedYear . ' 
Darussalam Tower , Mirpur
';



    //echo $msg;



    if (isset($_GET['flatId'])) {
    } else {
        die();
    }
    if ($_GET['flatId'] == '') {
        die();
    }

    $query = "SELECT COUNT(f_id) as `num` FROM flat_bill where f_month = '{$selectedMonth}'  AND  f_year = '{$selectedYear}' AND f_flatId = '{$flatId}' ";
    $row = mysqli_fetch_array(mysqli_query($conn, $query));
    $total = $row['num'];
    if ($total > 0) {
        // echo 'Alreaday ';
    } else {
        // Step 1: Get the last invoice number
        $query = "SELECT MAX(f_invoice_number) as last_invoice FROM flat_bill";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $lastInvoiceNumber = $row['last_invoice'] ? $row['last_invoice'] : 0;
        $newInvoiceNumber = $lastInvoiceNumber + 1; // Increment the last invoice number

        $sql = "INSERT INTO flat_bill (f_date, f_month, f_year, f_service_charge, f_int_bill, f_dish_bill, f_flat_rent, f_c_current_bill, f_c_center_rent, f_c_center_various, f_atic_rent, f_d_donation, f_d_various_charge, f_status, f_flatId,f_total, f_invoice_number) 
    VALUES (NOW(), '$selectedMonth', '$selectedYear', $serviceCharge, $internetBill, $dishBill, $flatRent, $commonBill, $centerRent, $centerVarious, $atticRent, $donation, $developmentVarious, 'Pending',$flatId,$totalAmount, $newInvoiceNumber)";

        // Execute SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully.";
            sendGSMS('8809617620596', $mobileNumber, $msg, 'C200022562c68264972b36.87730554', 'text&contacts');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Ensure $newInvoiceNumber is defined before using it
    if ($newInvoiceNumber !== null) {
        // Your invoice generation logic here
        echo "
    <p>Invoice Number: $newInvoiceNumber</p>
   
    ";
    } else {
        echo "Invoice number could not be generated.";
    }

    // Prepare SQL statement
    /* 
*/


    //handle action basec on submit button clicked 
    if (isset($_GET['submitAction'])) {
        switch ($_GET['submitAction']) {
            case 'calculate':
                break;
            case 'invoice':
                // Generate invoice HTML
                echo "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Invoice</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .invoice-container { display: flex; justify-content: space-between; gap:20px; }
                        .invoice { border: 1px solid #ddd; padding: 20px; border-radius: 8px; width: 48%; }
                        .invoice-header {text-align: center; margin-bottom: 20px; }
                        .ivoice-detials{display: flex; justify-content:space-between; text-align:start;}
                        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        .invoice-table th, .invoice-table td {justify-content:center; padding: 10px; border: 1px solid #ddd; }
                        .invoice-table th { background-color: #f4f4f4; }
                        .invoice-total { font-weight: bold; }
                        .invoice-footer { text-align: center; margin-top: 20px; font-size: 0.875em; color: #666; }
                    </style>
                </head>
                <body>
                    <div class='invoice-container'>
                        <!-- Office Copy -->
                        <div class='invoice'>
                            <div class='invoice-header'>
                               
                                <div style='justify-content:space-between;'>
                                
                                 <img src='img/darus-sunna-logo1-150x150.png' alt='logo' style='width: 100px; height: 100px; background-color:black; border-radius:50%;'>
                                 
                                 <p><span style='font-size:20px; font-weight:bold;'>দারুস সালাম এ্যাপার্টমেন্ট ওনার্স এসোসিয়েশন</span><br>৫৯/ডি/এ, দারুস সালাম, মিরপুর, ঢাকা-১২১৬</p>
                                 
                                <h3>Office Copy</h3>
                                </div>
                              <div class='ivoice-detials'>
                                <div>
                                <p><strong>Owner Name:</strong> $ownerName</p>
                                <p><strong>Mobile Number:</strong> $mobileNumber</p>
                                <p><strong>Flat No:</strong> $flatNumber</p>
                                
                                </div>
                                <div>
                                <p><strong>Invoice Date:</strong> $invoiceDate</p> <!-- Add invoice date here -->
                                <p><strong>Month:</strong> " . htmlspecialchars(ucfirst($selectedMonth)) . "</p>
                                <p><strong>Year:</strong> " . htmlspecialchars($selectedYear) . "</p>
                                </div>
                              </div>
                            </div>
                            
                            <table class='invoice-table'>
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>";

                $charges = [
                    ['description' => 'Service Charge', 'amount' => $serviceCharge],
                    ['description' => 'Internet Bill', 'amount' => $internetBill],
                    ['description' => 'Dish Bill', 'amount' => $dishBill],
                    ['description' => 'Flat Rent', 'amount' => $flatRent],
                    ['description' => 'Common Bill', 'amount' => $commonBill],
                    ['description' => 'Center Rent', 'amount' => $centerRent],
                    ['description' => 'Center Various', 'amount' => $centerVarious],
                    ['description' => 'Attic Rent', 'amount' => $atticRent],
                    ['description' => 'Donation', 'amount' => $donation],
                    ['description' => 'Development Various', 'amount' => $developmentVarious],
                ];

                foreach ($charges as $charge) {
                    if ($charge['amount'] > 0) {
                        echo "<tr>
                                                <td>{$charge['description']}</td>
                                                <td> ৳" . number_format($charge['amount'], 0) . "</td>
                                              </tr>";
                    }
                }

                echo "</tbody>
                    </table>
                    <div class='invoice-total'>
                        <p>Total Amount:  ৳" . number_format($totalAmount, 0) . "</p>
                    </div>
                    <div class='invoice-footer'>
                    
                    <button onclick='editInvoice()'>Edit Invoice</button>
                    <p>Thank you for your business!</p>
                    </div>
                </div>

                
                <!-- Customer Copy -->
                <div class='invoice'>
                     <div class='invoice-header'>
                        
                                  
                              <div>  
                              <div style='display:flex; justify-content: center; align-items:center; gap:10px;'>
                                
                                 <img src='img/darus-sunna-logo1-150x150.png' alt='logo' style='width: 100px; height: 100px; background-color:black; border-radius:50%;'>
                                 
                                <p><span style='font-size:20px; font-weight:bold;'>দারুস সালাম এ্যাপার্টমেন্ট ওনার্স এসোসিয়েশন</span><br>৫৯/ডি/এ, দারুস সালাম, মিরপুর, ঢাকা-১২১৬</p>
                                 
                                </div>
                                <h3>Customer Copy</h3>
                              
                              </div>
                        <div class='ivoice-detials'>
                        <div>
                        <p><strong>Owner Name:</strong> $ownerName</p>
                         <p><strong>Mobile Number:</strong> $mobileNumber</p>
                         <p><strong>Flat No:</strong> $flatNumber</p>
                                
                            </div>
                             <div>
                            <p><strong>Invoice Date:</strong> $invoiceDate</p> <!-- Add invoice date here -->
                                <p><strong>Month:</strong> " . htmlspecialchars(ucfirst($selectedMonth)) . "</p>
                                <p><strong>Year:</strong> " . htmlspecialchars($selectedYear) . "</p>
                            </div>
                            </div>
                    </div>
                    <table class='invoice-table'>
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($charges as $charge) {
                    if ($charge['amount'] > 0) {
                        echo "<tr>
                                          <td>{$charge['description']}</td>
                                          <td> ৳" . number_format($charge['amount'], 0) . "</td>
                                      </tr>";
                    }
                }

                echo "</tbody>
                    </table>
                    <div class='invoice-total'>
                        <p>Total Amount:  ৳" . number_format($totalAmount, 0) . "</p>
                    </div>
                    <div class='invoice-footer'>
                    <p>Invoice Number: $newInvoiceNumber</p>
                   

                    <p>Thank you for your business!</p>
                    </div>
                </div>
            </div>
            
                </body>
                </html>";
                exit;
            case 'sms':
                break;
        }
    }
}



