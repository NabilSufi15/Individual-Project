<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location:home.php");
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

  <div id="MainBody">

    <ul>
      <h1>Counting</h1>
      <li><a href= "counting1.php"><h2>Counting Animals</h2></a></li>
      <li><a href= "counting2.php"><h2>Counting Fruit</h2></a></li>
      <li><a href= "counting3.php"><h2>Counting Money</h2></a></li>
    </ul>

    <ul>
      <h1>Matching</h1>
      <li><a href= "matching1.php"><h2>Match Numbers</h2></a></li>
      <li><a href= "matching2.php"><h2>Match Sports</h2></a></li>
      <li><a href= "matching3.php"><h2>Match Fruits</h2></a></li>
    </ul>

    <ul>
      <h1>Order</h1>
      <li><a href= "order1.php"><h2>Order Numbers</h2></a></li>
      <li><a href= "order2.php"><h2>Order Letters</h2></a></li>
      <li><a href= "order3.php"><h2>Order Money</h2></a></li>
    </ul>

    <ul>
      <h1>Listening</h1>
      <li><a href= "listening1.php"><h2>Listening Numbers</h2></a></li>
      <li><a href= "listening2.php"><h2>Listening Colours</h2></a></li>
      <li><a href= "listening3.php"><h2>Listening Alphabet</h2></a></li>
    </ul>

    <ul>
      <h1>Grouping</h1>
      <li><a href= "grouping1.php"><h2>Grouping Numbers</h2></a></li>
      <li><a href= "grouping2.php"><h2>Grouping Colours</h2></a></li>
      <li><a href= "grouping3.php"><h2>Grouping Colours 2</h2></a></li>
    </ul>

    <ul>
      <h1>Recommendation</h1>
      <?php
      echo "<li>";
      $file_open = fopen("recom.csv", "r");
      while (($line = fgetcsv($file_open)) !== false) {

        if($line[0] == $_SESSION['user_id']){
            echo "<h2>" . $line[1] . "</h2>";
          }
      }
      fclose($file_open);
      echo "</li>";
      ?>
    </ul>




  </div>



  </div>


</body>

</html>
