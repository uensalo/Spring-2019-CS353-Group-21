
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>UYAP2</title>
</head>

<body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
   <div style="text-align: center;">
     <img src="logo.png" class="w3-round"><br><br><br><br>
<?php		
	include("config.php");
	if(isset($_POST['start_date']) && isset($_POST['end_date']) ){
		$start = $_POST['start_date'];
		$end =   $_POST['end_date'];
		$sql = "SELECT Case_ID FROM Court_Case WHERE DATE(Open_Date) > DATE('$start') AND DATE(Open_Date) < DATE('$end')";
		$result = mysqli_query ($db,$sql) or die('Error updating database: '.mysql_connect_error($result));
		echo "<div style=\"margin-top:1%; margin-left: 5%; margin-right:5%;\">";
		echo '<div class="w3-container" style="margin-top:-1%">
		<table class="w3-table-all w3-centered w3-hoverable">
	  		<tr>
				<th>Case ID</th>
				<th>Victim(s)</th>
				<th>Suspect(s)</th>
	  		</tr>';
		while($table = mysqli_fetch_array($result)){
			echo '<tr><th>' . $table[0] . '</th>';
			$sql1 = "SELECT FullName,Role FROM Involved JOIN Citizen ON (Involved.Litigant_ID = Citizen.TC_ID) WHERE Case_ID = '$table[0]' ";
			$result1 = mysqli_query ($db,$sql1) or die('Error updating database: '.mysql_connect_error($result));
			echo '<th>';
			$bol = 1;
			while($table1 = mysqli_fetch_array($result1)){
				if($table1[1] == "Suspect" && $bol ==1) {
					$bol = 0;
					echo("</th><th>");
				}
				echo($table1[0] . "<br>");
			}
			echo "</th></tr>";
		}
		echo '</table></div><br><br>';
	}
?>	
     <form id='test' action="search_date.php" method="post">
	<label>Starting Date:</label>
        <input type = "date" name = "start_date" class = "box"/>
	<label>End Date To:</label>
        <input type = "date" name = "end_date" class = "box"/>	
	<input type="submit" name="submit" value="Submit" class="btn btn-info"/>
     </form>
</body>	
</html>