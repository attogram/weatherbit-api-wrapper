<?php
/**
 * Weatherbits API Wrapper Tests
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Attogram\Filesystem\Cache;

final class WeatherbitTests extends TestCase
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
    }
}
