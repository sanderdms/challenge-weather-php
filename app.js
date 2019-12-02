const template = document.getElementById("weather-day");
const target = document.getElementById("app");

const cityInput = document.getElementById("city-input");
const weatherForm = document.getElementById("weather-form");

const getWeatherData = async city => {
  const apiKey = "d02ba4169b2ac4f0d179b1e84c341147";
  const apiUrl = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&APPID=${apiKey}`;

  const respons = await fetch(apiUrl);
  const data = await respons.json();
  return data;
};

document.getElementById("run").addEventListener("click", async function() {
    const data = await getWeatherData(cityInput.value);
    if(data.cod=="200") renderWeather(transformPerDay(data));
    if(data.cod=="404") console.log("display city not found message");
});

let timer = null;
cityInput.addEventListener("input", function() {
  clearTimeout(timer);

  if (cityInput.value.length <= 0) {
    document.getElementById("app").innerHTML = "";
  }
});

const getDayFromString = string => {
  const date = new Date(string);
  const result = date.getDay();
  return result;
};

const transformPerDay = data => {
  const dayList = [];
  data.list.forEach(element => {
    const weekDay = new Date(element.dt_txt).toLocaleString("nl-BE", {
      weekday: "long"
    });
    const today = new Date();
    if (weekDay == today.toLocaleString("nl-BE", { weekday: "long" })) {
      element.Today = " (Vandaag)";
    }
    element.weekday = weekDay;
  });

  const weekDays = [
    "zondag",
    "dinsdag",
    "woensdag",
    "donderdag",
    "vrijdag",
    "zaterdag",
    "maandag"
  ];

  weekDays.forEach(weekDay => {
    const filtered = data.list.filter(item => item.weekday == weekDay);
    if (filtered.length > 0) dayList.push(filtered);
  });

  return dayList;
};

const renderWeather = days => {
  target.querySelectorAll('*').forEach(n => n.remove());
  console.table(days);
  days.forEach(day => {
    let max = day[0].main.temp_max;
    let min = day[0].main.temp_min;
    let icon = "";

    day.forEach(hour => {
      if (hour.main.temp_max > max) {
        max = hour.main.temp_max;
      }
      if (hour.main.temp_min < min) {
        min = hour.main.temp_min;
      }
    });

    console.log(day[0].weekday + "---MAX TEMP--" + max);
    console.log(day[0].weekday + "---MIN TEMP--" + min);
    const tempNode = template.content.cloneNode(true);

    if (day[0].Today) {
      tempNode.querySelector("h1").innerText = day[1].weekday + day[0].Today;
      tempNode.querySelector(
        "img"
      ).src = `http://openweathermap.org/img/wn/${day[1].weather[0].icon}.png`;
      tempNode.querySelector(
        ".percent_cloudDek"
      ).innerText = `CloudDek : ${day[0].clouds.all}%`;
      tempNode.querySelector(".min_temp").innerText = `min-temp = ${min} °C`;
      tempNode.querySelector(".max_temp").innerText = `max-temp = ${max} °C`;
      tempNode.querySelector(
        ".description"
      ).innerHTML = `Discription : ${day[0].weather[0].description}`;
      tempNode.querySelector(
        ".pressure"
      ).innerHTML = `Pressure = ${day[0].main.pressure} hPa`;
      tempNode.querySelector(
        ".wind-speed"
      ).innerHTML = `Wind Speed = ${day[0].wind.speed} kmh`;
      tempNode.querySelector(
        ".wind-dir"
      ).innerHTML = `Wind Direction = ${day[0].wind.deg}°`;
      target.appendChild(tempNode);
    } else {
      tempNode.querySelector("h1").innerText = day[1].weekday;
      tempNode.querySelector(
        "img"
      ).src = `http://openweathermap.org/img/wn/${day[1].weather[0].icon}.png`;
      tempNode.querySelector(
        ".percent_cloudDek"
      ).innerText = `CloudDek : ${day[0].clouds.all}%`;
      tempNode.querySelector(".min_temp").innerText = `min-temp = ${min} °C`;
      tempNode.querySelector(".max_temp").innerText = `max-temp = ${max} °C`;
      tempNode.querySelector(
        ".description"
      ).innerHTML = `Discription : ${day[0].weather[0].description}`;
      tempNode.querySelector(
        ".pressure"
      ).innerHTML = `Pressure = ${day[0].main.pressure} hPa`;
      tempNode.querySelector(
        ".wind-speed"
      ).innerHTML = `Wind Speed = ${day[0].wind.speed} kmh`;
      tempNode.querySelector(
        ".wind-dir"
      ).innerHTML = `Wind Direction = ${day[0].wind.deg}°`;
      target.appendChild(tempNode);
    }
  });
};