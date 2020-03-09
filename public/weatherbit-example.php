<?php
/**
 * Example of using the Weatherbit API Wrapper
 * 
 * @see https://github.com/attogram/weatherbit-api-wrapper
 */
declare(strict_types = 1);

use \Attogram\Weatherbit\Weatherbit;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('../src/Weatherbit.php'); // or: require('path/to/vendor/autoload.php');

$call      = isset($_GET['call'])    ? $_GET['call']         : 'usage';
$key       = isset($_GET['key'])     ? $_GET['key']          : '';
$city      = isset($_GET['city'])    ? $_GET['city']         : '';
$country   = isset($_GET['country']) ? $_GET['country']      : '';
$days      = isset($_GET['days'])    ? intval($_GET['days']) : 2;
$ipAddress = isset($_GET['ip'])      ? $_GET['ip']           : '';
$latitude  = isset($_GET['lat'])     ? $_GET['lat']          : '';
$longitude = isset($_GET['long'])    ? $_GET['long']         : '';

$selected = ' checked';
$callSelected = [];
$callSelected['forecast'] = ($call == 'forecast') ? $selected : '';
$callSelected['current'] = ($call == 'current') ? $selected : '';
$callSelected['usage'] = ($call == 'usage') ? $selected : '';

$pageTitle= 'Weatherbit v' . Weatherbit::VERSION . ' web test';

printForm();

if (!isset($_GET['run']) || $_GET['run'] != 'test') {
    printFooter();
    exit;
}

$data = [];
$error = '';

$weatherbit = new Weatherbit();

try {
    $weatherbit->setKey($key);

    if (!empty($city)) {
        print '<pre>setLocationByCity(' . htmlentities("$city, $country") . ')</pre>';
        $weatherbit->setLocationByCity($city, $country);
    } elseif (!empty($latitude)) {
        print '<pre>setLocationByLatitudeLongitude(' . htmlentities("$latitude, $longitude") . ')</pre>';
        $weatherbit->setLocationByLatitudeLongitude($latitude, $longitude);
    } elseif (!empty($ipAddress)) {
        print '<pre>setLocationByIP(' . htmlentities($ipAddress) . ')</pre>';
        $weatherbit->setLocationByIP($ipAddress);
    }

    switch ($call) {
        case 'forecast':
            $data = $weatherbit->getDailyForecast($days);
            break;
        case 'current':
            $data = $weatherbit->getCurrent();
            break;
        case 'usage':
            $data = $weatherbit->getUsage();
            break;
        default:
            $error = 'Invalid Call type';
            break;
    }
} catch (Exception $error) {
    $error = $error->getMessage();
}

printResults($data, $error);

printFooter();


function printResults($data, $error)
{
    global $weatherbit;

    print '<pre>API Call URL: <a href="' . $weatherbit->getUrl() . '" target="_blank">'
    . $weatherbit->getUrl() . '</a></pre>';

    if ($error) {
        print '<p style="background-color:lightsalmon;padding:10px;">ERROR: ' . $error . '</p>';
    }

    if ($data) {
        print '<pre style="background-color:lightgreen;padding:10px;">Data: ' . print_r($data, true) . '</pre>';
    }
}

function printForm()
{
    global $pageTitle, $callSelected, $key, $city, $country, $days, $ipAddress, $latitude, $longitude;

    print '<html><head><title>' . $pageTitle . '</title></head><body>
    <h1>' . $pageTitle  . '</h1>
    <form>
        <dl>
            <dt>API Call:<dt>
            <dd><input type="radio" name="call" value="forecast"' . $callSelected['forecast'] . '>Daily Weather Forecast (1-16 days)</input></dd>
            <dd><input type="radio" name="call" value="current"' . $callSelected['current'] . '>Current Weather</input></dd>
            <dd><input type="radio" name="call" value="usage"' . $callSelected['usage'] . '>API Usage</input></dd>
        </dl>

        API Key: <input name="key" type="text" value="' . htmlentities($key) . '" size="35" /><br />

        <dl>
            <dt>Location by City:</dt>
                <dd> City: <input name="city" type="text" value="' . htmlentities($city) . '" size="20" /></dd>
                <dd>Country: <input name="country" type="text" value="' . htmlentities($country) . '" size="2" maxlength="2" />
                    (2 Letter Country Code) (optional)</dd>
            <br />
            <dt>Location By Latitude / Longitude:</dt>
                <dd>Latitude: <input name="lat" type="text" value="' . $latitude . '" size="20" /></dd>
                <dd>Longitude: <input name="long" type="text" value="' . $longitude . '" size="20" /></dd>
            <br />
            <dt>Location By IP Address:</dt>
                <dd>IP Address: <input name="ip" type="text" value="' . $ipAddress . '" size="20" /></dd>
        </dl>

        Forecast Days (1-16): <input name="days" type="text" value="' . $days . '" size="2" maxlength="2" /><br />
        Language: en<br />
        Units: Metric<br />
        <input type="hidden" name="run" value="test" />
        <input type="submit" value="  Get Weatherbit.io API Response  " />
        &nbsp; &nbsp; <a href="' . $_SERVER['PHP_SELF'] . '">reset</a>
    </form>';
}

function printFooter()
{
    print '<br /><hr />'
        . '<a href="https://github.com/attogram/weatherbit-api-wrapper/" target="_blank">'
            . 'https://github.com/attogram/weatherbit-api-wrapper/</a>'
        . '<br /><br />' . gmdate('Y-m-d H:i:s') . ' UTC'
        . '</body></html>';
}
