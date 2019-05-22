<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>UYAP - Assign Expert Witness</title>
</head>

<body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
    <div style="margin-top:6%; text-align: center; margin-right:40%; margin-left:40%;">
        <?php
        include('config.php');
        $tc1 =     $_SESSION['tc'];
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
        echo '<div style="margin-top:6%; text-align: center; margin-right:40%; margin-left:40%;">';
        if ($_SESSION["user_type"] == 'Judge') {
            if ($_SESSION["caseState"] != 'Closed') {
                
                $caseState = $_SESSION["caseState"];
                $case_id = $_SESSION["CaseID"];

                $fieldsSql = 'SELECT DISTINCT Language_Name FROM Translates';
                $fieldResult = mysqli_query($db, $fieldsSql);

                echo '<form action="judge_assign_interp.php" method="post">';
                echo '<select name="lang" class="w3-select w3-border w3-round-xlarge w3-light-grey">';
                while ($row = mysqli_fetch_assoc($fieldResult)) {
                    echo '<option value="' . $row["Language_Name"] . '">' . $row["Language_Name"] . '</option>';
                }
                echo '</select>';
                echo '<div style="display:inline-block;"><br>';
                echo '<input type="submit" class="w3-input w3-border w3-round-large w3-indigo" value="Assign Interpreter">';
                echo '</div>';
                echo '<div style="display:inline-block; vertical-align:10%;">';
                echo '<button class="w3-button w3-round-large w3-indigo" onclick="window.location.href = \'caseview_judge.php\';"><strong>Back</strong></button>';
                echo '</div>';
                echo '</form>';
                if (isset($_POST["lang"])) {
                    $lang = $_POST["lang"];
                    $tc = $_SESSION["tc"];
                    $addSql = "SELECT TC_ID FROM Interpreter NATURAL JOIN Translates WHERE Language_Name = '$lang' AND TC_ID NOT IN (SELECT TC_ID FROM (SELECT * FROM Works NATURAL JOIN Court_Case) AS T NATURAL JOIN Translates WHERE IsClosed = 0 AND Language_name = '$lang' GROUP BY T.TC_ID HAVING count(T.TC_ID) >= 5) AND TC_ID != '$tc' ORDER BY rand()";
                    $result = mysqli_query($db, $addSql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $tc = $row["TC_ID"];
                        $insertSql = "INSERT INTO Works VALUES('$tc', '$case_id')";
                        $result = mysqli_query($db, $insertSql);
                        header('location: caseview_judge.php');
                    } else {
                        echo '<script> alert("No translators are available at the moment. Please try later.") </script>';
                    }
                }
            } else {
                echo 'ERROR';
            }
        } else {
            header('location: error.php');
        }
        ?>
    </div>
</body>

</html>