<?php
/**
 * Weatherbits API Wrapper Tests
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Attogram\Weatherbit\Weatherbit;

final class WeatherbitTest extends TestCase
{
    /**
     * @var Attogram\Weatherbit\Weatherbit
     */
    protected $weatherbit;

    protected function setWeatherbit()
    {
        if (!$this->weatherbit) {
            $this->weatherbit = new Weatherbit();
        }
    }

    public function testClass()
    {
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

    public function testSetKey()
    {
        $this->setWeatherbit();
        $this->weatherbit->setKey('1234567890abcdefghijk');
        $this->assertTrue(true);
    }

    public function testSetKeyEmptyString()
    {
        $this->setWeatherbit();
        $this->expectException(Exception::class);
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
        $this->expectException(Exception::class);
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
        $this->expectException(Exception::class);
        $this->weatherbit->setLanguage('a');
    }

    public function testSetLanguageTooLong()
    {
        $this->setWeatherbit();
        $this->expectException(Exception::class);
        $this->weatherbit->setLanguage('abc');
    }

}
