<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location:home.html");
  echo 'Sorry, You are not logged in.';

} else {
}
?>

<!DOCTYPE html>
<html>
<head>
  <link href="CSS/style.css" rel="stylesheet" type="text/css" />
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<body>
  <div id="Container">
    <header>
      <h1>
        <center> <img src="assets/WebsiteName.png"/> </center>
      </h1>
    </header>


	<div id="navBar">
    <ul>
      <li><a href= "userHomepage.php"><b>Home</b></a></li>
      <li><a href= "gameResults.php"><b>Results</b></a></li>
  		<li><a href= "logout.php"><b>LogOut</b></a></li>
    </ul>
  </div>


      <h1>Past Game Results</h1>
      <?php
      //creates a table with several headings
      echo "<table>\n";
      echo "<th><h2>User ID</h2></th>";
      echo "<th><h2>Game ID</th>";
      echo "<th><h2>Game Name</h2></th>";
      echo "<th><h2>Game Template</h2></th>";
      echo "<th><h2>Score</h2></th>";
      echo "<th><h2>Attempt</h2></th>";
      echo "<th><h2>Rating</h2></th>";
      echo "<th><h2>Date</h2></th>";
      echo "</tr>";

      //opens the csv file
      $file_open = fopen("results.csv", "r");
      while (($line = fgetcsv($file_open)) !== false) {

        //checks if it can find a match to the user id
        if($line[1] == $_SESSION['user_id']){
            echo "<tr>";
            //prints user id
            echo "<td>" . $line[1] . "</td>";
            //prints game id
            echo "<td>" . $line[2] . "</td>";
            //prints game name
            echo "<td>" . $line[4] . "</td>";
            // prints game template
            echo "<td>" . $line[5] . "</td>";
            //prints score
            echo "<td>" . $line[6] . "</td>";
            //prints attempts
            echo "<td>" . $line[7] . "</td>";
            //prints ratings
            echo "<td>" . $line[8] . "</td>";
            //prints date
            echo "<td>" . $line[9] . "</td>";
            echo "</tr>\n";
          }
      }
      //closes file and table
      fclose($file_open);
      echo "\n</table>";
      ?>



</body>

</html>
