<!DOCTYPE HTML>
<?php
session_start();
?>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>UYAP2 - Error</title>
</head>

<body class="w3-sand">
    <?php
    include("config.php");
    $tc =     $_SESSION['tc'];
    $name = "SELECT FullName FROM Citizen WHERE TC_ID = '$tc'";
    $result = mysqli_query($db, $name) or die('Error updating database: ' . mysqli_connect_error($result));
    while ($table = mysqli_fetch_array($result)) {
        $name = $table[0];
        echo ("<div class=\"w3-top\"><div class=\"w3-bar w3-border w3-indigo w3-text-white\"> <a href=\"#\" class=\"w3-bar-item w3-button w3-padding-16\">
		<i class=\"material-icons\">person</i></a><div class=\"w3-bar-item w3-padding-16\">" . $name . "&nbsp" . $tc .  "</div><div class=\"w3-bar-item w3-right\"><form action='error.php' method='post'><input type='submit' class=\"w3-input w3-indigo w3-hover-grey\"type=\"submit\" style=\"margin: 0 auto;\" name='Log' value='Logout' style='width:65%; height:65%'></form></div><div class=\"w3-bar-item w3-right w3-padding-16\" style=\"display:inline-block; margin=1%;\"><strong>" . $_SESSION['user_type'] . "</strong></div></div></div>");
    }

    if (isset($_POST['Log'])) {
        session_destroy();
        header('location: index.php');
    }
    ?>
    <div class="w3-container w3-border w3-large w3-center-align" style="margin-top:10%">
        <div>
            <p>Unauthorized access. You do not have access to this page.</p>
        </div>
    </div>
</body>


</html>