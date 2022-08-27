<?php

// set parameters
$hostname = "localhost";
$username = "peter";
$password = "password";
$database = "water_level";

// connect to the database
$dbhandle = mysqli_connect($hostname, $username, $password, $database)
or die("ERROR: Could not connect. " . mysqli_connect_error());
echo "Connected to MySQL server $hostname<br>";

//select database
$selected = mysqli_select_db($dbhandle,$database)
or die("Could not find database $database");
echo "Database $database found<br>";

//execute query
$query = "SELECT level, date_time FROM level_log ORDER BY id DESC LIMIT 1"; 
$result = mysqli_query($dbhandle, $query);
$last_row = mysqli_fetch_row($result);
echo "Note: at 10cm the waterbladder is almost empty<p>";
echo "Showing last row: $last_row[0], $last_row[1]<p>";

?>

<!doctype html>
<html lang="eng">
<head>
<!--Setting the boxes using CSS-->
  <style>
    *{box-sizing: border-box;}
    .column {float: left; width: 50%; padding: 20px;} 
    .row:after {content: ""; display: table; clear: both;}
  </style>

    <meta charset=UTF-8">
    <title>Watermonitor Report</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['date_time', 'level'],

          <?php 
            // 24 * 4 = 96 entries per 24hr
            $select_query = "SELECT * FROM (
			     SELECT id, level, date_time FROM level_log ORDER BY id DESC LIMIT 96)
			     sub ORDER by id ASC"; 
            $query_result = mysqli_query($dbhandle, $select_query);

            if(mysqli_num_rows($query_result)> 0) {
              while ($row_val = mysqli_fetch_array($query_result)) {

            $date  = ($row_val['date_time']);
            $date2 = new DateTime($date);
            //$date3 = date_format($date2, 'Y,m,d,H,i,s');
            $date3 = date_format($date2, 'H.i');

                echo "['". $date3 ."',41 - '".$row_val['level']."'],";
              }
            }
          ?>

        ]);

        var options = {
            title: 'Watermonitor Day',
	    width: 1600, 
	    height: 500,

            hAxis: {
	      title: 'Time'
            },

	    vAxis: {
              title: 'Waterlevel',
	      viewWindow: {
	        min: 0,
	        max: 50 
	      },
	      ticks: [0, 10, 20, 30, 40, 50] // set the Y-axis values
	    }
        };

        var chart = new google.charts.Bar(document.getElementById('ColumnChart_Day'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['date_time', 'level'],

          <?php
            // 7 * 24 * 4 = 672 entries per 7d 
            $select_query = "SELECT * FROM (
                             SELECT id, level, date_time FROM level_log ORDER BY id DESC LIMIT 672)
                             sub ORDER by id ASC";
            $query_result = mysqli_query($dbhandle, $select_query);

            if(mysqli_num_rows($query_result)> 0) {
              while ($row_val = mysqli_fetch_array($query_result)) {

                $date  = ($row_val['date_time']);
                $date2 = new DateTime($date);
                //$date3 = date_format($date2, 'Y,m,d,H,i,s');
                $date3 = date_format($date2, 'i'); // take the minutes - take every hour with 00

                if ($date3 == "00") {
                  $date3 = date_format($date2, 'M.d'); // take the day
                  echo "['". $date3 ."',41 - '".$row_val['level']."'],";
                }
              }
            }
          ?>

        ]);

        var options = {
            title: 'Watermonitor Week',
            width: 1600,
            height: 500,

            hAxis: {
              title: 'Time'
            },

            vAxis: {
              title: 'Waterlevel',
              viewWindow: {
                min: 0,
                max: 50
              },
              ticks: [0, 10, 20, 30, 40, 50] // set the Y-axis values
            }
        };

        var chart = new google.charts.Bar(document.getElementById('ColumnChart_Week'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['date_time', 'level'],

          <?php
            // 30 * 24 * 4 = 2880 entries per 30d
            $select_query = "SELECT * FROM (
                             SELECT id, level, date_time FROM level_log ORDER BY id DESC LIMIT 2880)
                             sub ORDER by id ASC";
            $query_result = mysqli_query($dbhandle, $select_query);

            if(mysqli_num_rows($query_result)> 0) {
              while ($row_val = mysqli_fetch_array($query_result)) {

                $date  = ($row_val['date_time']);
                $date2 = new DateTime($date);
                //$date3 = date_format($date2, 'Y,m,d,H,i,s');
                $date3 = date_format($date2, 'H'); // take the hr - take 00 and 12 

                if ($date3 == "00" or $date3 == "12") {
                  $date3 = date_format($date2, 'M'); // take the month 
                  echo "['". $date3 ."',41 - '".$row_val['level']."'],";
                }
              }
            }
          ?>

        ]);

        var options = {
            title: 'Watermonitor Month',
            width: 1600,
            height: 500,

            hAxis: {
              title: 'Time'
            },

            vAxis: {
              title: 'Waterlevel',
              viewWindow: {
                min: 0,
                max: 50
              },
              ticks: [0, 10, 20, 30, 40, 50] // set the Y-axis values
            }
        };

        var chart = new google.charts.Bar(document.getElementById('ColumnChart_Month'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>


  </head>
  <body>
      <div class="row">
      <div class="column" id="ColumnChart_Day"></div>
      </div>
      <div class="row">
      <div class="column" id="ColumnChart_Week"></div>
      </div>
      <div class="row">
      <div class="column" id="ColumnChart_Month"></div>
      </div>
  </body>
</html>

<?php
//close the connection
mysqli_close($dbhandle);
?>

