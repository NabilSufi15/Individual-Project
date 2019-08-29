<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location:home.html");
  echo 'Sorry, You are not logged in.';

} else {
echo 'Welcomeeeee, '.$_SESSION['username'];
// echo "\n". $_SESSION['user_id'];
}

//$user_id = '';
echo "<html><body><table>\n\n";
$file_open = fopen("recom.csv", "r");
while (($line = fgetcsv($file_open)) !== false) {

  if($line[0] == $_SESSION['user_id']){
      echo "<tr>";
      echo $line[1];
      echo "<td><a href='matching2.php'> a " . $line[1] . "</a></td>";
      echo "<td>" . $line[3] . "</td>";
      echo "</tr>\n";
    }
}
fclose($file_open);
echo "\n</table></body></html>";
?>
