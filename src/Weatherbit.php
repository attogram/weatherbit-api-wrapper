<?php
/**
 * Weatherbit API Wrapper
 * 
 * @see https://github.com/attogram/weatherbit-api-wrapper
 */
declare(strict_types = 1);

namespace Attogram\Weatherbit;

use Exception;
use function curl_close;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function is_array;
use function is_string;
use function json_decode;
use function strlen;

class Weatherbit
{
    const VERSION = '2.0.0';

    /**
     * @var string - user agent for API requests
     */
    const USER_AGENT = 'WeatherbitApiWrapper/' . self::VERSION;

    /**
     * @var string - Weatherbit api endpoint prefix
     * @see https://www.weatherbit.io/api
     * @see https://www.weatherbit.io/api/swaggerui/weather-api-v2
     */
    const PREFIX_API = 'https://api.weatherbit.io/v2.0';

    /**
     * @var string - api postfix for 16 day / daily Forecast
     *             - Returns a daily forecast, where each point represents one day (24hr) period.
     *             - Every point has a datetime string in the format "YYYY-MM-DD".
     *             - One day begins at 00:00 UTC, and ends at 23:59 UTC.
     * @see https://www.weatherbit.io/api/weather-forecast-16-day
     * @see https://www.weatherbit.io/api/swaggerui/weather-api-v2#/1632day324732daily32Forecast
     */
    const POSTFIX_FORECAST_DAILY = '/forecast/daily';

    /**
     * @var string - api postfix for current weather
     *             - Returns a Current Observation
     *             - Given a city in the format of "City" or "City, State".
     *             - The state, and country parameters can be provided to make the search more accurate.
     * @see https://www.weatherbit.io/api/weather-current
     * @see https://www.weatherbit.io/api/swaggerui/weather-api-v2#/Current32Weather32Data
     */
    const POSTFIX_CURRENT = '/current';

    /**
     * @var string - api postfix for current usage
     *             - Returns the current Weather API usage summary for your API key subscription.
     * @see https://www.weatherbit.io/api/subscription-usage
     */
    const POSTFIX_USAGE = '/subscription/usage';

    /**
     * @var string - Weatherbit API access key
     */
    private $key = '';

    /**
     * @var string - Language for API response - default 'en' for English
     * @see https://www.weatherbit.io/api/requests
     */
    private $language = '';

    /**
     * @var string - Units for API Response 
     *               M = [DEFAULT] Metric (Celcius, m/s, mm)
     *               S = Scientific (Kelvin, m/s, mm)
     *               I = Imperial Fahrenheit (F, mph, in)
     * @see https://www.weatherbit.io/api/requests
     */
    private $units = '';

    /**
     * @var string - array of location values for API call
     */
    private $location = [];

    /**
     * @var string - URL for API call
     */
    private $url = '';


    /**
     * Set Weatherbit API access key
     *
     * @param string $key
     * @throws Exception
     * @return void
     */
    public function setKey(string $key)
    {
        if (!is_string($key) || empty($key)) {
            throw new Exception('Invalid API Key');
        }
        $this->key = $key;
    }

    /**
     * Set Language
     * @see https://www.weatherbit.io/api/requests
     *
     * @param string $languageCode - 2 letter language code
     */
    public function setLanguage(string $languageCode)
    {
        if (empty($languageCode) || strlen($languageCode) != 2) {
            throw new Exception('Invalid Language Code');
        }
        $this->language = $languageCode;
    }

    /**
     * Set Units
     * @see https://www.weatherbit.io/api/requests
     * 
     * @param string $unitsCode - 1 letter units code
     */
    public function setUnits(string $unitsCode)
    {
        if (empty($unitsCode) || !in_array($unitsCode, ['M', 'S', 'I'])) {
            throw new Exception('Invalid Units value.  Please use: M, S, or I');
        }
        $this->units = $unitsCode;
    }

    /**
     * Set Location by Latitude/Longitude
     * 
     * @param string $latitude
     * @param string $longitude
     */
    public function setLocationByLatitudeLongitude(string $latitude, string $longitude)
    {
        $this->location = [
            'lat' => $latitude,
            'lon' => $longitude,
        ];
    }

    /**
     * Set Location by City ID
     * 
     * @param string $cityId
     */
    public function setLocationByCityId(string $cityId)
    {
        $this->location = [
            'city_id' => $cityId,
        ];
    }

