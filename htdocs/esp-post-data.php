<?php
  include_once('esp-database.php');

  // Keep this API Key value to be compatible with the ESP code provided in the project page. If you change this value, the ESP sketch needs to match
  $api_key_value = "";

  $api_key= $sensor_status = $wind_dir = $wind_speed = $ambnt_temp = $humidity = $pyranometer = $rain = $mod_temp = $barometer = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
      $sensor_status = test_input($POST["sensor_status"]);
      $wind_dir = test_input($POST["wind_dir"]);
      $wind_speed = test_input($POST["wind_speed"]);
      $ambnt_temp = test_input($POST["ambnt_temp"]);
      $humidity = test_input($POST["humidity"]);
      $pyranometer = test_input($POST["pyranometer"]);
      $rain = test_input($POST["rain"]);
      $mod_temp = test_input($POST["mod_temp"]);
      $barometer = test_input($POST["barometer"]);

      $result = insertReading($sensor_status, $wind_dir, $wind_speed, $ambnt_temp, $humidity, $pyranometer, $rain, $mod_temp, $barometer);
	  
      echo $result;
    }
    else {
      echo "Wrong API Key provided.";
    }
  }
  else {
    echo "No data posted with HTTP POST.";
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
