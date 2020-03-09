<?php
/**
 * Test for Weatherbit API Wrapper
 */
declare(strict_types = 1);

use \Attogram\Weatherbit\Weatherbit;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('../src/Weatherbit.php');

$call    = isset($_GET['call']) ? $_GET['call'] : '';
$key     = isset($_GET['key']) ? $_GET['key'] : '';
$city    = isset($_GET['city']) ? $_GET['city'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$days    = isset($_GET['days']) ? intval($_GET['days']) : 1;
$ip      = isset($_GET['ip']) ? $_GET['ip'] : $_SERVER['REMOTE_ADDR'];

$pageTitle= 'Weatherbit v' . Weatherbit::VERSION . ' web test';

?>
<html>
  <head>
    <title><?= $pageTitle ?></title>
  </head>
  <body>
    <h1><?= $pageTitle ?></h1>
    <form>
        Call: <select name="call">
            <option value="forecast"<?= $call == 'forecast' ? ' selected="selected"' : '' ?>>Daily Weather Forecast</option>
            <option value="current"<?= $call == 'current' ? ' selected="selected"' : '' ?>>Current Weather</option>
            <option value="usage"<?= $call == 'usage' ? ' selected="selected"' : '' ?>>API Usage</option>
        </select><br /><br />
        API Key: <input name="key" type="text" value="<?= htmlentities($key) ?>" size="35" /><br /><br />
        City: <input name="city" type="text" value="<?= htmlentities($city) ?>" size="20" /><br /><br />
        Country: <input name="country" type="text" value="<?= htmlentities($country) ?>" size="2" maxlength="2" /> (2 Letter Country Code)<br /><br />
        Forecast Days (1-16): <input name="days" type="text" value="<?= $days ?>" size="2" maxlength="2" /><br /><br />
        IP: <input name="ip" type="text" value="<?= $ip ?>" size="20" />
        <br /><br />
        <input type="hidden" name="run" value="test" />
        <input type="submit" value="  Get Weatherbit API Response  " />
        &nbsp; &nbsp; <a href="<?= $_SERVER['PHP_SELF'] ?>">reset</a>
    </form>
<?php

if (!isset($_GET['run']) || $_GET['run'] != 'test') {
    testFooter();
    exit;
}

$data = [];
$error = '';

try {
    $weatherbit = new Weatherbit();
    $weatherbit->setKey($key);
    switch($call) {
        case 'forecast':
            $weatherbit->setCity($city);
            $weatherbit->setCountry($country);
            $data = $weatherbit->getDailyForecast($days);
            break;
        case 'current':
            $weatherbit->setCity($city);
            $weatherbit->setCountry($country);
            $data = $weatherbit->getCurrent();
            break;
        case 'usage':
            $data = $weatherbit->getUsage();
            break;
        default:
            throw new Exception('Invalid Call type');
            break;
    }
} catch (Exception $error) {
    $error = $error->getMessage();
}

if ($error) {
    print '<p style="background-color:lightsalmon;padding:10px;">ERROR: ' . $error . '</p>';
}

if ($data) {
    print '<pre style="background-color:lightgreen;padding:10px;">Data: ' . print_r($data, true) . '</pre>';
}

testFooter();

function testFooter()
{
    print '<br /><hr />'
        . '<a href="https://github.com/attogram/weatherbit-api-wrapper/" target="_blank">'
            . 'https://github.com/attogram/weatherbit-api-wrapper/</a>'
        . '<br /><br />' . gmdate('Y-m-d H:i:s') . ' UTC' 
        . '</body></html>';
}
