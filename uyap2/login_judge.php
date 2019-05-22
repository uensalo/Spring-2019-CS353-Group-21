<?php 
    session_start();
?>
<?php
   include("config.php");
   $error = '';
   if(isset($_POST['judge_tc']) && isset($_POST['password'])) {
      
      $my_judge_tc = mysqli_real_escape_string($db,$_POST['judge_tc']);
      $my_pass = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT TC_ID, Judge_Password, FullName FROM Judge NATURAL JOIN Citizen WHERE TC_ID = '$my_judge_tc' and Judge_Password = '$my_pass';";
      $result = mysqli_query($db,$sql);
      $count = mysqli_num_rows($result);
      if($count == 1)
      {
         $row = mysqli_fetch_assoc($result);
         $_SESSION['tc'] = $my_judge_tc;
         $_SESSION['name'] = $row['FullName'];
         $_SESSION['user_type'] = 'Judge';
         $error = '';
         header("location: view_judge.php");//judge_case_view.php
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
    <title>UYAP2 - Judge Login</title>
</head>
   <body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
      <div style="text-align: center;">
          <img src="logo.png" class="w3-round">
          <h4>Login as <strong>Judge</strong></h4><br>
          <form action="" method="post" class="w3-container" style="margin: 0 auto; width:30%"><br>
            <label class="w3-text-indigo"><strong>TC NO:</strong></label><input type = "text" name = "judge_tc" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br>
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
