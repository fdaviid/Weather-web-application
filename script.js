//függvények az időjárási adatok lekérdezéséhez
document.addEventListener('DOMContentLoaded', function () {
    const APIKey = '2d65eaf788227ad6b56a55a92ff98657';

    function fetchHourlyForecast(city) {
        return fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${APIKey}`)
            .then(response => response.json());
    }

    function fetchCurrentWeather(city) {
        return fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${APIKey}`)
            .then(response => response.json());
            
    }

    function fetch5DayForecast(city) {
        return fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&cnt=40&appid=${APIKey}`)
            .then(response => response.json());
    }

    //ikonok beállítása az időjárási viszonyhoz képest
    function updateWeatherIcons(weatherData) {
        const weatherIconMap = {
            'Clear': '01d',
            'Clouds': '02d',
            'Rain': '10d',
            'Snow': '13d',
            'Mist': '50d',
            'Haze': '50d',

        };
        const iconBaseURL = 'https://openweathermap.org/img/wn/';
        weatherData.forEach((forecast, index) => {
            const weatherType = forecast.weather[0].main;
            const iconURL = `${iconBaseURL}${weatherIconMap[weatherType]}@2x.png`;
            document.getElementById(`wrapper-icon-hour${index + 1}`).src = iconURL;
        });
    }

    
    //beírt helység
    function searchSubmit(event) {
        event.preventDefault();

        const city = document.getElementById('city-input').value;

        if (city === '') {
            alert('Kérlek írd be a város nevét!');
            return;
        }

        fetchHourlyForecast(city)
            .then(json => {
                console.log(json);
                if (!json.list || json.list.length === 0) {
                    console.error('Hourly forecast data not found in API response.');
                    return;
                }

                

                //3 óránkénti előrejelzés
                for (let i = 0; i < 6; i++) {
                    const forecast = json.list[i];
                    const temperature = Math.round(forecast.main.temp);
                    const time = new Date(forecast.dt * 1000);
                    const hours = time.getHours();
                    const minutes = time.getMinutes();
                    document.getElementById(`wrapper-hour${i + 1}`).textContent = `${temperature}°C`;
                    document.getElementById(`wrapper-time${i + 1}`).textContent = `${hours < 10 ? '0' + hours : hours}:${minutes < 10 ? '0' + minutes : minutes}`;
                }
                updateWeatherIcons(json.list); //az ikonok frissítése az időjárás előrejelzés alapján

            })
            .catch(error => {
                console.error('Error fetching hourly forecast data:', error);
            });

        fetchCurrentWeather(city)
            .then(json => {
                const location = document.querySelector('.location');
                location.textContent = json.name;


                const currentTime = new Date();
                const timezoneOffsetSeconds = json.timezone;
                currentTime.setSeconds(currentTime.getSeconds() + timezoneOffsetSeconds);
                const hours = currentTime.getUTCHours();
                const minutes = currentTime.getUTCMinutes();
                const formattedTime = `${hours < 10 ? '0' + hours : hours}:${minutes < 10 ? '0' + minutes : minutes}`;

                const timeNow = document.querySelector('.time-now');
                timeNow.textContent = `Helyi idő - ${formattedTime}`;

                const image = document.querySelector('.weather-box img');
                const temperature = document.querySelector('.weather-box .temperature');
                const description = document.querySelector('.weather-box .description');
                const humidity = document.querySelector('.weather-details .humidity span');
                const wind = document.querySelector('.weather-details .wind span');
                const rain = document.querySelector('.weather-details .rain span');

                switch (json.weather[0].main) {
                    case 'Clear':
                        if (hours >= 22) {
                            image.src = 'clear_night.png';
                        } else {
                            image.src = 'clear.png';
                        }
                        break;

                    case 'Rain':
                        if (hours >= 22) {
                            image.src = 'rain_night.png';
                        } else {
                            image.src = 'rain.jpg';
                        }
                        break;

                    case 'Snow':
                        image.src = 'snow.png';
                        break;

                    case 'Clouds':
                        if (hours >=22) {
                            image.src = 'cloud_night.png';
                        } else {
                            image.src = 'cloud.png';
                        }
                        break;

                    case 'Mist':
                    case 'Haze':
                        image.src = 'mist.png';
                        break;

                    default:
                        image.src = 'cloud2.png';
                }

                temperature.innerHTML = `${parseInt(json.main.temp)}<span>°C</span>`;
                description.textContent = json.weather[0].description;
                humidity.textContent = `${json.main.humidity}%`;
                wind.textContent = `${parseInt(json.wind.speed)}Km/h`;
                rain.textContent = `${json.clouds.all}%`;

                const weatherAlert = document.querySelector('.weather-alert');
                var audio = new Audio('alertsound.mp3');

                //figyelmeztető üzenetek
                if (parseInt(json.main.temp) >= 35) {
                    weatherAlert.innerHTML = '<i class="bi bi-exclamation-triangle text-danger alert-icon"></i> Figyelem! A forró nyári időjárás kihívást jelenthet az egészséged számára, ezért fontos, hogy megfelelően felkészülj rá. Ne feledkezz meg a megfelelő hidratációról! Fogyassz elegendő mennyiségű vizet vagy más hidratáló italokat, hogy megelőzd a kiszáradást és fenntartsd a test megfelelő működését. Ha lehetséges, próbálj árnyékban maradni a legmelegebb órákban, hogy csökkentsd a hőhatást és megelőzd a hőguta kialakulását.';
                    audio.play();
                } else if (parseInt(json.wind.speed) >= 10) {
                    weatherAlert.innerHTML = '<i class="bi bi-exclamation-triangle text-danger alert-icon"></i> Figyelem! Az erős szél veszélyes lehet, különösen nyílt terepen vagy magas épületek közelében. Kérlek, ügyelj a biztonságodra, és kerüld a szabadban tartózkodást, ha lehetséges. Ha szükséges, keress fedezéket egy biztonságos helyen, és várj, amíg a szél ereje csillapodik.';
                    audio.play();
                } else if (parseInt(json.clouds.all) >= 70) {
                    weatherAlert.innerHTML = '<i class="bi bi-exclamation-triangle text-danger alert-icon"></i> Figyelem! Az esős időjárás veszélyes lehet, különösen ha a közlekedésben vagy. Győződj meg róla, hogy megfelelően felszerelt vagy esőkabátot vagy esernyőt használsz, és ügyelj a lassabb vezetésre, hogy minimalizáld a balesetek kockázatát.';
                    audio.play();
                } else if (parseInt(json.clouds.all) >= 80 && (parseInt(json.wind.speed) >= 15)) {
                    weatherAlert.innerHTML = '<i class="bi bi-exclamation-triangle text-danger alert-icon"></i> Figyelem! Jelenleg viharos időjárási előrejelzések vannak. Kérlek, maradj biztonságos helyen akár az otthonodban, távol az ablakoktól és az esetleges szabadon lévő tárgyaktól. Ha lehetőséged van rá, keress fedezéket egy masszív szerkezetű épületben, és várj, amíg a vihar elmúlik.';
                    audio.play();
                } else {
                    weatherAlert.textContent = '';
                }

                

            })
            .catch(error => {
                console.error('Error fetching current weather data:', error);
            });

        fetch5DayForecast(city)
            .then(json => {
                if (!json.list || json.list.length === 0) {
                    console.error('Forecast data not found in API response.');
                    return;
                }

                //5 napos előrejelzés
                for (let i = 0; i < 5; i++) {
                    const forecast = json.list[i * 8];
                    const temperature = Math.round(forecast.main.temp); 
                    const time = new Date(forecast.dt * 1000);
                    const date = time.getDate();
                    const month = time.getMonth() + 1;
                    const dateString = `${date}/${month}`;

                    document.getElementById(`wrapper-temperature-day${i + 1}`).textContent = `${temperature}°C`;
                    document.getElementById(`wrapper-date${i + 1}`).textContent = dateString;
                }
                updateWeatherIcons(json.list); //az ikonok frissítése az időjárás előrejelzés alapján

            })
            .catch(error => {
                console.error('Error fetching forecast data:', error);
            });
    }

    const searchForm = document.getElementById('search-form');
    searchForm.addEventListener('submit', searchSubmit);

    var disappear = function () {
        return jQuery(document).height() - jQuery(window).height();
    };

    jQuery(function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() >= disappear()) {
                jQuery('.vanish').fadeOut();
            } else {
                jQuery('.vanish').fadeIn();
            }
        });
    });
});

function validateInput(input) {
    var errorMessage = document.getElementById("error-message");

    if (/\d+$/.test(input.value)) { //teszt, hogy a beírt érték csak szám-e
        errorMessage.style.display = "inline"; 
        input.value = ''; 
    } else {
        errorMessage.style.display = "none"; 
    }
}