<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <p>

        <form id='test' action="lawyer_new_case.php" method="post">
            <div>
                <table id="form_table1" style="display: inline-block;" rules=none>
                    <tr>
                        <th>Victim TC <button type="button" class='btn btn-success addmore1'>+</button></th>
                    </tr>
                    <tr>
                        <td><input class="form-control" type='number' name='v_TC[]' /></td>
                    </tr>
                </table></br>
                <table id="form_table2" style="display: inline-block;" rules=none>
                    <tr>
                        <th>Suspect TC <button type="button" class='btn btn-success addmore2'>+</button> <br></th>
                    </tr>
                    <tr class='case'>
                        <td><input class="form-control" type='number' name='s_TC[]' /></td>
                    </tr>
                </table>
            </div>
            <label>Case Description:</label>
            <textarea name="case_description" rows="10" cols="50"></textarea>
            <br /><br />
            <label>Case File:</label>
            <input type="text" name="case_file" class="box" /><br />
            <label>Crime Name:</label>
            <input type="text" name="crime_name" class="box" /><br />
            <label>Crime Date:</label>
            <input type="date" name="c_date" class="box" /><br /><br />
            <label>Crime Description:</label>
            <textarea name="crime_description" rows="3" cols="50"></textarea>
            <br /><br />
            <label>Crime Scene Description:</label>
            <textarea name="crime_scene_description" rows="3" cols="50"></textarea>
            <br /><br />


            <br><label>Crime City:</label>
            <select name="city">
                <option value="istanbul">İstanbul</option>
                <option value="ankara">Ankara</option>
                <option value="izmir">İzmir</option>
                <option value="adana">Adana</option>
            </select><br>

            <label>Crime Street Name:</label>
            <input type="text" name="street_name" class="box" /><br />
            <label>Crime Street Address:</label>
            <textarea name="street_address" rows="3" cols="30"></textarea>
            <br /><br />

            <input type="submit" name="submit" value="Submit" class="btn btn-info">
        </form>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $(document).ready(function() {
                $(".addmore1").on('click', function() {
                    var data1 = "<tr class='case'> <td><input class='form-control' type='number' name='v_TC[]' /></td></tr>";
                    $('#form_table1').append(data1);
                });
                $(".addmore2").on('click', function() {
                    var data2 = "<tr class='case'><td><input class='form-control' type='number' name='s_TC[]'/></td></tr>";
                    $('#form_table2').append(data2);
                });
            });
        </script>
    </p>
</body>

</html>

