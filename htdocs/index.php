<?php
    include_once('esp-database.php');
    if (isset($_GET["readingsCount"])){
      $data = $_GET["readingsCount"];
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $readings_count = $_GET["readingsCount"];
    }
    // default readings count set to 20
    else {
      $readings_count = 20;
    }

    $last_reading = getLastReadings();
    $last_reading_wind = $last_reading["wind_speed"];
    $last_reading_rain = $last_reading["rain"];
    $last_reading_temp = $last_reading["ambnt_temp"];
    $last_reading_humi = $last_reading["humidity"];
    $last_reading_time = $last_reading["reading_time"];

    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));

    $min_wind = minReading($readings_count, 'wind_speed');
    $max_wind = maxReading($readings_count, 'wind_speed');
    $avg_wind = avgReading($readings_count, 'wind_speed');

    $min_rain = minReading($readings_count, 'rain');
    $max_rain = maxReading($readings_count, 'rain');
    $avg_rain = avgReading($readings_count, 'rain');

    $min_temp = minReading($readings_count, 'ambnt_temp');
    $max_temp = maxReading($readings_count, 'ambnt_temp');
    $avg_temp = avgReading($readings_count, 'ambnt_temp');

    $min_humi = minReading($readings_count, 'humidity');
    $max_humi = maxReading($readings_count, 'humidity');
    $avg_humi = avgReading($readings_count, 'humidity');
?>

<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="refresh" content="5">
        <link rel="stylesheet" type="text/css" href="esp-style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <header class="header">
        <h1>📊 ESP Weather Station</h1>
        <form method="get">
            <input type="number" name="readingsCount" min="1" placeholder="Number of readings (<?php echo $readings_count; ?>)">
            <input type="submit" value="UPDATE">
        </form>
    </header>
<body>
    <p>Last reading: <?php echo $last_reading_time; ?></p>
    <section class="content">
	    <div class="box gauge--1">
	    <h3>WIND SPEED</h3>
              <div class="mask">
			  <div class="semi-circle"></div>
			  <div class="semi-circle--mask"></div>
			</div>
		    <p style="font-size: 30px;" id="windspeed">--</p>
		    <table cellspacing="5" cellpadding="5">
		        <tr>
		            <th colspan="3">Wind Speed <?php echo $readings_count; ?> readings</th>
	            </tr>
		        <tr>
		            <td>Min</td>
                    <td>Max</td>
                    <td>Average</td>
                </tr>
                <tr>
                    <td><?php echo $min_wind['min_amount']; ?> Kmph</td>
                    <td><?php echo $max_wind['max_amount']; ?> Kmph</td>
                    <td><?php echo round($avg_wind['avg_amount'], 2); ?> Kmph</td>
                </tr>
            </table>
        </div>
        <div class="box gauge--2">
	    <h3>RAIN</h3>
              <div class="mask">
			  <div class="semi-circle"></div>
			  <div class="semi-circle--mask"></div>
			</div>
		    <p style="font-size: 30px;" id="rain">--</p>
		    <table cellspacing="5" cellpadding="5">
		        <tr>
		            <th colspan="3">Rain <?php echo $readings_count; ?> readings</th>
	            </tr>
		        <tr>
		            <td>Min</td>
                    <td>Max</td>
                    <td>Average</td>
                </tr>
                <tr>
                    <td><?php echo $min_rain['min_amount']; ?> mm</td>
                    <td><?php echo $max_rain['max_amount']; ?> mm</td>
                    <td><?php echo round($avg_rain['avg_amount'], 2); ?> mm</td>
                </tr>
            </table>
        </div>
        <div class="box gauge--3">
	    <h3>TEMPERATURE</h3>
              <div class="mask">
			  <div class="semi-circle"></div>
			  <div class="semi-circle--mask"></div>
			</div>
		    <p style="font-size: 30px;" id="temp">--</p>
		    <table cellspacing="5" cellpadding="5">
		        <tr>
		            <th colspan="3">Temperature <?php echo $readings_count; ?> readings</th>
	            </tr>
		        <tr>
		            <td>Min</td>
                    <td>Max</td>
                    <td>Average</td>
                </tr>
                <tr>
                    <td><?php echo $min_temp['min_amount']; ?> &deg;C</td>
                    <td><?php echo $max_temp['max_amount']; ?> &deg;C</td>
                    <td><?php echo round($avg_temp['avg_amount'], 2); ?> &deg;C</td>
                </tr>
            </table>
        </div>
        <div class="box gauge--4">
            <h3>HUMIDITY</h3>
            <div class="mask">
                <div class="semi-circle"></div>
                <div class="semi-circle--mask"></div>
            </div>
            <p style="font-size: 30px;" id="humi">--</p>
            <table cellspacing="5" cellpadding="5">
                <tr>
                    <th colspan="3">Humidity <?php echo $readings_count; ?> readings</th>
                </tr>
                <tr>
                    <td>Min</td>
                    <td>Max</td>
                    <td>Average</td>
                </tr>
                <tr>
                    <td><?php echo $min_humi['min_amount']; ?> %</td>
                    <td><?php echo $max_humi['max_amount']; ?> %</td>
                    <td><?php echo round($avg_humi['avg_amount'], 2); ?> %</td>
                </tr>
            </table>
        </div>
    </section>
