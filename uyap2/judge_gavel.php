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
        if (isset($_POST['gavel'])) {
            $tc = $_SESSION['tc'];
            $gavel = $_POST['gavel'];
            $endSql = "UPDATE Judge SET Gavel_Name = '$gavel' WHERE TC_ID = '$tc'";
            $result = mysqli_query($db, $endSql);
            echo '<script>alert("Gavel Name Changed.")</script>';
            header('location: view_judge.php');
        }
    } else {
        header('location: error.php');
    }

    ?>
    <br />

    <form action="judge_gavel.php" method="post">
        New Gavel Name: <input type="text" name="gavel" ><br>
        <input type="submit" value="Change">
    </form>
    <a href="caseview_judge.php">Back</a>

</body>

</html>