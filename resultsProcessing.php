<?php

session_start();
if (!isset($_SESSION['username'])) {
  echo 'Sorry, You are not logged in.';
  header("Location:home.php");

} else {
echo 'Welcome, '.$_SESSION['username'];
header("Location:userHomepage.php");
}

if (isset($_POST["submit"]))
{
//gets results from one of the games when submit is selected
  $score = $_POST["score"];
  $attempt = $_POST["attempt"];
  $game = $_POST['game'];
  $game_id = $_POST['game_id'];
  $template = $_POST["template"];
  $rating = $_POST["rating"];
  $username = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];
  $date = date('d-m-Y');

  $file_open = fopen("results.csv", "a");
  $no_rows = count(file("results.csv"));
  if($no_rows > 1)
  {
   $no_rows = ($no_rows - 1) + 1;
  }

  //appends the data to results.csv file
  $data = array(
   'row'  => $no_rows,
   'user_id' => $user_id,
   'game_id' => $game_id,
   'username' => $username,
   'game' => $game,
   'template' => $template,
   'score'  => $score,
   'attempt'  => $attempt,
   'rating' => $rating,
   'date' => $date
  );

  fputcsv($file_open, $data);


}

?>
