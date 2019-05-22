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
        if (isset($_SESSION["CaseID"]) && $_SESSION['caseState'] != 'Closed') {
            $caseState = $_SESSION["caseState"];
            $case_id = $_SESSION["CaseID"];
            $tc = $_SESSION["tc"];
            $addSql = "SELECT TC_ID FROM Conciliator WHERE TC_ID NOT IN (SELECT TC_ID FROM Councils NATURAL JOIN Court_Case WHERE Court_Case.IsClosed = 0 GROUP BY Conciliator_ID HAVING count(Conciliator_ID) >= 5) AND TC_ID != '$tc' ORDER BY rand()";
            $result = mysqli_query($db, $addSql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $tc_con = $row["TC_ID"];

                $lawyerSql = "SELECT * FROM Involved WHERE Case_ID = '$case_id'";
                $lawyerResult = mysqli_query($db, $lawyerSql);

                while ($lawyerRow = mysqli_fetch_assoc($lawyerResult)) {
                    $lawyerID = $lawyerRow["Lawyer_ID"];
                    $litigantID = $lawyerRow["Litigant_ID"];
                    if ($lawyerID === null) {
                        $insertSql = "INSERT INTO Councils VALUES('$tc_con', '$litigantID', '$case_id', NULL, 0)";
                    } else {
                        $insertSql = "INSERT INTO Councils VALUES('$tc_con', '$litigantID', '$case_id', '$lawyerID', 0)";
                    }
                    $result = mysqli_query($db, $insertSql);
                }
                header('location: caseview_judge.php');
            } else {
                echo '<script> alert("No conciliators are available at the moment. Please try later.") </script>';
            }
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