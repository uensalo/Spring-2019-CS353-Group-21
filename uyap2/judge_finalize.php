<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>UYAP - Finalize Case</title>
</head>

<body>
    <?php
    include("config.php");
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
        $caseState = $_SESSION["caseState"];
        $case_id = $_SESSION["CaseID"];
        if (isset($_POST["verdict"]) && $caseState != 'Closed') {
            $verdict = $_POST["verdict"];
            $endSql = "UPDATE Court_Case SET IsClosed = 1, Result = '$verdict', Case_State = 'Closed' WHERE Case_Id = '$case_id'";
            $result = mysqli_query($db, $endSql);
            echo '<script>alert("Case closed.")</script>';
        }
    } else {
        header('location: error.php');
    }
    echo '</div>';

    ?>
    <br />

    <form action="judge_finalize.php" method="post">
        Verdict: <input type="text" name="verdict"><br>
        <input type="submit" value="Finalize Case">
    </form>
    <a href="caseview_judge.php">Back</a>

</body>

</html>