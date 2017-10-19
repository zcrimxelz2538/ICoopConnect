<?php
require('condb.php');
$uid = $_POST["uid"];
$money = $_POST["money"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$sql = 'INSERT INTO debit(user_id,money,fname,lname) VALUES("'.$uid.'","'.$money.'","'.$fname.'","'.$lname.'")';
if ($conn->query($sql) === TRUE) {
  header('location: showtime.php');
} else {
header('location: showtime.php');
}
?>
