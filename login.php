<?php include('connection.php');
//echo sha1('dst##89##');
if(isset($_POST['subb'])){
$username = $_POST['username'];
$password = sha1($_POST['password']);
if($username == 'sobarmart' AND $password == '2a48b41b4240815e0221e851de3087a428295b17'){
$_SESSION['sa_crm_drs_id'] = true;	
header("Location: index.php");
}

}
?>
<?php include('header.php') ?>
<?php 

?>

<style>
.login-container {
    width: 900px;
}
.sa-bg {
    position: relative; /* Make sure the overlay is positioned relative to the container */
    background-image: url('img/sobamarthltd.jpeg');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh; /* Full viewport height */
    width: 100%; /* Full width */
    display: flex; /* If you want to center the content within */
    justify-content: center;
    align-items: center;
}

/* Adding a black shadow overlay */
.sa-bg::before {
    content: ""; /* Required to generate a pseudo-element */
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3); /* Semi-transparent black background */
    z-index: 1; /* Make sure it stays above the background image but below content */
}

/* To ensure the content stays above the overlay */
.sa-bg > * {
    position: relative;
    z-index: 2;
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