<?php
    echo   '<h2> View Latest ' . $readings_count . ' Readings</h2>
            <table cellspacing="5" cellpadding="5" id="tableReadings">
                <tr>
                    <th>ID</th>
                    <th>Sensor Status</th>
                    <th>Wind Dir</th>
                    <th>Wind Speed</th>
                    <th>Ambient Temp</th>
                    <th>Humidity</th>
                    <th>Pyranometer</th>
                    <th>Rain</th>
                    <th>Mod Temp</th>
                    <th>Barometer</th>
                    <th>TimeStamp</th>
                </tr>';

    $result = getAllReadings($readings_count);
        if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row_id = $row["id"];
            $row_sensor_status = $row["sensor_status"];
            $row_wind_dir = $row["wind_dir"];
            $row_wind_speed = $row["wind_speed"];
            $row_ambnt_temp = $row["ambnt_temp"];
            $row_humidity = $row["humidity"];
            $row_pyranometer = $row["pyranometer"];
            $row_rain = $row["rain"];
            $row_mod_temp = $row["mod_temp"];
            $row_barometer = $row["barometer"];
            $row_reading_time = $row["reading_time"];
            // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
            //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
            // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
            //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));

            echo '<tr>
                    <td>' . $row_id . '</td>
                    <td>' . $row_sensor_status . '</td>
                    <td>' . $row_wind_dir . '</td>
                    <td>' . $row_wind_speed . '</td>
                    <td>' . $row_ambnt_temp . '</td>
                    <td>' . $row_humidity . '</td>
                    <td>' . $row_pyranometer . '</td>
                    <td>' . $row_rain . '</td>
                    <td>' . $row_mod_temp . '</td>
                    <td>' . $row_barometer . '</td>
                    <td>' . $row_reading_time . '</td>
                  </tr>';
        }
        echo '</table>';
        $result->free();
    }
?>

<script>
    var wind_v = <?php echo $last_reading_wind; ?>;
    var rain_v = <?php echo $last_reading_rain; ?>;
    var temp_v = <?php echo $last_reading_temp; ?>;
    var humi_v = <?php echo $last_reading_humi; ?>;
    setWindSpeed(wind_v);
    setRain(rain_v);
    setTemperature(temp_v);
    setHumidity(humi_v);

    function setWindSpeed(curVal){
    	var minWind = 0.0;
    	var maxWind = 120.0;

    	var newVal = scaleValue(curVal, [minWind, maxWind], [0, 180]);
    	$('.gauge--1 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#windspeed").text(curVal + ' Kmph');
    }

    function setRain(curVal){
    	var minRain = 0;
    	var maxRain = 300;

    	var newVal = scaleValue(curVal, [minRain, maxRain], [0, 180]);
    	$('.gauge--2 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#rain").text(curVal + ' mm');
    }

    function setTemperature(curVal){
    	//set range for Temperature in Celsius -5 Celsius to 38 Celsius
    	var minTemp = -5.0;
    	var maxTemp = 60.0;

    	var newVal = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);
    	$('.gauge--3 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#temp").text(curVal + ' ºC');
    }

    function setHumidity(curVal){
    	//set range for Humidity percentage 0 % to 100 %
    	var minHumi = 0;
    	var maxHumi = 100;

    	var newVal = scaleValue(curVal, [minHumi, maxHumi], [0, 180]);
    	$('.gauge--4 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#humi").text(curVal + ' %');
    }

    function scaleValue(value, from, to) {
        var scale = (to[1] - to[0]) / (from[1] - from[0]);
        var capped = Math.min(from[1], Math.max(from[0], value)) - from[0];
        return ~~(capped * scale + to[0]);
    }
</script>
</body>
</html>