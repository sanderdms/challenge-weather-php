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
        $msg = "Weather 5 day forecast for <b>".$apiResponse->city->name."</b>, ".$apiResponse->city->country;
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
    <title>Weather</title>
    <link href="output.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
</head>

<body class="bg-gray-300">
    <!-- START CONTAINER -->    
    <div class="container mx-auto mt-5">
        <section class="px-5">
             <!--START FORM-->
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="GET" class="w-full flex shadow-lg" autocomplete="off">
                <input type="text" name="city" value="<?=$inputValue?>" placeholder="Cityname"
                    class="text-2xl border-b-4 border-blue-800 w-7/12 py-4 px-4 text-blue-700 leading-tight focus:outline-none bg-white lg:w-9/12">
                <input type="submit" class="lg:w-3/12 w-5/12 py-4 text-white bg-blue-800 text-3xl cursor-pointer" value="Search">
            </form>
            <!--END FORM-->
            <!-- START CITY DEFINED -->
            <?php if ($cityDefined) : ?>
                <div class="mt-0">
                    <p class="bg-blue-800 text-white p-3 px-5 text-sm lg:inline-block mt-1 lg:mt-0"><?=$msg ?></p>
                </div>
            <?php endif; ?>
            <!-- END CITY DEFINED -->   
        </section>
        <!-- START RESULTS FOUND -->
        <?php if ($resultsFound) : ?>
        <!--START DAILY BARCHART-->
        <section class="mt-5 bg-white mx-5 shadow-lg" style="height: 30vh;">
            <div class="chart-container p-5" style="position: relative; height:100%; width:100%">
               <?php include("chart.php") ?>
            </div>
        </section>
        <!--END DAILY BARCHART-->
        <!--START DAILY CARDS-->
        <section>
            <div class="sm:w-full px-5">
                <div class="flex flex-wrap -mx-5 mt-10">

                <div class="w-full md:w-1/2 lg:w-2/4 p-7 px-5 mb-10">
                        <div class="p-10 md:p-6 bg-white text-center shadow-lg">
                            <h1 class="text-2xl my-2 font-bold leading-tight">Now</h1>
                            <p>Special card today summary and detail view ⛅ </p>
                            <div class="minmax flex justify-center my-5">
                                <div class="min w-1/2 h-32 p-5 bg-green-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                                <div class="max w-1/2 h-32 p-5 bg-red-800 text-white text-5xl relative"><?=$metrics["max"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">maximum</div>
                                </div>
                                <div class="min w-1/2 h-32 p-5 bg-blue-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                                <div class="min w-1/2 h-32 p-5 bg-yellow-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                                <div class="min w-1/2 h-32 p-5 bg-blue-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                                <div class="min w-1/2 h-32 p-5 bg-orange-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                            </div>
                        <?php include("hourchart.php") ?>
                        </div>
                    </div>
            
                    <!-- START FOREACH DAY -->    
                    <?php foreach($fullData as $day => $weather) : ?>
                    <?php $metrics = array_pop($weather);?>
                    <div class="w-full md:w-1/2 lg:w-2/4 p-7 px-5 mb-10">
                        <div class="p-10 md:p-6 bg-white text-center shadow-lg">
                            <h1 class="text-2xl my-2 font-bold leading-tight"><?=$day?></h1>
                            <div class="minmax flex justify-center my-5">
                                <div class="min w-1/2 h-32 p-5 bg-blue-800 text-white text-5xl relative"><?=$metrics["min"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">minimum</div>
                                </div>
                                <div class="max w-1/2 h-32 p-5 bg-red-800 text-white text-5xl relative"><?=$metrics["max"] ?><sup class="text-lg">°C</sup>
                                    <div class="absolute bottom-0 mb-2 w-full text-center left-0 text-xs text-gray-100">maximum</div>
                                </div>
                            </div>
                        <?php include("hourchart.php") ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <!-- END FOREACH DAY -->    
                </div>
            </div>
        </section>
        <?php endif; ?>
        <!-- END RESULTS FOUND --> 
    </div>
    <!-- END CONTAINER -->    
</body>
</html>

