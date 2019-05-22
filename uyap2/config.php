<?php
   define('DB_SERVER', '139.179.11.31');
   define('DB_USERNAME', 'doga.oruc');
   define('DB_PASSWORD', 'N64Bt4PV');
   define('DB_DATABASE', 'doga_oruc');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   if($db === false){
      echo die("ERROR: Could not connect. " . mysqli_connect_error());
   } 
?>