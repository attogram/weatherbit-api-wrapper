# weatherbit-api-wrapper

PHP wrapper for Weatherbit.io API

[![Build Status](https://travis-ci.org/attogram/weatherbit-api-wrapper.svg?branch=master)](https://travis-ci.org/attogram/weatherbit-api-wrapper)
[![Maintainability](https://api.codeclimate.com/v1/badges/46de553afcad6cff3161/maintainability)](https://codeclimate.com/github/attogram/weatherbit-api-wrapper/maintainability)
[![Total Downloads](https://poser.pugx.org/attogram/weatherbit-api-wrapper/downloads)](https://packagist.org/packages/attogram/weatherbit-api-wrapper)
[![License](https://poser.pugx.org/attogram/weatherbit-api-wrapper/license)](https://packagist.org/packages/attogram/weatherbit-api-wrapper)

Versions:
[![Latest Stable Version](https://poser.pugx.org/attogram/weatherbit-api-wrapper/v/stable)](https://packagist.org/packages/attogram/weatherbit-api-wrapper)
[![Latest Unstable Version](https://poser.pugx.org/attogram/weatherbit-api-wrapper/v/unstable)](https://packagist.org/packages/attogram/weatherbit-api-wrapper)

## Install

* `composer require attogram/weatherbit-api-wrapper`
* Get an API Key from: <https://www.weatherbit.io/>

## Example Usage

```php
<?php
require('path/to/vendor/autoload.php');

$weatherbit = new \Attogram\Weatherbit\Weatherbit();

try {
    $weatherbit->setKey('YOUR-WEATHERBIT-API-KEY');
    $weatherbit->setLocationByCity('Amsterdam', 'NL');
    $currentWeather = $weatherbit->getCurrent(); // Gets array of current weather data
} catch (Exception $exception) {
    exit('Error: ' . $exception->getMessage());
}

print_r($currentWeather);
```

* see [public/example.php](public/example.php) for an example web form

## Functions

### public function setKey(string $key)

### public function setLanguage(string $languageCode)

### public function setUnits(string $unitsCode)

### public function setLocationByLatitudeLongitude(string $latitude, string $longitude)

### public function setLocationByCityId(string $cityId)

### public function setLocationByPostalCode(string $postalCode)

### public function setLocationByCityIds(array $cityIds)

### public function setLocationByCity(string $city, string $country = '')

### public function setLocationByIp(string $ipAddress = 'auto')

### public function setLocationByStation(string $weatherStation)

### public function setLocationByStations(array $weatherStations)

### public function getDailyForecast($days = 10): array

### public function getCurrent(): array

### public function getUsage(): array

### public function getUrl(): string

## Project Links

* Github: <https://github.com/attogram/weatherbit-api-wrapper/>
* Packagist: <https://packagist.org/packages/attogram/weatherbit-api-wrapper>
* CodeClimate: <https://codeclimate.com/github/attogram/weatherbit-api-wrapper>
* Travis CI: <https://travis-ci.org/attogram/weatherbit-api-wrapper>
