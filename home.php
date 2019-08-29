<?php
   session_start();

   $username = '';
   $password = '';
   $user_id = '';
   $error = '';

   //if user clicks submit
   if(isset($_POST['submit'])){
     $username= $_POST['username'];
     $password = $_POST['password'];
     //$user_id = $_POST['user_id'];

     //if username is empty
     if(empty($_POST["username"]))
     {

      $error= "Please Enter your Name";

     //if password is empty
    }else if (empty($_POST["password"])) {

        $error= "Please Enter your password";

    }

     $success = false;
   $handle = fopen("users.csv", "r");

  //checks whther the username and password match comparing to the users.csv file
   while (($data = fgetcsv($handle)) !== FALSE) {
       if ($data[4] == $username && $data[5] == $password) {
           $success = true;
           break;
       }
   }

   fclose($handle);

   //if match can log in and is directed to userHomepage
   if ($success) {
       // they logged in ok
       $user_id= $data[0];
       $_SESSION['username'] = $username;
       $_SESSION['user_id'] = $user_id;
       header("Location:userHomepage.php");
       exit();
       $error= "<h2>logged in</h2>";
   //if username and password doesn't match
   } else {
       // login failed
       $error= "<h2>Error logging in: username and password doesn't match</h2>";
   }


   }

   ?>
   <!DOCTYPE html>
   <html>
   <head>
     <link href="CSS/style.css" rel="stylesheet" type="text/css" />
   </head>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
     <div class="login-box">
        <h1> Login </h1>
        <div class="textbox">
           <label>Username: </label>
           <input type="text" name="username" placeholder="Enter Name" class="form-control" />
        </div>

        <div class="textbox">
           <label>Password: </label>
           <input type="password" name="password" placeholder="Enter Password" class="form-control"/>
        </div>

        <input type="submit" name="submit" class="btn" value="Login" />

    </div>
     </form>

     <div class="message">
       <h1><?= $error; ?></h1>
     </div>

   </body>

   </html>
