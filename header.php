<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOBARMART</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Correct FontAwesome CSS CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="css/main.css">
  
  <script>
    $(document).ready(function() {
      $('.button-left').click(function() {
        $('.sidebar').toggleClass('fliph');
      });
    });
  </script>
  
    <style>
    .logo {
        height: 35px; /* Set the desired height */
        width: 50px; /* Maintain aspect ratio */
       margin-left: -16px;
    }
    a.navbar-brand{
      width: 40px;
    }
</style>

</head>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background: linear-gradient(to bottom,rgb(22, 148, 171),rgb(160, 219, 232));">
    <div class="container-fluid">
        <!--<a class="navbar-brand" href="#"><img  class="logo" src="./img/logo.jpg"></a> <!-- Logo -->
        <h4 style="color: #02306b;">SOBARMART LIMITED</h4>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link fs-6 " href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a> <!-- Logout Option -->
                </li>
            </ul>
        </div>
    </div>
</nav>
