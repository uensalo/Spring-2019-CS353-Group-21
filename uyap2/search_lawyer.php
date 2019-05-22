<!DOCTYPE HTML>
<?php
session_start();
?>

<html>

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>UYAP2 - Lawyer Search View</title>
</head>

<body>
	<?php

	include('config.php');
	$tc1 = 	$_SESSION['tc'];
	$sql = "SELECT FullName,Bureau_Name,Office_Name FROM Lawyer NATURAL JOIN Citizen WHERE TC_ID = '$tc1'";
	$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
	while ($table = mysqli_fetch_array($result)) {
		$name = $table[0];
		echo
			"<div class=\"w3-top\">
			<div class=\"w3-bar w3-border w3-indigo w3-text-white\">
				<a href=\"view_lawyer.php\" class=\"w3-bar-item w3-button w3-padding-16\">
					<i class=\"material-icons\">person</i>
				</a>
				<div class=\"w3-bar-item w3-padding-16\">" . $name . "&nbsp" . $tc1 .  "&nbsp" . $table[1] . "&nbsp" . $table[2] . "</div>
				<a href=\"lawyer_new_case.php\" class=\"w3-bar-item w3-button w3-padding-16\">
					<i class=\"material-icons\">announcement</i>
				</a>
				<form action='view_lawyer.php' method='post'>
					<div class=\"w3-bar-item\">
						<input type=\"text\" class=\"w3-input w3-indigo w3-hover-grey\" style=\"margin: 0 auto;\" name='search' placeholder=\"Search By TC\" style='width:65%; height:65%'>
					</div>
					<div class=\"w3-bar-item\">
						<input class=\"w3-input w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name='Subbmit' value=\"Go\" style='width:65%; height:65%'>
					</div>
					<div class=\"w3-bar-item w3-right\">
						<input class=\"w3-input w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name='Log' value='Logout' style='width:65%; height:65%'>
					</div>
				</form>
				<div class=\"w3-bar-item w3-right w3-padding-16\" style=\"display:inline-block; margin=1%;\">
					<strong>Lawyer</strong>
				</div>
			</div>
		</div>";
	}
	if ($_SESSION["user_type"] == 'Lawyer') {

		if (isset($_POST['Subbmit'])) {
			if (!isset($_POST['search']) || $_POST['search'] == '') {
				echo "<script type='text/javascript'>alert('Please Fill in The Required Fields');
			window.location='search_lawyer.php';
			</script>";
			}
		}

		$tc1 = 	$_SESSION['stc'];
		$name = "SELECT FullName,Birthdate,Biological_Sex,Nationality FROM Citizen WHERE TC_ID = '$tc1'";
		$result = mysqli_query($db, $name) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			$name = $table[0];
			echo ($tc1 . " &nbsp;&nbsp;  ");
			echo ($table[0] . " &nbsp;&nbsp;  ");
			echo ($table[1] . " &nbsp;&nbsp;  ");
			if ($table[2] == 1)
				echo ('Male' . " &nbsp;&nbsp;  ");
			else
				echo ('Female' . " &nbsp;&nbsp;  ");
			echo ($table[3] . " &nbsp;&nbsp;  ");
			echo "<BR>";
		}

		$cases;
		$i = 0;
		echo "<div style=\"margin-top:5%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Active Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
		<table class="w3-table-all w3-centered w3-hoverable">
		  <tr>
			<th>Case ID</th>
			<th>Latest Trial</th>
			<th>Client(s)</th>
			<th>Opening Date</th>
			<th>Case Information</th>
			<th></th>
		  </tr>';
		$date = date('Y-m-d');
		$sql = "SELECT Case_ID,MAX(T_Date), Open_Date FROM Involved NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE Litigant_ID = '$tc1' AND Case_State = 'On-Going' GROUP BY Case_ID ";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='search_lawyer.php' method='post'>";
			echo ("<th>" . $table[0] . "</th>");
			echo ("<th>" . $table[1] . "</th>");  //Open date
			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";
			echo ("<th>" . $table[2] . "</th>");
			echo "<th><input class=\"w3-input w3-border w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name=\"action" . $i . "\" value='GO'></th>";
			echo "</form>";
			$val = 'action' . strval($i);
			$cases[$val] = $table[0];
			$i++;
		}
		echo "</table></div></div>";

		echo "<div style=\"margin-top:5%; margin-left: 5%; margin-right:5%;\"><div class='w3-panel w3-indigo'><h2>Closed Cases</h2></div>";
		echo '<div class="w3-container" style="margin-top:-1%">
		<table class="w3-table-all w3-centered w3-hoverable">
		  <tr>
			<th>Case ID</th>
			<th>Latest Trial</th>
			<th>Client(s)</th>
			<th>Opening Date</th>
			<th>Case Information</th>
			<th></th>
		  </tr>';
		$sql = "SELECT Case_ID,MAX(T_Date), Open_Date FROM Involved NATURAL JOIN TakesPlaceOn NATURAL JOIN Court_Case WHERE Litigant_ID = '$tc1'AND Case_State = 'Closed' GROUP BY Case_ID  ";
		$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
		while ($table = mysqli_fetch_array($result)) {
			echo "<tr><form action='search_lawyer.php' method='post'>";
			echo ("<th>" . $table[0] . "</th>");
			echo ("<th>" . $table[1] . "</th>");  //Open date
			$sql1 = "SELECT FullName FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]'";
			$result1 = mysqli_query($db, $sql1) or die('Error updating database: ' . mysql_connect_error($result));
			echo "<th>";
			while ($table1 = mysqli_fetch_array($result1)) {
				echo ($table1[0] . "<br>");
			}
			echo "</th>";

			echo ("<th>" . $table[2] . "</th>");
			echo "<th><input class=\"w3-input w3-border w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name=\"action" . $i . "\" value='GO'></th>";
			echo "</form>";
			$val = 'action' . strval($i);
			$cases[$val] = $table[0];
			$i++;
		}
		$ct = $i;
		echo "</table></div></div>";

		for ($i = 0; $i < $ct; $i++) {
			$val = 'action' . strval($i);
			if (isset($_POST[$val])) {
				//$_SESSION[] = 
				$_SESSION['CaseID'] = $cases[$val];
				echo "<script type='text/javascript'>
			window.location='caseview_lawyer.php';
			</script>";
			}
		}

		//echo "The current server timezone is: " . $date;
		while ($table = mysqli_fetch_array($result)) {
			echo ($table[0] . " &nbsp;&nbsp; || ");
			echo ($table[1] . " &nbsp;&nbsp; || ");
		}

		if (isset($_POST['Subbmit'])) {
			$tc2 = $_POST['search'];
			if (is_numeric($tc2)) {
				$sql = "SELECT TC_ID FROM Citizen";
				$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
				while ($table = mysqli_fetch_array($result)) {
					if ($tc2 == $table[0]) {
						$_SESSION['stc'] = $tc2;
						header("location: search_lawyer.php");
						exit;
					} else {
						echo "<script type='text/javascript'>alert('No citizen Found');
			window.location='search_lawyer.php';
			exit;			
			</script>";
					}
				}
			} else {
				echo "<script type='text/javascript'>alert('Please Enter a Valid TC');
			window.location='search_lawyer.php';
			exit;			
			</script>";
			}
		}


		if (isset($_POST['Log'])) {
			session_destroy();
			echo "<script type='text/javascript'>alert('Logged Out');
			window.location='index.php';
			exit;			
			</script>";
		}

	} else {
		header('location: error.php');
	}
	?>
</body>

</html>