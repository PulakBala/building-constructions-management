<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>


<main class="page-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <?php
                // Get the flat ID from the URL
                $flatId = isset($_GET['id']) ? intval($_GET['id']) : 0;

                // Initialize variables
                $ownerName = 'Not Available';
                $mobileNumber = 'Not Available';
                $flatNumber = 'Not Available';

                if ($flatId > 0) {
                    // Fetch the flat details from the database
                    $sql = "SELECT * FROM flats WHERE id = $flatId";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $flat = mysqli_fetch_assoc($result);

                        if ($flat) {
                            $ownerName = htmlspecialchars($flat['owner_name']);
                            $mobileNumber = htmlspecialchars($flat['mobile_number']);
                            $flatName = htmlspecialchars($flat['flatname']);
                            // $flatNumber = htmlspecialchars($flat['flat_number']);
                            // $rent = htmlspecialchars($flat['rent']);
                            // $advance = htmlspecialchars($flat['advance']);
                        } else {
                            // Handle case where flat is not found
                            $ownerName = 'Flat not found';
                            $mobileNumber = 'Flat not found';
                        }
                    } else {
                        // Handle SQL query error
                        $ownerName = 'Query failed';
                        $mobileNumber = 'Query failed';
                    }
                } else {
                    // Handle invalid ID
                    $ownerName = 'Invalid ID';
                    $mobileNumber = 'Invalid ID';
                }
                ?>



                <div class="flat-details">
                    <div class="container mt-4">
                        <div class=" d-flex justify-content-center mb-5">
                            <div>
                                <p><strong>Manager Name:</strong> <?php echo $ownerName; ?></p>
                                <p><strong>Mobile Number:</strong> <?php echo $mobileNumber; ?></p>
                                <p><strong>Building Name:</strong> <?php echo $flatName; ?></p>
                            </div>
                            <div class="ml-5" style="display: none;">
                                <p><strong>Flat No:</strong> <?php echo $flatNumber; ?></p>
                                <p><strong>Rent:</strong> <?php echo $rent; ?></p>
                                <p><strong>Advance:</strong> <?php echo $advance; ?></p>
                            </div>  
                        </div>
                        <form action="invoice.php" method="get">
                            <input type="hidden" name="flatId" value="<?php echo $flatId; ?>">
                            <input type="hidden" name="ownerName" value="<?php echo htmlspecialchars($ownerName); ?>">
                            <input type="hidden" name="mobileNumber" value="<?php echo htmlspecialchars($mobileNumber); ?>">
                            <input type="hidden" name="flatname" value="<?php echo htmlspecialchars($flatName); ?>">
                            <input type="hidden" name="flatNumber" value="<?php echo htmlspecialchars($flatNumber); ?>">


                            <!-- add bill input section  -->

                            <div class="form-section">
                                <h5>1.Received Rent</h5>

                                <div class="form-row">
                                    <label for="flatRent">Received Rent</label>
                                    <input type="number" id="flatRent" name="flatRent" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                            </div>


                            <div class="form-section">
                                <h5>2. Current Bill</h5>

                                <div class="form-row">
                                    <label for="commonBill">Current Bill</label>
                                    <input type="number" id="commonBill" name="commonBill" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                            </div>

                            
                            <div class="form-section">
                                <h5>3. Sallary</h5>
                                <div class="form-row" style="display: none;">
                                    <label for="centerRent">Rent</label>
                                    <input type="number" id="centerRent" name="centerRent" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                                <div class="form-row">
                                    <label for="guardBill">Guard Sallary</label>
                                    <input type="number" id="guardBill" name="guardBill" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($centerVarious); ?>">
                                </div>
                            </div>


                            
                            <div class="form-section">
                                <h5>4. Other expense</h5>
                                <div class="form-row" style="display: none;">
                                    <label for="centerRent">Rent</label>
                                    <input type="number" id="centerRent" name="centerRent" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                                <div class="form-row">
                                    <label for="centerVarious">Other Charges</label>
                                    <input type="number" id="centerVarious" name="centerVarious" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($centerVarious); ?>">
                                </div>
                            </div>


                            <div class="form-section">
                                <h5>5. Flat</h5>
                                <div class="form-row">
                                    <label for="details">Details</label>
                                    <input type="text" id="details" name="details" class="form-control" placeholder="Note">
                                </div>
                                <div class="form-row">
                                    <label for="emptyFlatBill">Empty Flat </label>
                                    <input type="number" id="emptyFlatBill" name="emptyFlatBill" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($centerVarious); ?>">
                                </div>
                            </div>


                            <div class="form-section" style="display: none;">
                                <h5>2. Internet Bill</h5>

                                <div class="form-row">
                                    <label for="internetBill">Internet Bill</label>
                                    <input type="number" id="internetBill" name="internetBill" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                            </div>


                            <div class="form-section" style="display: none;">
                                <h5>3. Dish Bill</h5>

                                <div class="form-row">
                                    <label for="dishBill">Dish Bill</label>
                                    <input type="number" id="dishBill" name="dishBill" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($internetBill); ?>">
                                </div>
                            </div>

                            <div class="form-section" style="display: none;">

                                <h5>4. Monthly Services Charge</h5>

                                <div class="form-row">
                                    <label for="serviceCharge">Services Charge</label>
                                    <input type="number" id="serviceCharge" name="serviceCharge" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($serviceCharge); ?>">
                                </div>



                            </div>





                            <div class="form-section" style="display: none;">
                                <h5>8. Rooftop Room Rent</h5>

                                <div class="form-row">
                                    <label for="atticRent">Rent</label>
                                    <input type="number" id="atticRent" name="atticRent" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($atticRent); ?>">
                                </div>
                            </div>
                            <div class="form-section" style="display: none;">
                                <h5>9. Development</h5>
                                <div class="form-row">
                                    <label for="donation">Donation</label>
                                    <input type="number" id="donation" name="donation" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($donation); ?>">
                                </div>
                                <div class="form-row">
                                    <label for="developmentVarious">Various Charges</label>
                                    <input type="number" id="developmentVarious" name="developmentVarious" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($developmentVarious); ?>">
                                </div>
                            </div>


                            <div class="form-section">
                                <h5>6. Select Month and Year</h5>
                                <div class="form-row">
                                    <label for="month1">Select Month</label>
                                    <select id="month1" name="month1" class="form-control">
                                        <option value="january">January</option>
                                        <option value="february">February</option>
                                        <option value="march">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label for="year">Select Year</label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                    </select>
                                </div>
                            </div>


                            <input type="hidden" name="action" value="calculate">
                            <div style="display: flex; gap:10px">

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="submitAction" value="invoice">Generate Invoice</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bootstrap JS and dependencies -->
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            </div>

        </div>
    </div>
</main>
<?php include('footer.php') ?>