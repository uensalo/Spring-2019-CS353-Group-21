<?php
    include("config.php");
    session_start();
    if(isset($_SESSION["logined_citizen_tc"]))
    {
        //header("location: index.php");
       // exit;
    }
    if(isset($_SESSION["logined_concil_tc"]))
    {
       // header("location: index.php");
       // exit;
    } 
    if(isset($_SESSION["logined_interpreter_tc"]))
    {
       // header("location: index.php");
       // exit;
    }
    if(isset($_SESSION["logined_judge_tc"]))
    {
        //header("location: index.php");
      //  exit;
    } 
    if(isset($_SESSION["logined_lawyer_tc"]))
    {
      //  header("location: index.php");
       // exit;
    }


    $error = 'dsada';
    if(isset($_POST['tc']) && isset($_POST['f_name']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['b_date']) && isset($_POST['sex']) && isset($_POST['nationality']) && isset($_POST['city']) && isset($_POST['street_name']) && isset($_POST['street_address'])) 
    {
        $my_citizen_tc = mysqli_real_escape_string($db,$_POST['tc']);
        $my_name = mysqli_real_escape_string($db,$_POST['f_name']);
        $my_pass = mysqli_real_escape_string($db,$_POST['password']); 
        $my_confirm_pass = mysqli_real_escape_string($db,$_POST['confirm_password']); 
        $my_birth_date = mysqli_real_escape_string($db,$_POST['b_date']); 
        $my_sex = mysqli_real_escape_string($db,$_POST['sex']);  
        $my_city = mysqli_real_escape_string($db,$_POST['city']); 
        $my_street_name = mysqli_real_escape_string($db,$_POST['street_name']);
        $my_street_address = mysqli_real_escape_string($db,$_POST['street_address']);
        $my_nationality = mysqli_real_escape_string($db,$_POST['nationality']);
        $error = '';

        if( $my_sex == 'male')
            $my_sex = 1;
        else
            $my_sex = 0;

        if($my_pass != $my_confirm_pass || $my_pass == "" || $my_confirm_pass == "")
        {
             $error = "Passwords do not match!";

        }
        else
        {
            $address_exists = 1;
            $curr_address_id = -1;
            $sql1 = "SELECT Address_ID FROM Address WHERE City = '$my_city' and Street_Name = '$my_street_name' and Street_Address = '$my_street_address';";
            $result = mysqli_query($db,$sql1);
            $count = mysqli_num_rows($result);
            if ($count == 1)
            {
                while($row = mysqli_fetch_array($result))
                {
                    $curr_address_id = $row['Address_ID'];
                    $curr_address_id = $curr_address_id + 0;
                }
            }
            else
            {
                $address_exists = 0;
            }


            if($address_exists == 0)
            {
                $sql2 = "INSERT INTO Address VALUES (10,'$my_city','$my_street_name', '$my_street_address');";
                mysqli_query($db,$sql2);
            }

            $sql3 = "SELECT Address_ID FROM Address WHERE City = '$my_city' and Street_Name = '$my_street_name' and Street_Address = '$my_street_address';";
            $result = mysqli_query($db,$sql3);
            $count = mysqli_num_rows($result);
            if ($count == 1)
            {
                while($row = mysqli_fetch_array($result))
                {
                    $curr_address_id = $row['Address_ID'];
                    $curr_address_id = $curr_address_id + 0;
                }
            }


            $sql4 = "INSERT INTO Citizen VALUES ('$my_citizen_tc', '$my_name', '$my_birth_date', '$my_sex', '$my_nationality', '$curr_address_id', '$my_pass');";

            if (mysqli_query($db, $sql4))
            {
                header("location: index.php");
                exit;
            } 
            else
            {
                echo "Error: " . $sql4 . "<br>" . mysqli_error($db);
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles.css">
  <title>UYAP2 - Signup</title>
</head>
<body class="w3-sand" style="margin-top: 1%; margin-bottom: 3%;">
  <div style="text-align: center;">
    <img src="logo.png" class="w3-round">
    <h4>Create A New Account</h4>
    <form action="" method="post" class="w3-container" style="margin: 0 auto; width:30%">
      <label class="w3-text-indigo"><strong>TC:</strong></label>
      <input type="number" name="tc" class="w3-input w3-border w3-round-xlarge w3-light-grey" /><br>
      <label class="w3-text-indigo"><strong>Full Name:</strong></label>
      <input type="text" name="f_name" class="w3-input w3-border w3-round-xlarge w3-light-grey" /><br>
      <label class="w3-text-indigo"><strong>Password:</strong></label>
      <input type="password" name="password" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br>
      <label class="w3-text-indigo"><strong>Confirm Password:</strong></label>
      <input type="password" name="confirm_password" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br>
      <label class="w3-text-indigo"><strong>Birth Date:</strong></label>
      <input type="date" name="b_date" class="w3-input w3-border w3-round-xlarge w3-light-grey"/><br>
      <label class="w3-text-indigo"><strong>Sex:</strong></label>
      <input type="radio" name="sex" value="male" checked class=""> Male </input>
      <input type="radio" name="sex" value="female" class=""> Female </input>
      <br><br>
      <label class="w3-text-indigo"><strong>Nationality:</strong></label><br>
      <select name="nationality" class="w3-select w3-border w3-round-xlarge w3-light-grey" style="width:60%">
        <option value="turkey">Turkey</option>
        <option value="england">England</option>
        <option value="germany">Germany</option>
        <option value="north_korea">North Korea</option>
      </select><br><br><br>
      <h5 class="w3-text-indigo"><strong>Address</strong></h5>
      <label class="w3-text-indigo"><strong>City:</strong></label><br>
      <select name="city" class="w3-select w3-border w3-round-xlarge w3-light-grey" style="width:30%">
        <option value="istanbul">İstanbul</option>
        <option value="ankara">Ankara</option>
        <option value="izmir">İzmir</option>
        <option value="adana">Adana</option>
      </select><br>
      <label class="w3-text-indigo"><strong>Street Name:</strong></label><br>
      <input type="text" name="street_name" class="w3-input w3-border w3-round-xlarge w3-light-grey"/>
      <label class="w3-text-indigo"><strong>Street Address:</strong></label><br>
      <textarea name="street_address" rows="3" cols="30" class="w3-textarea w3-border w3-round-xlarge w3-light-grey"></textarea><br><br>
      <input type="submit" class="w3-input w3-border w3-indigo" value=" Sign up " style="margin: 0 auto; width: 40%" />
    </form>
  </div>
</body>
</html>