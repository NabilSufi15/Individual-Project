<?php
session_start(); //get the previous session info
if (isset($_SESSION['username'])) {
  session_destroy(); //destroy it
  header("Location:home.php"); //redirect back to the start

  //echo 'Sorry, You are not logged in.';

} else {

header("Location:home.php");
}



?>
