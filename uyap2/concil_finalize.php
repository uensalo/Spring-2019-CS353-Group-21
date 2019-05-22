<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head><title>UYAP Finalize</title></head>
    <body>
        <?php
            include("config.php");
            $case_id = $_SESSION["curCaseID"];
            $noLitigants = $_SESSION["noLitigants"];
            $noAccepted = $_SESSION["noAccepted"];
            $caseState = $_SESSION["caseState"];

            if($caseState != 'Closed' && ($noAccepted == $noLitigants)) {
                $endSql = "UPDATE Court_Case SET Isclosed = 1, Result = 'Agreed by Conciliation', Case_State = 'Closed' WHERE Case_Id = $case_id";
                $result = mysqli_query($db, $endSql);
                echo "Successfully finalized case.";
            } else if ($noAccepted != $noLitigants){
                echo "Failed to finalize case. Everybody needs to agree.";
            } else {
                echo "Case already closed.";
            }

            $_SESSION["curCaseID"] = -1;
            $_SESSION["noLitigants"] = -1;
            $_SESSION["noAccepted"] = -1;
        ?>
        <br/>
        <a href="caseview_concil.php">Back</a>

    </body>
</html>