<?php
include("config.php");
if ($_SESSION['user_type'] == 'Lawyer') {
    /*
    $sql = "SELECT * FROM Involved;";
    $result = mysqli_query($db,$sql);
    echo "<br> <br> All accounts: <br> <br>";
    while($row = mysqli_fetch_array($result))
    {
        echo 'Litigant_ID: ', $row['Litigant_ID'], '  ','Case_ID: ', $row['Case_ID'], '  ','Street_Name: ', $row['Street_Name'], '  ','Street_Adress: ', $row['Street_Address'], '  ';
        echo "<br>";
    }*/

    $v_TC = $_POST['v_TC'];
    $s_TC = $_POST['s_TC'];

    $totalName1 = sizeof($v_TC);
    $totalName2 = sizeof($s_TC);


    $case_description = $_POST['case_description'];
    $case_file = $_POST['case_file'];
    $crime_name = $_POST['crime_name'];
    $c_date = $_POST['c_date'];
    $crime_description = $_POST['crime_description'];
    $crime_scene_description = $_POST['crime_scene_description'];
    $city = $_POST['city'];
    $street_name = $_POST['street_name'];
    $street_address = $_POST['street_address'];

    $valid_v_count = 0;
    $valid_s_count = 0;
    for ($i = 0; $i < $totalName1; $i++) {
        $name = $v_TC[$i];
        if (empty($name) || strlen((string)$name) != 11) {
            unset($v_TC[$i]);
        } else {
            $valid_v_count++;
        }
    }
    for ($i = 0; $i < $totalName2; $i++) {
        $name = $s_TC[$i];
        if (empty($name) || strlen((string)$name) != 11) {
            unset($s_TC[$i]);
        } else {
            $valid_s_count++;
        }
    }


    $v_TC = array_unique($v_TC);
    $s_TC = array_unique($s_TC);

    $v_TC = array_values($v_TC);
    $s_TC = array_values($s_TC);

    $intersect_array = array_intersect($v_TC, $s_TC);


    if (!empty($case_description) && !empty($case_file) && !empty($crime_name) && !empty($c_date) && !empty($crime_description) && !empty($crime_scene_description) && !empty($city) && !empty($street_name) && !empty($street_address) && ($valid_s_count > 0) && ($valid_v_count > 0) && !(count($intersect_array) > 0)) {
        $my_case_description = mysqli_real_escape_string($db, $case_description);
        $my_case_file = mysqli_real_escape_string($db, $case_file);
        $my_crime_name = mysqli_real_escape_string($db, $crime_name);
        $my_c_date = mysqli_real_escape_string($db, $c_date);
        $my_crime_description = mysqli_real_escape_string($db, $crime_description);
        $my_crime_scene_description = mysqli_real_escape_string($db, $crime_scene_description);
        $my_city = mysqli_real_escape_string($db, $city);
        $my_street_name = mysqli_real_escape_string($db, $street_name);
        $my_street_address = mysqli_real_escape_string($db, $street_address);

        //GETS ADDRESS INDEX AND ADDS IT IF ADDRESS DNE
        $address_exists = 1;
        $curr_address_id = -1;
        $sql1 = "SELECT Address_ID FROM Address WHERE City = '$my_city' and Street_Name = '$my_street_name' and Street_Address = '$my_street_address';";
        $result = mysqli_query($db, $sql1);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                $curr_address_id = $row['Address_ID'];
                $curr_address_id = $curr_address_id + 0;
            }
        } else {
            $address_exists = 0;
        }


        if ($address_exists == 0) {

            $sql2 = "INSERT INTO Address(City, Street_Name,Street_Address) VALUES ('$my_city','$my_street_name', '$my_street_address');";
            mysqli_query($db, $sql2);
        }

        $sql3 = "SELECT Address_ID FROM Address WHERE City = '$my_city' and Street_Name = '$my_street_name' and Street_Address = '$my_street_address';";
        $result = mysqli_query($db, $sql3);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                $curr_address_id = $row['Address_ID'];
                $curr_address_id = $curr_address_id + 0;
            }
        }


        $sql4 = "INSERT INTO Crime(Date, Crime_Scene_Description, Crime_Description, Crime_Name) VALUES ('$my_c_date','$my_crime_scene_description', '$my_crime_description', '$my_crime_name');";
        mysqli_query($db, $sql4);

        //Getting new inserted crime id
        $cur_crime_id = -1;
        $sql5 = "SELECT Crime_ID FROM Crime WHERE Date = '$my_c_date' and Crime_Scene_Description = '$my_crime_scene_description' and Crime_Description = '$my_crime_description' and Crime_Name = '$my_crime_name';";
        $result = mysqli_query($db, $sql5);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                $cur_crime_id = $row['Crime_ID'];
                $cur_crime_id = $cur_crime_id + 0;
            }
        }

        $sql6 = "INSERT INTO Committed_At VALUES ('$curr_address_id','$cur_crime_id');";
        mysqli_query($db, $sql6);

        $trial_date = date('Y-m-d', time() + 864000);
        $open_date = date('Y-m-d');
        $open_lawyer_tc = $_SESSION['tc'];

        $sql10 = "INSERT IGNORE INTO Trial_Date VALUES ('$trial_date');";
        mysqli_query($db, $sql10);



        $cur_court_name = '';

        $sql7 = "SELECT Court_Name FROM Court WHERE Court_Name NOT IN (SELECT Court_Name FROM Court_Case WHERE Court_Case.IsClosed = 0 GROUP BY Court_Name HAVING count(Court_Name) >= 20) ORDER BY rand()";
        $result = mysqli_query($db, $sql7);
        $row = mysqli_fetch_assoc($result);
        $cur_court_name = $row["Court_Name"];


        $sql8 = "INSERT INTO Court_Case(Case_Description, Case_file, IsClosed, Result, Court_Name, Open_Date, Case_State, Crime_ID) VALUES('$my_case_description','$my_case_file', 0, '','$cur_court_name','$open_date','On-Going','$cur_crime_id');";
        mysqli_query($db, $sql8);

        $cur_case_id = -1;
        $sql13 = "SELECT Case_ID FROM Court_Case WHERE Case_Description = '$my_case_description' AND Case_file = '$my_case_file' AND IsClosed = 0 AND Result = '' AND Court_Name = '$cur_court_name' AND Open_Date = '$open_date' AND Case_State = 'On-Going' AND Crime_ID = '$cur_crime_id';";
        $result = mysqli_query($db, $sql13);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_array($result)) {
                $cur_case_id = $row['Case_ID'];
                $cur_case_id = $cur_case_id + 0;
            }
        }

        $sql14 = "INSERT  INTO TakesPlaceOn VALUES ('$cur_case_id','$trial_date');";
        mysqli_query($db, $sql14);


        for ($i = 0; $i < $valid_v_count; $i++) {
            $cur_v = $v_TC[$i];
            $sql9 = "INSERT IGNORE INTO Litigant VALUES ('$cur_v');";
            mysqli_query($db, $sql9);

            $sql11 = "INSERT IGNORE INTO Represents VALUES ('$cur_v', '$open_lawyer_tc');";
            mysqli_query($db, $sql11);

            $sql12 = "INSERT INTO Involved VALUES ('$open_lawyer_tc', '$cur_v', 'Victim', '$cur_case_id');";
            mysqli_query($db, $sql12);
        }

        for ($i = 0; $i < $valid_s_count; $i++) {
            $cur_s = $s_TC[$i];
            $sql15 = "INSERT IGNORE INTO Litigant VALUES ('$cur_s');";
            mysqli_query($db, $sql15);
            /*
          $sql17 = "INSERT IGNORE INTO Represents VALUES ('$cur_s', 50000);";
          mysqli_query($db,$sql17);*/

            $sql16 = "INSERT INTO Involved VALUES (NULL,'$cur_s', 'Suspect', '$cur_case_id');";
            if (mysqli_query($db, $sql16))
                echo "YAAY2 <br/>";
        }

        $sql17 = "SELECT TC_ID FROM Judge WHERE TC_ID NOT IN (SELECT TC_ID FROM Judges NATURAL JOIN Court_Case WHERE IsClosed = 0 GROUP BY TC_ID HAVING count(TC_ID) >= 5) ORDER BY rand()";
        $result = mysqli_query($db, $sql17);
        $row = mysqli_fetch_assoc($result);
        $cur_judge = $row["TC_ID"];

        $sql18 = "INSERT  INTO Judges VALUES ('$cur_judge','$cur_case_id');";
        mysqli_query($db, $sql18);

        //header("location: welcome.php"); main page of lawyer

    }
} else { 
    header('location: error.php');
}

?>