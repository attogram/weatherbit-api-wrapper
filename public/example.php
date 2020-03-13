<?php
/**
 * Example of using the Weatherbit API Wrapper
 *
 * @see https://github.com/attogram/weatherbit-api-wrapper
 */
declare(strict_types=1);

use Attogram\Weatherbit\Weatherbit;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('../vendor/autoload.php');

$data = [];

$data['call']      = isset($_GET['call'])     ? $_GET['call']         : 'usage';
$data['key']       = isset($_GET['key'])      ? $_GET['key']          : '';
$data['units']     = isset($_GET['units'])    ? $_GET['units']        : 'M';
$data['language']  = isset($_GET['language']) ? $_GET['language']     : 'en';
$data['city']      = isset($_GET['city'])     ? $_GET['city']         : '';
$data['country']   = isset($_GET['country'])  ? $_GET['country']      : '';
$data['days']      = isset($_GET['days'])     ? intval($_GET['days']) : 2;
$data['ipAddress'] = isset($_GET['ip'])       ? $_GET['ip']           : '';
$data['latitude']  = isset($_GET['lat'])      ? $_GET['lat']          : '';
$data['longitude'] = isset($_GET['long'])     ? $_GET['long']         : '';

$check = ' checked';
$data['call_forecast'] = ($data['call'] == 'forecast') ? $check : '';
$data['call_current']  = ($data['call'] == 'current')  ? $check : '';
$data['call_usage']    = ($data['call'] == 'usage')    ? $check : '';
$data['units_M']       = ($data['units'] == 'M')       ? $check : '';
$data['units_I']       = ($data['units'] == 'I')       ? $check : '';
$data['units_S']       = ($data['units'] == 'S')       ? $check : '';

$data['pageTitle'] = 'weatherbit-api-wrapper v' . Weatherbit::VERSION;

printForm();

if (!isset($_GET['run']) || $_GET['run'] != 'test') {
    printFooter();
    exit;
}

$response = [];
$error = '';

$weatherbit = new Weatherbit();

try {
    $weatherbit->setKey($data['key']);

    if (!empty($data['units'] && $data['units'] != 'M')) {
        $weatherbit->setUnits($data['units']);
    }

    if (!empty($data['language'] && $data['language'] != 'en')) {
        $weatherbit->setLanguage($data['language']);
    }

    if (!empty($data['city'])) {
        $weatherbit->setLocationByCity($data['city'], $data['country']);
    } elseif (!empty($data['latitude'])) {
        $weatherbit->setLocationByLatitudeLongitude($data['latitude'], $data['longitude']);
    } elseif (!empty($data['ipAddress'])) {
        $weatherbit->setLocationByIP($data['ipAddress']);
    }

    switch ($data['call']) {
        case 'forecast':
            $response = $weatherbit->getDailyForecast($data['days']);
            break;
        case 'current':
            $response = $weatherbit->getCurrent();
            break;
        case 'usage':
            $response = $weatherbit->getUsage();
            break;
        default:
            $error = 'Invalid Call type';
            break;
    }
} catch (Exception $error) {
    $error = get_class($error) . ': ' . $error->getMessage();
}

printResults($response, $error);

printFooter();

function printResults($response, $error)
{
    global $weatherbit;

    print '<pre>API Call URL: <a href="' . $weatherbit->getUrl() . '" target="_blank">'
        . $weatherbit->getUrl() . '</a></pre>';

    if ($error) {
        print '<p style="background-color:lightsalmon;padding:10px;">ERROR: ' . $error . '</p>';
    }

    if ($response) {
        print '<pre style="background-color:lightgreen;padding:10px;">RESPONSE: ' . print_r($response, true) . '</pre>';
    }
}

function printForm()
{
    global $data;

    print '<html><head><title>' . $data['pageTitle'] . '</title></head><body>
    <h1>' . $data['pageTitle'] . '</h1>
    <form>
        <dl>
            <dt>API Call:<dt>
            <dd><input type="radio" name="call" value="forecast"' . $data['call_forecast']
                . '>Daily Weather Forecast (1-16 days)</input> - ' . Weatherbit::POSTFIX_FORECAST_DAILY . '</dd>
            <dd><input type="radio" name="call" value="current"' . $data['call_current']
                . '>Current Weather</input> - ' . Weatherbit::POSTFIX_CURRENT . '</dd>
            <dd><input type="radio" name="call" value="usage"' . $data['call_usage']
                . '>API Usage</input> - ' . Weatherbit::POSTFIX_USAGE . '</dd>
        </dl>

        API Key: <input name="key" type="text" value="' . htmlentities($data['key']) . '" size="35" /><br />

        <dl>
            <dt>Location by City:</dt>
                <dd> City: <input name="city" type="text" value="' . htmlentities($data['city'])
                    . '" size="20" /></dd>
                <dd>Country: <input name="country" type="text" value="' . htmlentities($data['country'])
                    . '" size="2" maxlength="2" /> (2 Letter Country Code) (optional)</dd>
            <br />
            <dt>Location By Latitude / Longitude:</dt>
                <dd>Latitude: <input name="lat" type="text" value="' . $data['latitude'] . '" size="20" /></dd>
                <dd>Longitude: <input name="long" type="text" value="' . $data['longitude'] . '" size="20" /></dd>
            <br />
            <dt>Location By IP Address:</dt>
                <dd>IP Address: <input name="ip" type="text" value="' . $data['ipAddress'] . '" size="20" /></dd>
        </dl>

        Forecast Days (1-16): <input name="days" type="text" value="' . $data['days'] . '" size="2" maxlength="2" />
        <br />
        
        Language: <input name="language" type="text" value="' . htmlentities($data['language'])
            . '" size="2" maxlength="2" /> (2 Letter Language code)
        <br />

        Units:
        <input type="radio" name="units" value="M"' . $data['units_M'] . '>M - Metric (Celcius, m/s, mm)</input>
        &nbsp;
        <input type="radio" name="units" value="I"' . $data['units_I'] . '>I - Imperial (Fahrenheit, mph, in)</input>
        &nbsp;
        <input type="radio" name="units" value="S"' . $data['units_S'] . '>S - Scientific (Kelvin, m/s, mm)</input>
        </select>
        <br />
        <br />
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
