<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>UYAP</title>
</head>

<body>
    <?php
    include("config.php");
    if ($_SESSION["user_type"] == 'Judge') {
        $caseState = $_SESSION["caseState"];
        $case_id = $_SESSION["CaseID"];
        if ($_SESSION["caseState"] != 'Closed') {
            $dateSql = "SELECT * FROM TakesPlaceOn WHERE $case_id = Case_ID ORDER BY T_Date DESC LIMIT 1";
            $result = mysqli_query($db, $dateSql);
            $row = mysqli_fetch_assoc($result);
            $latest_trial = new DateTime($row["T_Date"]);
            echo 'Latest trial date: ' . $latest_trial->format("Y-m-d");
            echo '<br/>';


            if (isset($_POST["trial_date"])) {
                if ($latest_trial < $_POST["trial_date"] && $latest_trial <= date('Y-m-d')) {
                    $new_date = $_POST["trial_date"];
                    $postSql = "INSERT INTO TakesPlaceOn VALUES('$case_id', '$new_date')";
                    mysqli_query($db, $postSql);
                    header('location: caseview_judge.php');
                } else if ($latest_trial < $_POST["trial_date"] &&  $latest_trial > date('Y-m-d') || ($_POST["trial_date"] > date('Y-m-d') && $latest_trial > $_POST["trial_date"])) {
                    $new_date = $_POST["trial_date"];
                    $str = $latest_trial->format("Y-m-d");
                    $postSql = "UPDATE TakesPlaceOn SET T_Date = '$new_date' WHERE $case_id = Case_ID AND T_Date = '$str'";
                    mysqli_query($db, $postSql);
                    header('location: caseview_judge.php');
                } else {
                    echo 'Postponed date must be later than the latest trial date.';
                }
            } else {
                echo 'Please choose a date.';
            }
            echo '<form action="judge_postpone.php" method="post">' . '<input type="date" name="trial_date">' . '<input type="submit" value="Postpone Trial">' . '</form>';
        } else {
            echo 'ERROR';
        }
    } else {
        header('location: error.php');
    }
    ?>
    <br />
    <a href="caseview_judge.php">Back</a>

</body>

</html>