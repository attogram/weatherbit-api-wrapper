# weatherbit-api-wrapper

PHP wrapper for Weatherbit.io API

[![Build Status](https://travis-ci.org/attogram/weatherbit-api-wrapper.svg?branch=master)](https://travis-ci.org/attogram/weatherbit-api-wrapper)


## Install

`composer require attogram/weatherbit-api-wrapper`

## Example Usage

```php
<?php

require('path/to/vendor/autoload.php');

$weatherbit = new \Attogram\Weatherbit\Weatherbit();

$weatherbit->setKey('YOUR-WEATHERBIT-API-KEY');

$weatherbit->setCity('Amsterdam');

$weatherbit->setCountry('NL');

$currentWeather = $weatherbit->getCurrent(); // Get current weather data

$forecastedWeather = $weatherbit->getDailyForecast(15); // Get 15 day forecast


```

* see [public/test.php](public/test.php) for an example web form

## Links

* Packagist: <https://packagist.org/packages/attogram/weatherbit-api-wrapper>
* CodeClimate: <https://codeclimate.com/github/attogram/weatherbit-api-wrapper>
* Travis CI: <https://travis-ci.org/attogram/weatherbit-api-wrapper>
