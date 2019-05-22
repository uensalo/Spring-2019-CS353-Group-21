<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>UYAP2 - Case View</title>
</head>

<body>
    <?php
    include("config.php");
    $tc1 = 	$_SESSION['tc'];
	$sql = "SELECT FullName,Gavel_Name FROM Judge NATURAL JOIN Citizen WHERE TC_ID = '$tc1'";
	$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
    while ($table = mysqli_fetch_array($result)) {
		$name = $table[0];
		$gavel = $table[1];
		echo 
		"<div class=\"w3-top\">
			<div class=\"w3-bar w3-border w3-indigo w3-text-white\">
				<a href=\"view_judge.php\" class=\"w3-bar-item w3-button w3-padding-16\">
					<i class=\"material-icons\">person</i>
				</a>
				<div class=\"w3-bar-item w3-padding-16\">" . $name . "&nbsp" . $tc1 .  "</div>
				<a href=\"judge_gavel.php\" class=\"w3-bar-item w3-button w3-padding-16\">
					<i class=\"material-icons\">gavel</i>
				</a>
				<div class=\"w3-bar-item w3-padding-16\">" . $gavel . "</div>
				<form action='view_judge.php' method='post'>
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
					<strong>Judge</strong>
				</div>
			</div>
		</div>";
	}
    echo '<div class="w3-container" style="margin-top:5%;" margin-left:5%; margin-right:5%;>';
    $case_id = $_SESSION["CaseID"];
    $checkSql = "SELECT TC_ID FROM Judges WHERE Case_ID = '$case_id' LIMIT 1";
    $checkResult = mysqli_query($db, $checkSql);
    $checkOwner = false;
    if(mysqli_num_rows($checkResult) == 1) {
        $checkRow = mysqli_fetch_assoc($checkResult);
        $checkOwner = $checkRow['TC_ID'] == $_SESSION['tc'];
    }
    if ($_SESSION["user_type"] == 'Judge' && $checkOwner) {
        if (isset($_SESSION["CaseID"])) {
            $case_id = $_SESSION["CaseID"];
            $concilSql = "SELECT * FROM Councils WHERE Case_ID = $case_id";
            $concilResult = mysqli_query($db, $concilSql);
            $noConcil = mysqli_num_rows($concilResult);

            $judgeSql = "SELECT * FROM Judges, Citizen, Judge WHERE Case_ID = $case_id AND Judges.TC_ID = Citizen.TC_ID AND Judge.TC_ID = Judges.TC_ID";
            $result = mysqli_query($db, $judgeSql);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo '<div class="w3-container" style="margin: 2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Case ID</th>
                    <th>Judge TC</th>
                    <th>Judge Name</th>
                    <th>Gavel Name</th>
                </tr><tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $case_id . "</th><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Gavel_Name"] . "</th></tr></table></div>";
                }
            } else {
                echo "DUNYANIN SONU GELDİ. BUZULLAR BUZULLAR. HERYER OLDU BUZ GİBİ. BUZULLAR BUZULLAR.";
            }

            $suspectSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Citizen.TC_ID = Involved.Litigant_ID AND Involved.Role = \"Suspect\"";
            $result = mysqli_query($db, $suspectSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo '<div class="w3-container" style="margin:2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Suspect TC</th>
                    <th>Suspect Name</th>
                    <th>Birth Date</th>
                    <th>Sex</th>
                    <th>Nationality</th>
                    <th>Lawyer ID</th>
                    <th>Lawyer Name</th>
                    <th>Agreed to Concillation</th>
                </tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $suspectTC = $row["TC_ID"];
                    $lawyerSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Involved.Litigant_ID = $suspectTC AND Citizen.TC_ID = Involved.Lawyer_ID AND Involved.Role = \"Suspect\"";
                    $lawyerResult = mysqli_query($db, $lawyerSql);
                    $rowLawyer = mysqli_fetch_assoc($lawyerResult);
                    if ($rowLawyer != null) {
                        echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>" . $row["Lawyer_ID"] . "</th><th>" . $rowLawyer["FullName"] . "</th>";
                    } else {
                        echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>-</th><th>-</th>";
                    }
                    if ($noConcil > 0) {
                        $concilSql = "SELECT * FROM Councils WHERE Case_ID = $case_id";
                        $concilResult = mysqli_query($db, $concilSql);
                        $noConcil = mysqli_num_rows($concilResult);
                        while ($crow = mysqli_fetch_assoc($concilResult)) {
                            if ($crow["Litigant_ID"] == $row["TC_ID"]) {
                                echo "<th>";
                                if ($crow["Agreed"]) {
                                    echo "YES";
                                } else {
                                    echo "NO";
                                }
                                echo "</th></tr>";
                            }
                        }
                    }
                }
            echo '</table></div>';
            } else {
                echo "FATAL ERROR SUSPECT NOT FOUND.";
            }

            $concilSql = "SELECT * FROM Councils WHERE Case_ID = $case_id";
            $concilResult = mysqli_query($db, $concilSql);
            $noConcil = mysqli_num_rows($concilResult);
            $victimSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Citizen.TC_ID = Involved.Litigant_ID AND Involved.Role = \"Victim\"";
            $result = mysqli_query($db, $victimSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo '<div class="w3-container" style="margin:2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Victim TC</th>
                    <th>Victim Name</th>
                    <th>Birth Date</th>
                    <th>Sex</th>
                    <th>Nationality</th>
                    <th>Lawyer ID</th>
                    <th>Lawyer Name</th>
                    <th>Agreed to Concilation</th>
                </tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $victimTC = $row["TC_ID"];
                    $lawyerSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Involved.Litigant_ID = $victimTC AND Citizen.TC_ID = Involved.Lawyer_ID AND Involved.Role = \"Victim\"";
                    $lawyerResult = mysqli_query($db, $lawyerSql);
                    $rowLawyer = mysqli_fetch_assoc($lawyerResult);
                    echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>" . $row["Lawyer_ID"] . "</th><th>" . $rowLawyer["FullName"] . "</th>";
                    if ($noConcil > 0) {
                        $concilSql = "SELECT * FROM Councils WHERE Case_ID = $case_id";
                        $concilResult = mysqli_query($db, $concilSql);
                        $noConcil = mysqli_num_rows($concilResult);
                        while ($crow = mysqli_fetch_assoc($concilResult)) {
                            if ($crow["Litigant_ID"] == $row["TC_ID"]) {
                                echo "<th>";
                                if ($crow["Agreed"]) {
                                    echo "YES";
                                } else {
                                    echo "NO";
                                }
                                echo '</th></tr>';
                            }
                        }
                    }
                }
                echo '</table></div>';
            } else {
                echo "FATAL ERROR VICTIM NOT FOUND.";
            }

            $crimeSql = "SELECT * FROM Court_Case, Crime WHERE Court_Case.Case_ID = '$case_id' AND Court_Case.Crime_ID = Crime.Crime_ID";
            $result = mysqli_query($db, $crimeSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo '<div class="w3-container" style="margin:2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Crime ID</th>
                    <th>Crime Date</th>
                    <th>Crime Scene Description</th>
                    <th>Crime Description</th>
                    <th>Crime Name</th>
                </tr><tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["Crime_ID"] . "</th><th>" . $row["Date"] . "</th><th>" . $row["Crime_Scene_Description"] . "</th><th>" . $row["Crime_Description"] . "</th><th>" . $row["Crime_Name"];
                }
                echo '</th></tr></table></div>';
            } else {
                echo "FATAL ERROR CRIME NOT FOUND.";
            }

            $caseSql = "SELECT * FROM Court_Case WHERE Court_Case.Case_ID = $case_id";
            $result = mysqli_query($db, $crimeSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                echo '<div class="w3-container" style="margin:2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Case Description</th>
                    <th>Case File</th>
                    <th>Result</th>
                    <th>Court Name</th>
                    <th>Opening Date</th>
                    <th>Status</th>
                </tr><tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["Case_Description"] . "</th><th>" . $row["Case_file"] . "</th><th>" . $row["Result"] . "</th><th>" . $row["Court_Name"] . "</th><th>" . $row["Open_Date"] . "</th><th>" . $row["Case_State"];
                    $_SESSION["caseState"] = $row["Case_State"];
                }
                echo '</th></tr></table></div>';
            } else {
                echo "FATAL ERROR CASE NOT FOUND.";
            }

            echo '<div class="w3-container" style="margin:2%">
                    <table class="w3-table-all w3-centered w3-hoverable">
                    <tr>
                        <th>Conciliator TC</th>
                        <th>Conciliator Name</th>
                        <th>Interpreter TC</th>
                        <th>Interpreter Name</th>
                        <th>Interpreter Language</th>
                        <th>Expert Witness TC</th>
                        <th>Expert Witness Name</th>
                        <th>Expert Witness Field</th>
                    </tr><tr>';
            if ($noConcil > 0) {
                $concilSql = "SELECT DISTINCT Citizen.TC_ID, Citizen.FullName FROM Citizen, Councils WHERE Councils.Case_ID = $case_id AND Citizen.TC_ID = Councils.Conciliator_ID";
                $result = mysqli_query($db, $concilSql);
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th>";
                    }
                } else {
                    echo "<th><a href=\"judge_assign_concil.php\">Assign Conciliator</a></th><th>-</th>";
                }
            } else {
                echo "<th><a href=\"judge_assign_concil.php\">Assign Conciliator</a></th><th>-</th>";
            }
            $interpSql = "SELECT * FROM Translates, Citizen, Works WHERE Translates.TC_ID = Works.TC_ID AND Works.Case_ID = $case_id AND Citizen.TC_ID = Works.TC_ID";
            $result = mysqli_query($db, $interpSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Language_Name"] . "</th>";
                }
            } else {
                echo "<th><a href=\"judge_assign_interp.php\">Assign Interpreter</a></th><th>-</th><th>-</th>";
            }
            $expertSql = "SELECT * FROM Citizen, Informs, Expert_Witness WHERE Expert_Witness.TC_ID = Informs.TC_ID AND Informs.Case_ID = $case_id AND Citizen.TC_ID = Informs.TC_ID";
            $result = mysqli_query($db, $expertSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Expertise_Field"] . "</th>";
                }
            } else {
                echo "<th><a href=\"judge_assign_expert.php\">Assign Expert Witness</a></th><th>-</th><th>-</th>";
            }
            echo '</tr></table></div>';

            echo '<div class="w3-container" style="margin:2%">
                    <table class="w3-table-all w3-centered w3-hoverable"> 
                <tr>
                    <th class= "w3-centered">Trial Dates</th></tr><tr>';
            $dateSql = "SELECT * FROM TakesPlaceOn WHERE Case_ID = $case_id ORDER BY T_Date DESC";
            $result = mysqli_query($db, $dateSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<th>' . $row["T_Date"] . '</th>';
                }
            }
            echo '</tr></table></div>';
        }
        ?>

        <?php
        if ($_SESSION["caseState"] == 'Closed') {
            echo '<div class="w3-display-container" style="margin-right:3%;margin-left:3%;"><form class="w3-display-right" action="judge_finalize.php" method="post"> <input disabled class="w3-input w3-round w3-border w3-blue-grey" type="submit" name="finalize" value="Case Closed" /></form>';
        } else {
            echo '<div class="w3-display-container" style="margin-right:3%;margin-left:3%;"><form class="w3-display-right" action="judge_finalize.php" method="post"> <input class="w3-input w3-round w3-border w3-indigo" type="submit" name="finalize" value="Finalize Case" /></form>';
        }

        if (isset($_SESSION["user_type"])) {
            $user = $_SESSION["user_type"];
            if ($user == 'Judge') {
                echo '<button class="w3-display-left w3-button w3-round w3-indigo" onclick="window.location.href = \'view_judge.php\';">Back</button></div><br>';
            }
        }
        echo '</div>';

        if (isset($_POST['Subbmit'])) {
			if (!isset($_POST['search']) || $_POST['search'] == '') {
				echo "<script type='text/javascript'>alert('Please Fill in The Required Fields');
			window.location='view_judge.php';
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
		if (isset($_POST['Subbmit'])) {
			$tc2 = $_POST['search'];
			if (is_numeric($tc2)) {
				$sql = "SELECT TC_ID FROM Citizen";
				$result = mysqli_query($db, $sql) or die('Error updating database: ' . mysql_connect_error($result));
				while ($table = mysqli_fetch_array($result)) {
					if ($tc2 == $table[0]) {
						$_SESSION['stc'] = $tc2;
						header("location: search_judge.php");
						exit;
					} else {
						echo "<script type='text/javascript'>alert('No citizen Found');
			window.location='view_judge.php';
			exit;			
			</script>";
					}
				}
			} else {
				echo "<script type='text/javascript'>alert('Please Enter a Valid TC');
			window.location='view_judge.php';
			exit;			
			</script>";
			}
		}
		if (isset($_POST['gavel'])) {

			echo "<script type='text/javascript'>
			window.location='gavel.php'; //TODO
			exit;			
			</script>";
		}
    } else {
        header('location: error.php');
    }
    ?>

</body>

</html>