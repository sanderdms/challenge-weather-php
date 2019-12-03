<?php
//@const API_KEY
require("secrets.php");
require("functions.php");

$cityDefined = false;
$resultsFound = false;
$inputValue = "";

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
        $chartData = generateMinMax($fullData);
        $chartLabels = generateDayLabels($dailyData);
    }
}
?>

<html>
    <body>
        <h1>Weather app</h1>
        <!--START FORM-->
        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="GET">
            <input type="text" name="city" value="<?= $inputValue ?>" placeholder="Cityname">
            <input type="submit">
        </form>
        <!--END FORM-->
        <!-- START CITY DEFINED -->
        <?php if ($cityDefined) : ?>
            <?= $msg ?>
        <?php endif; ?>
        <!-- END CITY DEFINED -->
        <!-- START RESULTS FOUND -->
        <?php if ($resultsFound) : ?>
            <p>City: <?= $data->city->name ?></p>
            <p>Country:<?= $data->city->country?></p>
            <?php include("chart.php") ?>
            <?php foreach($fullData as $day => $weather) : ?>
                <?php $metrics = array_pop($weather);?>
                <h1><?=$day?></h1>
                <p>Max temp: <?=$metrics["max"] ?></p>
                <p>Min temp: <?=$metrics["min"] ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- END RESULTS FOUND -->
    </body>
</html>