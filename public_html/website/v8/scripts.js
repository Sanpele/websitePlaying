let lat = 0;
let long = 0;

function ipLookUp() {
    $.ajax('http://ip-api.com/json')
    .then(
        function success(response) {
            console.log('User\'s Location Data is ', response);
            console.log('User\'s Country', response.country);
            console.log("lat is " + response.lat);
            console.log("long is " + response.lon);
            document.getElementById("city").innerHTML = "i am thinking your city is " + response.city;
            document.getElementById("internet").innerHTML = "corret me if im wrong but your internet provider is " + response.org; 


            lat = response.lat;
            long = response.lon;

            
            getWeather().then(data => {
                container.innerHTML = "humidity is : " + data.current.humidity + "%";
                temp.innerHTML = "the currrent temp is " +data.current.temp + " celcius?";
                discription.innerHTML = "a description of the current weather situation: " + data.current.weather[0].description
                console.log(data);
            });
        },

        function fail(data,status) {
            console.log('Request failed. Returned status of', status);
        }
    );
}
ipLookUp()

console.log("lat is " + lat);



const key = "4dfdbea5c03a11364df9aa93bade3378";
let container = document.getElementById("display");
let temp = document.getElementById("temp");

async function getWeather() {
    let respose = await fetch('https://api.openweathermap.org/data/2.5/onecall?lat=' + lat + '&lon=' + long + '&units=metric&exclude=daily,hourly&appid=' + key + '');
    let data = await respose.json();
    // let result = JSON.parse(data);
    return data;
}

// let weatherData = getWeather();


