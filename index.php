<?php include('connection.php');

$stored_hashed_password = '3301e17c04255de032af4e03e1432cb6d6e12bdb'; 
if(isset($_POST['subb'])){
    $username = $_POST['username'];
    $input_password = $_POST['password']; // ইনপুট করা পাসওয়ার্ড
    $hashed_input_password = sha1($input_password); // ইনপুট পাসওয়ার্ডের হ্যাশ তৈরি করুন

    // যাচাই করুন: username এবং hashed password মিলছে কিনা
    if($username == 'sobarmart' AND $hashed_input_password == $stored_hashed_password){
        $_SESSION['sa_crm_drs_id'] = true;	
        header("Location: dashboard.php");
        exit; // নিশ্চিত করুন যে কোনো আউটপুট এখানে বন্ধ থাকবে
    } else {
        echo "<script>alert('Invalid Username or Password!');</script>";
    }
}
?>
<?php include('header.php') ?>
<?php 

?>
<style>
.login-container {
    width: 100%;
    max-width: 400px;
   
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.sa-bg {
    position: relative;
    background-image: url('img/sobamarthltd.jpeg');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.sa-bg::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.sa-bg > * {
    position: relative;
    z-index: 2;
}

.btn-login {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border: none;
    transition: 0.3s ease-in-out;
}

.btn-login:hover {
    background-color: #0056b3;
}

/* Responsive tweaks */
@media (max-width: 576px) {
    .login-container {
        padding: 20px;
        border-radius: 8px;
    }

    .login-container h2 {
        font-size: 1.5rem;
        text-align: center;
    }
}
</style>
<main class="page-content m-0 sa-bg">

    <div class="container-fluid ">

        <div class="row"> 

            <div class="login-container">
                <h2>Login</h2>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                    </div>
                    <button type="submit" name="subb" class="btn btn-login btn-block">Login</button>
                </form>
             
            </div>



        </div>

    </div>
</main>
<?php include('footer.php') ?>