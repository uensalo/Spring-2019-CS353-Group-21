<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>UYAP</title>
</head>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>UYAP2 - Lawyer Case View</title>
</head>

<body>
    <?php
    include("config.php");
    $tc1 =     $_SESSION['tc'];
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
        </div>
        ";
    }
    echo '<div class="w3-container" style="margin-top:5%;" margin-left:5%; margin-right:5%;>';
    if ($_SESSION['user_type'] == 'Lawyer') {
        if (isset($_SESSION["CaseID"])) {
            $case_id = $_SESSION["CaseID"];

            $concilSql = "SELECT * FROM Councils WHERE Case_ID = $case_id";
            $concilResult = mysqli_query($db, $concilSql);
            $noConcil = mysqli_num_rows($concilResult);

            $judgeSql = "SELECT * FROM Judges, Citizen, Judge WHERE Case_ID = $case_id AND Judges.TC_ID = Citizen.TC_ID AND Judge.TC_ID = Judges.TC_ID";
            $result = mysqli_query($db, $judgeSql);

            if (mysqli_num_rows($result) > 0) {
                echo '<div class="w3-container" style="margin: 2%">
                <table class="w3-table-all w3-centered w3-hoverable">
                <tr>
                    <th>Case ID</th>
                    <th>Judge TC</th>
                    <th>Judge Name</th>
                    <th>Gavel Name</th>
                </tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><th>" . $case_id . "</th><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Gavel_Name"] . "</th></tr>";
                }
            } else {
                echo "DUNYANIN SONU GELDİ. BUZULLAR BUZULLAR. HERYER OLDU BUZ GİBİ. BUZULLAR BUZULLAR.";
            }
            echo '</table></div>';
            
            $tcs;
            $i = 0;
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
                    <th>Get Case</th>
                </tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $suspectTC = $row["TC_ID"];
                    $lawyerSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Involved.Litigant_ID = $suspectTC AND Citizen.TC_ID = Involved.Lawyer_ID AND Involved.Role = \"Suspect\"";
                    $lawyerResult = mysqli_query($db, $lawyerSql);
                    $rowLawyer = mysqli_fetch_assoc($lawyerResult);

                    if ($rowLawyer != null) {
                        echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>" . $row["Lawyer_ID"] . "</th><th>" . $rowLawyer["FullName"] . "</th></tr>";
                    } else {
                        echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>-</th><th>-</th>";
                        echo "<th><form action='caseview_lawyer.php' method='post'>";
                        echo "<input type=\"submit\" name=\"action" . $i . "\" value='Get Case'>";
                        echo "</th></form></tr>";
                        $val = 'action' . strval($i);
                        $tcs[$val] = $row["TC_ID"];
                        $i++;
                    }
                }
                echo '</table></div>';
            } else {
                echo "FATAL ERROR SUSPECT NOT FOUND.";
            }
            $ct = $i;
            for ($i = 0; $i < $ct; $i++) {
                $val = 'action' . strval($i);
                if (isset($_POST[$val])) {
                    $tc = $_SESSION["tc"];
                    $suspectTC = $tcs[$val];
                    $repSql = "INSERT INTO Represents VALUES('$suspectTC', '$tc');";
                    $invSql = "UPDATE Involved SET Lawyer_ID = '$tc' WHERE Case_ID = '$case_id' AND Litigant_ID = '$suspectTC'";
                    mysqli_query($db, $repSql);
                    mysqli_query($db, $invSql);
                    header("Refresh:0");
                }
            }
            $victimSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Citizen.TC_ID = Involved.Litigant_ID AND Involved.Role = \"Victim\"";
            $result = mysqli_query($db, $victimSql);
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
            </tr>';
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $victimTC = $row["TC_ID"];
                    $lawyerSql = "SELECT * FROM Citizen, Involved WHERE Involved.Case_ID = $case_id AND Involved.Litigant_ID = $victimTC AND Citizen.TC_ID = Involved.Lawyer_ID AND Involved.Role = \"Victim\"";
                    $lawyerResult = mysqli_query($db, $lawyerSql);
                    $rowLawyer = mysqli_fetch_assoc($lawyerResult);
                    echo "<tr><th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Birthdate"] . "</th><th>" . $row["Biological_Sex"] . "</th><th>" . $row["Nationality"] . "</th><th>" . $row["Lawyer_ID"] . "</th><th>" . $rowLawyer["FullName"] . "</th></tr>";
                }
            } else {
                echo "FATAL ERROR VICTIM NOT FOUND.";
            }
            echo '</table></div>';

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
                    echo "<th>-</th><th>-</th>";
                }
            } else {
                echo "<th>-</th><th>-</th>";
            }
            $interpSql = "SELECT * FROM Translates, Citizen, Works WHERE Translates.TC_ID = Works.TC_ID AND Works.Case_ID = $case_id AND Citizen.TC_ID = Works.TC_ID";
            $result = mysqli_query($db, $interpSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Language_Name"] . "</th>";
                }
            } else {
                echo "<th>-</th><th>-</th><th>-</th>";
            }
            $expertSql = "SELECT * FROM Citizen, Informs, Expert_Witness WHERE Expert_Witness.TC_ID = Informs.TC_ID AND Informs.Case_ID = $case_id AND Citizen.TC_ID = Informs.TC_ID";
            $result = mysqli_query($db, $expertSql);
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<th>" . $row["TC_ID"] . "</th><th>" . $row["FullName"] . "</th><th>" . $row["Expertise_Field"] . "</th>";
                }
            } else {
                echo "<th>-</th><th>-</th><th>-</th>";
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
        } else {
            // TO DO
        }

        if (isset($_SESSION["user_type"])) {
            $user = $_SESSION["user_type"];
            if ($user == 'Lawyer') {
                echo '<a href="search_lawyer.php">Back</a>';
            }
        }
    } else {
        header('location: error.php');
    }
    ?>
</body>

</html>