<?php
/**
 * Weatherbits API Wrapper Tests
 *
 * @see https://github.com/attogram/weatherbit-api-wrapper
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Attogram\Weatherbit\Weatherbit;
use Attogram\Weatherbit\WeatherbitException;

final class WeatherbitTest extends TestCase
{
    /**
     * @var Attogram\Weatherbit\Weatherbit
     */
    protected $weatherbit;

    // @TODO test setUp() against phpunit 6
    protected function setWeatherbit()
    {
        if (!$this->weatherbit) {
            $this->weatherbit = new Weatherbit();
        }
    }

    public function testWeatherbitClass()
    {
        $this->assertTrue(class_exists('Attogram\Weatherbit\Weatherbit'));
        $this->setWeatherbit();
        $this->assertInstanceOf(Weatherbit::class, $this->weatherbit);
        $this->assertTrue(is_string(Weatherbit::VERSION));
        $this->assertTrue(is_string(Weatherbit::VERSION));
        $this->assertTrue(is_string(Weatherbit::USER_AGENT));
        $this->assertEquals(Weatherbit::PREFIX_API, 'https://api.weatherbit.io/v2.0');
        $this->assertEquals(Weatherbit::POSTFIX_FORECAST_DAILY, '/forecast/daily');
        $this->assertEquals(Weatherbit::POSTFIX_CURRENT, '/current');
        $this->assertEquals(Weatherbit::POSTFIX_USAGE, '/subscription/usage');
        $this->assertClassHasAttribute('key', Weatherbit::class);
        $this->assertClassHasAttribute('language', Weatherbit::class);
        $this->assertClassHasAttribute('units', Weatherbit::class);
        $this->assertClassHasAttribute('location', Weatherbit::class);
        $this->assertClassHasAttribute('url', Weatherbit::class);
    }

    public function testClassWeatherbitException()
    {
        $this->assertTrue(class_exists('\Attogram\Weatherbit\WeatherbitException'));
        $this->expectException(WeatherbitException::class);
        throw new WeatherbitException();
    }

    public function testSetKey()
    {
        $this->setWeatherbit();
        $this->weatherbit->setKey('1234567890abcdefghijk');
        $this->assertTrue(true);
    }

    public function testSetKeyEmptyString()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setKey('');
    }

    public function testSetKeyNotString()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setKey([]);
    }

    public function testSetLanguage()
    {
        $this->setWeatherbit();
        $this->weatherbit->setLanguage('nl');
        $this->assertTrue(true);
    }

    public function testSetLanguageEmptyString()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLanguage('');
    }

    public function testSetLanguageNotString()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setLanguage([]);
    }

    public function testSetLanguageTooShort()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLanguage('a');
    }

    public function testSetLanguageTooLong()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLanguage('abc');
    }

    public function testSetUnits()
    {
        $this->setWeatherbit();
        $this->weatherbit->setUnits('M');
        $this->weatherbit->setUnits('S');
        $this->weatherbit->setUnits('I');
        $this->assertTrue(true);
    }

    public function testSetUnitsEmptyString()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setUnits('');
    }

    public function testSetUnitsNotString()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setUnits([]);
    }

    public function testSetUnitsNonCode()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setUnits('X');
    }

    public function testSetLocationByLatitudeLongitude()
    {
        $this->setWeatherbit();
        $this->weatherbit->setLocationByLatitudeLongitude('48.869966', '2.332706'); // Paris, FR
        $this->assertTrue(true);
    }

    public function testSetLocationByLatitudeLongitudeEmptyStrings()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLocationByLatitudeLongitude('', '');
    }

    public function testSetLocationByLatitudeLongitudeNotStrings()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setLocationByLatitudeLongitude([], []);
    }

    public function testSetLocationByCity()
    {
        $this->setWeatherbit();
        $this->weatherbit->setLocationByCity('Amsterdam');
        $this->assertTrue(true);
    }

    public function testSetLocationByCityCountry()
    {
        $this->setWeatherbit();
        $this->weatherbit->setLocationByCity('Amsterdam', 'NL');
        $this->assertTrue(true);
    }

    public function testSetLocationByCityStateCountry()
    {
        $this->setWeatherbit();
        $this->weatherbit->setLocationByCity('Amsterdam, NY', 'US');
        $this->assertTrue(true);
    }

    public function testSetLocationByCityEmptyString()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLocationByCity('');
    }

    public function testSetLocationByCityEmptyStrings()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLocationByCity('', '');
    }

    public function testSetLocationByCityNotString()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setLocationByCity([]);
    }

    public function testSetLocationByCityNotStrings()
    {
        $this->setWeatherbit();
        $this->expectException(TypeError::class);
        $this->weatherbit->setLocationByCity([], []);
    }

    public function testSetLocationByCityInvalidCountry()
    {
        $this->setWeatherbit();
        $this->expectException(WeatherbitException::class);
        $this->weatherbit->setLocationByCity('Amsterdam', 'NLD');
    }

    // @TODO setLocationByCityId
    // @TODO setLocationByCityIds
    // @TODO setLocationByPostalCode
    // @TODO setLocationByIp
    // @TODO setLocationByStation
    // @TODO setLocationByStations

    // @TODO getDailyForecast
    // @TODO getCurrent
    // @TODO getUsage
    // @TODO getUrl
    // @TODO setUrl
    // @TODO get
}