    /**
     * Set Location by Postal Code
     * 
     * @param string $postalCode
     */
    public function setLocationByPostalCode(string $postalCode)
    {
        $this->location = [
            'postal_code' => $postalCode,
        ];
    }

    /**
     * Set Location to a List of Cities IDs
     * 
     * @param array $cityIds
     */
    public function setLocationByCityIds(array $cityIds)
    {
        // Comma separated list of City IDs
        $this->location = [
            'cities' => implode(',', $cityIds),
        ];
    }

    /**
     * Set Location by City Name
     * 
     * @param string $city
     * @param string $country (optional) 2 letter country code
     */
    public function setLocationByCity(string $city, string $country = '')
    {
        if (empty($city)) {
            throw new Exception('Invalid City');
        }

        $this->location = [
            'city' => $city,
            'country' => $country,
        ];
    }

    /**
     * Set Location by IP Address
     * 
     * @param string $ipAddress - Ip Address, or 'auto'
     */
    public function setLocationByIp(string $ipAddress = 'auto')
    {
        $this->location = [
            'ip' => $ipAddress,
        ];
    }

    /**
     * Set Location by Weather Station
     * 
     * @param string $weatherStations
     */
    public function setLocationByStation(string $weatherStation)
    {
        $this->location = [
            'station' => $weatherStation,
        ];
    }

    /**
     * Set Location to List of Weather Stations
     */
    public function setLocationByStations(array $weatherStations)
    {
        $this->location = [
            'stations' => implode(',', $weatherStations),
        ];
    }

    /**
     * Get Daily Weather Forecast for 1-16 days in future
     *
     * @param int $days - Number of days to forecast (optional, default 10)
     * @throws Exception
     * @return array - array of weather forecast data
     */
    public function getDailyForecast($days = 10): array
    {
        if ($days < 1 || $days > 16) {
            throw new Exception('Forecast Days must between 1 and 16');
        }

        $this->setUrl(
            self::PREFIX_API . self::POSTFIX_FORECAST_DAILY, 
            ['days' => $days]
        );

        return $this->get();
    }

    /**
     * Get Current Weather
     *
     * @return array - array of current weather data
     */
    public function getCurrent(): array
    {
        $this->setUrl(self::PREFIX_API . self::POSTFIX_CURRENT);

        return $this->get();
    }

    /**
     * Get current API usage stats
     *
     * @return array - array of API subscription usage
     */
    public function getUsage(): array
    {
        $this->setUrl(self::PREFIX_API . self::POSTFIX_USAGE);

        return $this->get();
    }

    /**
     * Get current API Call URL
     * 
     * @return string - The Current URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set the URL string for the API Call
     * 
     * @param string $prefix - URL Prefix
     * @param array $additional - array of name/value pairs for additional URL values
     * @throws Exception
     */
    private function setUrl($prefix, $additional = [])
    {
        if (empty($this->key)) {
            throw new Exception('Missing API Key');
        }
    
        $this->url = $prefix . '?key=' .  urlencode($this->key);

        if (!empty($this->language)) {
            $this->url .= '&lang=' .  urlencode($this->language);
        }
        if (!empty($this->units)) {
            $this->url .= '&units=' .  urlencode($this->units);
        }
        foreach ($this->location as $name => $value) {
            if (!empty($value)) {
                $this->url .= '&' . $name . '=' . urlencode((string) $value);
            }
        }
        if (!empty($additional)) {
            foreach ($additional as $name => $value) {
                if (!empty($value)) {
                    $this->url .= '&' . $name . '=' . urlencode((string) $value);
                }
            }
        }
    }

    /**
     * Get Weather Data from the API
     *
     * @throws Exception
     * @return array - array of weather data
     */
    private function get()
    {
        if (empty($this->url)) {
            throw new Exception('Missing URL for API Call');
        }
    
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);

        $jsonData = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status != '200') {
            throw new Exception('API Failure - status code: ' . $status . ' - data: ' . print_r($jsonData, true));
        }

        if (empty($jsonData)) {
            throw new Exception('No data from API');
        }

        $data = @json_decode($jsonData, true); // @silently ignore decode errors
        if (!is_array($data)) {
            throw new Exception('Unable to decode response from API');
        }

        return $data;
    }
}
