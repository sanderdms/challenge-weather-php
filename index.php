<?php
//@const API_KEY
require("secrets.php");
require("functions.php");

$cityDefined = false;
$resultsFound = false;
$inputValue = "";

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $userInput = filter_var(ucfirst(str_replace(" ", "-", trim($_GET['city']))), FILTER_SANITIZE_STRING);
    $cityDefined = true;
    $inputValue = $userInput;
    $apiResponse = getWeatherData($userInput);
    if ($apiResponse->cod != 200) {
        $msg = "City $userInput not found";
    } else {
        $msg = "Weather found for $userInput:";
        $resultsFound = true;
        $dailyData = groupByDay($apiResponse);
        $fullData= addDailyMetrics($dailyData);
        $chartData = generateMinMax($fullData);
        $chartLabels = generateDayLabels($dailyData);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Weather App</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
    <body>
        <section class="container mx-auto">
            <h1>Weather app</h1>
            <!--START FORM-->
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="GET">
                <input type="text" name="city" value="<?= $inputValue ?>" placeholder="Cityname>
                <input type="submit">
            </form>
            <!--END FORM-->
        </section>
        
        <!-- START CITY DEFINED -->
        <?php if ($cityDefined) : ?>
        <section class="container mx-auto">
    
            <?= $msg ?>
        <?php endif; ?>
        <!-- END CITY DEFINED -->
        <!-- START RESULTS FOUND -->
        <?php if ($resultsFound) : ?>
            <p>City: <?= $apiResponse->city->name ?></p>
            <p>Country:<?= $apiResponse->city->country?></p>
            <?php //include("chart.php") ?>
            <?php foreach($fullData as $day => $weather) : ?>
                <?php $metrics = array_pop($weather);?>
                <h1><?=$day?></h1>
                <p>Max temp: <?=$metrics["max"] ?></p>
                <p>Min temp: <?=$metrics["min"] ?></p>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
        <!-- END RESULTS FOUND -->
       
    </body>
</html>

