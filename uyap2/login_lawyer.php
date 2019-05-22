<?php 
    session_start();
?>
<?php
   include("config.php");
   $error = '';
   if(isset($_POST['lawyer_tc']) && isset($_POST['password'])) {
      $my_lawyer_tc = mysqli_real_escape_string($db,$_POST['lawyer_tc']);
      $my_pass = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT TC_ID, Lawyer_Password, FullName FROM Lawyer NATURAL JOIN Citizen WHERE TC_ID = '$my_lawyer_tc' and Lawyer_Password = '$my_pass';";
      $result = mysqli_query($db,$sql);
      $count = mysqli_num_rows($result);
      if($count == 1)
      {
         
         $row = mysqli_fetch_assoc($result);
         $_SESSION['tc'] = $my_lawyer_tc;
         $_SESSION['name'] = $row['FullName'];
         $_SESSION['user_type'] = 'Lawyer';
         $error = '';
         header("location: view_lawyer.php");//lawyer_case_view.php
      }
      else
      {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>UYAP2 - Lawyer Login</title>
</head>
   <body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
      <div style="text-align: center;">
          <img src="logo.png" class="w3-round">
          <h4>Login as <strong>Lawyer</strong></h4><br>
          <form action="" method="post" class="w3-container" style="margin: 0 auto; width:30%"><br>
            <label class="w3-text-indigo"><strong>TC NO:</strong></label><input type = "text" name = "lawyer_tc" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br>
            <label class="w3-text-indigo"><strong>Password:</strong></label><input type = "password" name = "password" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br><br>
            <input type = "submit" value = " Submit "class="w3-input w3-border w3-indigo" style="margin: 0 auto; width: 40%"/>
         </form>
         <div style = "font-size:11px; color:#cc0000; margin-top:10px">
            <?php
                     echo $error;
            ?>
         </div>
      </div>
   </body>
</html>
