<?php
require_once "vendor/autoload.php";

use HackThursday\Handler\FacebookHandler;

if (!isset($argv[1])) {
    throw new Exception('You need to pass the cookie file path');
}

$facebook = new FacebookHandler($argv[1]);

$facebook->login('email', 'pass');

//example request
$string = $facebook->request('https://facebook.com');