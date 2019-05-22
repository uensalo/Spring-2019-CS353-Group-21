<!DOCTYPE HTML>
<?php
session_start();
?>

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>UYAP2 - Citizen View</title>
</head>
<html>

<body>
	<?php
	include("config.php");

	$tc = 	$_SESSION['tc'];
	$name = "SELECT FullName FROM Citizen WHERE TC_ID = '$tc'";
	$result = mysqli_query($db, $name) or die('Error updating database: ' . mysql_connect_error($result));
	while ($table = mysqli_fetch_array($result)) {
		$name = $table[0];
		echo ("<div class=\"w3-top\"><div class=\"w3-bar w3-border w3-indigo w3-text-white\"> <a href=\"view_citizen.php\" class=\"w3-bar-item w3-button w3-padding-16\">
		<i class=\"material-icons\">person</i></a><div class=\"w3-bar-item w3-padding-16\">" . $name . "&nbsp" . $tc .  "</div><div class=\"w3-bar-item w3-right\"><form action='view_citizen.php' method='post'><input type='submit' class=\"w3-input w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name='Log' value='Logout' style='width:65%; height:65%'></form></div><div class=\"w3-bar-item w3-right w3-padding-16\" style=\"display:inline-block; margin=1%;\"><strong>Citizen</strong></div></div></div>");
	}
	if ($_SESSION['user_type'] == 'Citizen') {
		$cases;
		$i = 0;
		$concs;
		$j = 0;
		echo "<div style=\"margin-top:5%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Active Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
	<table class="w3-table-all w3-centered w3-hoverable">
	  <tr>
		<th>Case ID</th>
		<th>Latest Trial</th>
		<th>Victim(s)</th>
		<th>Suspect(s)</th>
		<th>Opening Date</th>
		<th>Case Information</th>
		<th>Agree on Conciliation</th>
		<th></th>
	  </tr>';
		$date = date('Y-m-d');
		$sql = "SELECT Case_ID,MAX(T_Date),Open_Date,Role FROM Involved NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE Litigant_ID = '$tc' AND Case_State = 'On-Going' GROUP BY Case_ID";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='view_citizen.php' method='post'>";
			echo ("<th>" . $table[0] . "</th>");
			//echo($tc . " &nbsp;&nbsp;  ");
			echo ("<th>" . $table[2] . "</th>");  //Open date
			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]' AND Role = 'Victim'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";


			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]' AND Role = 'Suspect'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";

			echo ("<th>" . $table[1] . "</th>");
			echo "<th><input class=\"w3-input w3-border w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name=\"action" . $i . "\" value='GO'></th>";
			echo "</form>";
			$val = 'action' . strval($i);
			$cases[$val] = $table[0];
			$i++;


			$sql2 = "SELECT Agreed FROM Councils WHERE Case_ID = '$table[0]' AND Litigant_ID ='$tc' ";
			$result2 = mysqli_query($db, $sql2) or die('Error updating database: ' . mysql_connect_error($result));
			if ($table2 = mysqli_fetch_array($result2)) {
				if ($table2[0] == 0) {
					echo "<form action='view_citizen.php' method='post'>";
					echo "<th><input class=\"w3-input w3-border w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name=\"check" . $j . "\" value='Accept Offer'></th>";

					$chv = 'check' . strval($j);
					$concs[$chv] = $table[0];
					$j++;
				} else {
					echo "<form action='view_citizen.php' method='post'>";
					echo "<th><input disabled class=\"w3-input w3-border w3-indigo w3-blue-grey\" type=\"submit\" style=\"margin: 0 auto;\" name=\"check" . $j . "\" value='Offer Accepted'></th>";
				}
				echo "</form></tr>";
			}
		}

		echo "</table></div></div>";


		echo "<div style=\"margin-top:5%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Closed Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
	<table class="w3-table-all w3-centered w3-hoverable">
	  <tr>
		<th>Case ID</th>
		<th>Latest Trial</th>
		<th>Victim(s)</th>
		<th>Suspect(s)</th>
		<th>Opening Date</th>
		<th>Case Information</th>
		<th></th>
	  </tr>';
		$sql = "SELECT Case_ID,MAX(T_Date),Open_Date FROM Involved NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE Litigant_ID = '$tc'AND Case_State = 'Closed' GROUP BY Case_ID";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='view_citizen.php' method='post'>";
			echo ("<th>" . $table[0] . "</th>");
			//echo($tc . " &nbsp;&nbsp;  ");
			echo ("<th>" . $table[2] . "</th>");  //Open date
			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]' AND Role = 'Victim'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";

			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]' AND Role = 'Suspect'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";

			echo ("<th>" . $table[1] . "</th>");
			echo "<th><input class=\"w3-input w3-border w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name=\"action" . $i . "\" value='GO'></th>";
			echo "</form></tr>";
			$val = 'action' . strval($i);
			$cases[$val] = $table[0];
			$i++;
		}
		echo "</table></div></div>";

		$ct = $i;
		$ct_concil = $j;

		for ($i = 0; $i < $ct; $i++) {
			$val = 'action' . strval($i);
			if (isset($_POST[$val])) {
				$_SESSION['CaseID'] = $cases[$val];
				echo "<script type='text/javascript'>
			window.location='caseview_gen.php';
			</script>";
			}
		}

		for ($i = 0; $i < $ct_concil; $i++) {
			$chv = 'check' . strval($i);
			if (isset($_POST[$chv])) {
				$sql3 = "UPDATE Councils SET Agreed = 1 WHERE Case_ID = '$concs[$chv]' AND Litigant_ID ='$tc' ";
				$result3 = mysqli_query($db, $sql3);
				echo "<script type='text/javascript'>alert('Accepted');
			window.location='view_citizen.php';			
			</script>";
			}
		}

		//echo "The current server timezone is: " . $date;
		while ($table = mysqli_fetch_array($result)) {
			echo ($table[0] . " &nbsp;&nbsp; || ");
			echo ($table[1] . " &nbsp;&nbsp; || ");
		}
	} else {
		header('location: error.php');
	}


	if (isset($_POST['Log'])) {
		session_destroy();
		header('location: index.php');
	}

	?>
</body>

</html>