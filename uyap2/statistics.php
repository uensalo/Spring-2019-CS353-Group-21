<!DOCTYPE HTML>
<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <title>UYAP2 - Statistics</title>
</head>
<body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
  <div style="margin-top:4%; text-align: center;">
		<?php

	// TO DO DOES NOT WORK AKLJSDHKLAJSDNHK>JASNHDKLJASDHJLAS
	include("config.php");
	$year = 2008;
	$capi = "SELECT Count(Date) FROM Crime WHERE YEAR(Date) = '$year'";
	$result = mysqli_query ($db,$capi) or die('Error updating database: '.mysql_connect_error($capi));
	if($table = mysqli_fetch_array($result)){
		$crimePer = $table[0];
	}
	$capi = "SELECT Count(TC_ID) FROM Citizen WHERE YEAR(Birthdate) < '$year'";
	$result = mysqli_query ($db,$capi) or die('Error updating database: '.mysql_connect_error($capi));
	if($table = mysqli_fetch_array($result)){
		$alive = $table[0];
	}
	//echo $alive;
	//echo $crimePer;
  echo '<div style="margin:10%">';
  echo'<div class="w3-panel w3-leftbar w3-rightbar w3-indigo w3-xxlarge w3-serif w3-round-xlarge w3-hover-red"><p><i>';
	if($alive != 0){
		echo "Crimes Per Capita in " .$year. " = " . ($crimePer/$alive) ;
	}
	else{
		echo "No Citizen has been born before the given year";
	}
  echo '</i></p></div>';


	$count = "SELECT MAX(Count) FROM (SELECT Count(TC_ID) AS Count,TC_ID FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect' GROUP BY TC_ID) AS counter";
	$result = mysqli_query ($db,$count) or die('Error updating database: '.mysql_connect_error($capi));
	if($table = mysqli_fetch_array($result)){
		$Max = $table[0];
	}



	$capi = "SELECT Count,FullName FROM (SELECT Count(TC_ID) AS Count,TC_ID FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect' GROUP BY TC_ID) AS counter Natural JOIN Citizen WHERE Count = '$Max' ";
	//$capi = "SELECT Count(*),FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID WHERE role = 'suspect') GROUP BY FullName";
	$result = mysqli_query ($db,$capi) or die('Error updating database: '.mysql_connect_error($capi));

  echo'<div class="w3-panel w3-leftbar w3-rightbar w3-blue w3-xxlarge w3-serif w3-round-xlarge w3-hover-deep-orange"><p><i>';
	While($table = mysqli_fetch_array($result)){
		echo "The most Wanted Man is: " .$table[1] . " with " . " $table[0] ". "cases!!";
	}
  echo '</i></p></div>';

	$count = "SELECT MAX(Count) FROM (SELECT Count(TC_ID) AS Count,Nationality FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect' GROUP BY Nationality) AS counter";
	$result = mysqli_query ($db,$count) or die('Error updating database: '.mysql_connect_error($capi));
	if($table = mysqli_fetch_array($result)){
		$Max = $table[0];
	}
	$nation = "SELECT Count(*) FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect'";
	$result = mysqli_query ($db,$nation) or die('Error updating database: '.mysql_connect_error($capi));
	if($table = mysqli_fetch_array($result)){
		$total_crime = $table[0];
	}
	//echo $total_crime;

	$capi = "SELECT Count,FullName FROM (SELECT Count(TC_ID) AS Count,TC_ID FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect' GROUP BY TC_ID) AS counter Natural JOIN Citizen WHERE Count = '$Max' ";
	$racist = "SELECT Count,Nationality FROM (SELECT Count(*) AS Count,Nationality FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Role = 'Suspect' GROUP BY Nationality) AS counted WHERE Count = '$Max'";
	$result = mysqli_query ($db,$racist) or die('Error updating database: '.mysql_connect_error($capi));

	if($table = mysqli_fetch_array($result)){
    echo'<div class="w3-panel w3-leftbar w3-rightbar w3-light-blue w3-xxlarge w3-serif w3-round-xlarge w3-hover-pink"><p><i>';
		echo "The Majority of crimes committed by Citizens from " .$table[1] . " with: " . $table[0] ." cases!!";
    echo '</i></p></div>';
    echo'<div class="w3-panel w3-leftbar w3-rightbar w3-cyan w3-xxlarge w3-serif w3-round-xlarge w3-hover-purple"><p><i>';
		echo "This is  " .($table[0]/$total_crime)*100 . "% of all Crimes!!";
    echo '</i></p></div>';
	}
  echo '<button class="w3-button w3-round-large w3-indigo" onclick="window.location.href = \'index.php\';" style="width:15%"><strong>Back</strong></button><br><br><br>';
  echo '</div>';
?>
	</body>
</html>
