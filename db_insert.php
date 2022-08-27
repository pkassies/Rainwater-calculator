<?php
// Project Smart Water Tank
// Coded by PeterK

// To test this script
// call the script as an url via http

// Check MySQL connection
$link = mysqli_connect("localhost", "peter", "password", "water_level");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Setting variables
    date_default_timezone_set("Europe/Amsterdam");
//  $level="10";           // hardcoded entry to call by commandline - replace by next line to call from http
    $level=$_GET["level"]; // variable set by remote http call
    $t=time();
    $date_time= date('Y-m-d H:i:s', $t);

// Check variables
   echo "value     = $level<br>";
   echo "time      = $t<br>";
   echo "date_time = $date_time<br>";
 
// Attempt insert query execution
$sql="INSERT INTO `level_log`(`level`, `timestamp`, `date_time`)
    VALUES ('$level','$t', '$date_time')";

if(mysqli_query($link, $sql)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
?>
