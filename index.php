<?php
//@const API_KEY
require("secrets.php");

$cityDefined = false;
$resultsFound = false;
$inputValue = "";

function getWeatherData($city = "Ghent")
{
    $requestURL = "https://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&APPID=" . API_KEY;
    $respons = file_get_contents($requestURL);
    return json_decode($respons);
}


// $responsdata = JSON object
function groupByDay($responsdata)
{
    $output = [];
    $list = $responsdata->list;
    foreach($list as $listitem){
        $day = date("N", $listitem->dt);
        $dowMap = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $day = $dowMap[$day];
        if(!array_key_exists($day, $output)){
            $output[$day] = [];
        }
        array_push($output[$day], $listitem);
    }
    return $output;
}

function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
 }

// $daily = array
function addDailyMetrics($daily){
    $output = $daily;
    foreach($daily as $day => $timeslot){
    $dailyMetrics=[
            "max" => round($timeslot[0]->main->temp_max, 2),
            "min" => round($timeslot[0]->main->temp_min,2),
        ];
        foreach($timeslot as $item){
            if(round($item->main->temp_min,2) < $dailyMetrics["min"]){
                $dailyMetrics["min"] = round($item->main->temp_min,2);
            }
            if(round($item->main->temp_max,2) > $dailyMetrics["max"]){
                $dailyMetrics["max"] = round($item->main->temp_max,2);
            }
        }
        array_push($output[$day], $dailyMetrics);
    }
    return $output;
}

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $userInput = ucfirst($_GET['city']);
    //sanitize
    $cityDefined = true;
    $inputValue = $userInput;
    $data = getWeatherData($userInput);
    if ($data->cod != 200) {
        $msg = "City $userInput not found";
    } else {
        $msg = "Weather found for $userInput:";
        $resultsFound = true;
        $dailyData = groupByDay($data);
        $fullData= addDailyMetrics($dailyData);
        
    }
}

?>

<html>

<body>
    <h1> Weather app</h1>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="GET">
        <input type="text" name="city" value="<?= $inputValue ?>" placeholder="Cityname">
        <input type="submit">
    </form>
    <?php if ($cityDefined) : ?>
        <?= $msg ?>
    <?php endif; ?>
    <?php if ($resultsFound) : ?>
        <p>City: <?= $data->city->name ?></p>
        <p>Country:<?= $data->city->country?></p>
        <?php foreach($fullData as $day => $weather) : ?>
            <?php 
            // get metrics from last key
            //$metrics = $weather[count($weather)-1];
            $metrics = array_pop($weather);
            ?>

            <h1><?=$day?></h1>
            <p>Max temp: <?=$metrics["max"] ?></p>
            <p>Min temp: <?=$metrics["min"] ?></p>

        <?php endforeach; ?>

    <?php endif; ?>
</body>

</html>