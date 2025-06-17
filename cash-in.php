<style>
    .dashboard-section {
        display: flex;
        justify-content: space-between;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        text-align: center;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1.25rem;
    }

    .icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #007bff;
    }

    .card-header {
        background-color: #f8f9fa;
    }

    .form-container {
        width: 400px;

        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-group textarea {
        resize: vertical;
    }

    .form-group button {
        padding: 10px 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-group button:hover {
        background-color: #45a049;
    }
</style>

<div class="dashboard-section">
    <div style="width: 20%;">
        <?php
        include('./templates/sidebar.php');
        ?>
    </div>

    <div class="container mt-4" style="width: 80%;">
        <div class="row">
            <!-- Bill Paid Card -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="icon fas fa-check-circle"></i>
                        <h5 class="card-title"> CASH IN OUT</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">$1,234.56</p>
                        <p class="card-text">TEXT</p>
                    </div>
                </div>
            </div>

            <!-- Expense Card -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="icon fas fa-credit-card"></i>
                        <h5 class="card-title">MONTHLY CASH IN</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">$987.65</p>
                        <p class="card-text">TEXT</p>
                    </div>
                </div>
            </div>


            <!-- Collection Card -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="icon fas fa-hand-holding-usd"></i>
                        <h5 class="card-title">YEARLY CASH IN</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">$1,456.78</p>
                        <p class="card-text">Year collections</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="icon fas fa-hand-holding-usd"></i>
                        <h5 class="card-title">TOTAL CASH IN</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">$1,456.78</p>
                        <p class="card-text">total cash</p>
                    </div>
                </div>
            </div>
        </div>


        <div>

            <div class="form-container">
                <h2>Cash In Form</h2>
                <form action="/submit-cash-in" method="POST">
                    <div class="form-group">
                        <label for="username">User Name:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Note:</label>
                        <!-- <input type="text" id="note" name="note"> -->
                        <textarea type="text" id="note" name="note"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>


        </div>
    </div>

</div>