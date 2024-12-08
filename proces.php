<?php

include('connection.php');
// var_dump($_POST['tel']);
$tel = $_POST['tel'] ?? null;   
$flatno = $_POST['flatno'] ?? null;
$owner = $_POST['owner'] ?? null;  


if ($tel && $flatno && $owner) {
  $query = "INSERT INTO flats(flat_number, mobile_number, owner_name) VALUES('$flatno', '$tel', '$owner')";
  if ($conn->query($query) === TRUE) {
    header("Location: addnewflat.php?status=success");
    exit(); 
  } else {
    echo "Error: " . $query . "<br>" . $conn->error;
  }
} else {
  echo "Please fill all fields.";
}

$conn->close();
?>
