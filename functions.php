<?php

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
        $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');
        $day = $dowMap[$day -1];
        if(!array_key_exists($day, $output)){
            $output[$day] = [];
        }
        array_push($output[$day], $listitem);
    }
    return $output;
}

// $daily = array
function addDailyMetrics($daily)
{
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

function generateMinMax($fullData)
{
    $output = [
        "min"=>"",
        "max"=>""
    ];
    foreach($fullData as $day => $weather){
    $metrics = array_pop($weather);
    $output["min"] .= $metrics["min"].",";
    $output["max"] .= $metrics["max"].",";
    }
    return $output;
}

function generateDayLabels($dailyData)
{
    $output="";
    foreach($dailyData as $day => $data){
        $output .= '\''.$day.'\''.',';
    }
    return $output;
}