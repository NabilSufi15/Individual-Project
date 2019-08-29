<?php
session_start();

$user_id = '';
$fname    = '';
$lname    = '';
$email    = '';
$username = '';
$password = '';
$error = '';

//if user clicka submit store variables
if (isset($_POST['submit'])) {
    $fname    = $_POST['fname'];
    $lname    = $_POST['lname'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    //if firstname is empty
    if (empty($_POST["fname"])) {

        //alert user to enter first name
        $error= "Please enter your first name";

    //if lastname is empty
    } else if (empty($_POST["lname"])) {

        //alert user to enter last name
        $error= "Please enter your last name";

    //if email is empty
    } else if (empty($_POST["email"])) {

        //alert user to enter email
        $error= "Please enter your email";

    // if username is empty
    } else if (empty($_POST["username"])) {

        //alert user to enter username
        $error= "Please enter your username";

    //if password is empty
    } else if (empty($_POST["password"])) {

        //alert user to enter password
        $error="Please enter your Password";

    } else {

        //if fields are not empty, output
        $error= "registered success";
        header("Location:home.php");

        $file_open = fopen("users.csv", "a");
        $user_id   = count(file("users.csv"));

        //adds the new registered user to the users.csv file
        if ($user_id > 1) {
            $user_id= ($user_id - 1) + 1;
        }
        $form_data = array(
            'user_id' => $user_id,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'username' => $username,
            'password' => $password
        );
        fputcsv($file_open, $form_data);
    }
}


?>

<!DOCTYPE html>
<html>
   <head>
     <link href="CSS/style.css" rel="stylesheet" type="text/css" />
   </head>
   <body>
       <header>
         <h1>
           <center> <img src="assets/WebsiteName.png"/> </center>
         </h1>
       </header>

     <div id="navBar">
        <ul>
         <li><a href= "home.php"><b>Login</b></a></li>
         <li><a href= "register.php"><b>Register</b></a></li>
        </ul>
      </div>

      <form method="post">
        <div class='register-box'>
          <h1>Register</h1>
         <div class="textbox">
            <label>First Name: </label>
            <input type="text" name="fname" placeholder="Enter First Name" class="form-control"/>
         </div>
         <div class="textbox">
            <label>Last Name: </label>
            <input type="text" name="lname" placeholder="Enter Last Name" class="form-control"/>
        </div>
         <div class="textbox">
            <label>Email: </label>
            <input type="text" name="email" placeholder="Enter Email Address" class="form-control"/>
        </div>
         <div class="textbox">
            <label>Username: </label>
            <input type="text" name="username" placeholder="Enter Username" class="form-control"/>
        </div>
         <div class="textbox">
            <label>Password: </label>
            <input type="password" name="password" placeholder="Enter Password" class="form-control"/>
         </div>
         <div class="textbox">
            <input type="submit" name="submit" class="btn" value="Submit"/>
         </div>
       </div>
      </form>

      <div class="message">
        <h1><?= $error; ?></h1>
      </div>
   </body>
</html>
