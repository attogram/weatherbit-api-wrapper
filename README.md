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

try {
    $weatherbit = new \Attogram\Weatherbit\Weatherbit();
    $weatherbit->setKey('YOUR-WEATHERBIT-API-KEY');
    $weatherbit->setCity('Amsterdam');
    $weatherbit->setCountry('NL');

    $currentWeather = $weatherbit->getCurrent(); // Gets array of current weather data

    $forecastedWeather = $weatherbit->getDailyForecast(15); // Gets array 15 day forecast

} catch (Exception $exception) {
    exit('Error: ' . $exception->getMessage());
}

print "Current Weather:\n";
print_r($currentWeather);

print "Forecasted Weather:\n";
print_r($forecastedWeather);

```

* see [public/test.php](public/test.php) for an example web form

## Links

* Github: <https://github.com/attogram/weatherbit-api-wrapper/>
* Packagist: <https://packagist.org/packages/attogram/weatherbit-api-wrapper>
* CodeClimate: <https://codeclimate.com/github/attogram/weatherbit-api-wrapper>
* Travis CI: <https://travis-ci.org/attogram/weatherbit-api-wrapper>
