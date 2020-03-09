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
        $this->assertIsString(Weatherbit::VERSION);
        $this->assertIsString(Weatherbit::USER_AGENT);
        $this->assertIsString(Weatherbit::PREFIX_API);
        $this->assertIsString(Weatherbit::POSTFIX_FORECAST_DAILY);
        $this->assertIsString(Weatherbit::POSTFIX_CURRENT);
        $this->assertIsString(Weatherbit::POSTFIX_USAGE);
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
