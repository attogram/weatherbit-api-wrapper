<?php
/**
 * Weatherbit API Wrapper
 */
declare(strict_types = 1);

namespace Attogram\Weatherbit;

use Exception;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function is_array;
use function is_string;
use function json_decode;
use function strlen;

class Weatherbit
{
    const VERSION = '1.1.2';

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
     * @var string - City to use for weather lookup
     */
    private $city = '';

    /**
     * @var string - Country to use for weather lookup
     */
    private $country = '';

    /**
     * @var string - IP address to use for weather lookup
     */
    private $ipAddress = '';

    /**
     * Set Weatherbit API access key
     *
     * @param string $key
     * @throws Exception
     * @return void
     */
    public function setKey($key)
    {
        if (!is_string($key) || empty($key)) {
            throw new Exception('Invalid API Key');
        }
        $this->key = $key;
    }

    /**
     * Set City for weather lookup
     *
     * @param string $city
     * @throws Exception
     * @return void
     */
    public function setCity($city)
    {
        if (!is_string($city) || empty($city)) {
            throw new Exception('Invalid City');
        }
        $this->city = $city;
    }

    /**
     * Set Country for weather lookup
     *
     * @param string $country - 2 character country code
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Set IP address
     *
     * @param string $ipAddress - ipv4 IP address
     * @return void
     */
    public function setIp($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get Daily Weather Forecast for 1-16 days in future
     *
     * @param int $days - Number of days to forecast (optional, default 10)
     * @throws Exception - on missing key, city, country, or invalid days
     * @return array - array of forecast data
     */
    public function getDailyForecast($days = 10)
    {
        $this->validateCall();
    
        if ($days < 1 || $days > 16) {
            throw new Exception('Days must between 1 and 16');
        }

        $url = self::PREFIX_API . self::POSTFIX_FORECAST_DAILY
            . '?key=' . $this->key
            // lagnnguage (default: en)
            . '&lang=en'
            // units (default: M)
            // M= Metric (Celcius, m/s, mm), I= Imperial/Fahrenheit (F, mph, in), S= Scientific (Kelvin, m/s, mm)
            . '&units=M'
            . '&days=' . $days // (optional, default: 16)
            . '&city=' . urlencode($this->city)
            . '&country=' . urlencode($this->country);

        return $this->get($url);
    }

    /**
     * Get Current Weather
     *
     * @return array
     */
    public function getCurrent()
    {
        $this->validateCall();
    
        $url = self::PREFIX_API . self::POSTFIX_CURRENT
            . '?key=' . $this->key
            . '&lang=en'
            . '&units=M'
            . '&city=' . urlencode($this->city)
            . '&country=' . urlencode($this->country);

        return $this->get($url);
    }

    /**
     * Get current API usage stats
     *
     * @return array
     */
    public function getUsage()
    {
        $url = self::PREFIX_API . self::POSTFIX_USAGE . '?key=' . $this->key;

        return $this->get($url);
    }

    /**
     * validate we have the required variables for an API call
     *
     * @throws Exception
     * @return void
     */
    private function validateCall()
    {
        if (empty($this->key)) {
            throw new Exception('Missing API Key');
        }
        if (empty($this->city)) {
            throw new Exception('Missing City');
        }
    }

    /**
     * Get Data from the API
     *
     * @param string $url
     * @throws Exception
     * @return array - array of forecast data
     */
    private function get($url)
    {
        if (empty($url) || !is_string($url)) {
            throw new Exception('Invalid API URL');
        }
    
        $curl = curl_init($url);
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
