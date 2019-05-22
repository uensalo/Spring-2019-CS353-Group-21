<!DOCTYPE HTML>
<?php
session_start();
?>

<html>

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>UYAP2 - Expert Witness View</title>
</head>

<body>
	<?php
	include("config.php");
	$tc = 	$_SESSION['tc'];
	$name = "SELECT FullName FROM Citizen WHERE TC_ID = '$tc'";
	$result = mysqli_query($db, $name) or die('Error updating database: ' . mysql_connect_error($result));

	$expr = "SELECT Expertise_Field FROM Expert_Witness WHERE TC_ID = '$tc'";
	$exprResult = mysqli_query($db, $expr) or die('Error updating database: ' . mysql_connect_error($result));

	while ($table = mysqli_fetch_array($result)) {
		$name = $table[0];
		$expr = mysqli_fetch_array($exprResult);
		echo ("<div class=\"w3-top\"><div class=\"w3-bar w3-border w3-indigo w3-text-white\"> <a href=\"view_expert.php\" class=\"w3-bar-item w3-button w3-padding-16\">
		<i class=\"material-icons\">person</i></a><div class=\"w3-bar-item w3-padding-16\">" . $name . "&nbsp" . $tc .  "&nbsp" . $expr[0] ."</div><div class=\"w3-bar-item w3-right\"><form action='view_expert.php' method='post'><input type='submit' class=\"w3-input w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name='Log' value='Logout' style='width:65%; height:65%'></form></div><div class=\"w3-bar-item w3-right w3-padding-16\" style=\"display:inline-block; margin=1%;\"><strong>Expert Witness</strong></div></div></div>");
	}
	if ($_SESSION['user_type'] == 'Expert Witness') {
		$cases;
		$i = 0;
		echo "<div style=\"margin-top:5%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Active Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
	<table class="w3-table-all w3-centered w3-hoverable">
	  <tr>
		<th>Case ID</th>
		<th>Latest Trial</th>
		<th>Victim(s)</th>
		<th>Suspect(s)</th>
		<th>Opening Date</th>
		<th></th>
	  </tr>';

		$date = date('Y-m-d');
		$sql = "SELECT DISTINCT Case_ID,MAX(T_Date),Open_Date FROM Informs NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE TC_ID= '$tc' AND Case_State = 'On-Going' GROUP BY Case_ID";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='view_expert.php' method='post'>";
			echo ("<th>" . $table[0] . "</th>");
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

		echo "<div style=\"margin-top:2%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Closed Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
	<table class="w3-table-all w3-centered w3-hoverable">
	  <tr>
		<th>Case ID</th>
		<th>Latest Trial</th>
		<th>Victim(s)</th>
		<th>Suspect(s)</th>
		<th>Opening Date</th>
		<th></th>
	  </tr>';
		$date = date('Y-m-d');
		$sql = "SELECT DISTINCT Case_ID,MAX(T_Date),Open_Date FROM Councils NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE Conciliator_ID= '$tc' AND Case_State = 'Closed' GROUP BY Case_ID";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='view_expert.php' method='post'>";
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

		for ($i = 0; $i < $ct; $i++) {
			$val = 'action' . strval($i);
			if (isset($_POST[$val])) {
				$_SESSION['CaseID'] = $cases[$val];
				echo "<script type='text/javascript'>
			window.location='caseview_gen.php';
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
		echo "<script type='text/javascript'>alert('Logged Out');
		window.location='index.php';
		exit;			
		</script>";
	}
	?>
</body>

</html>