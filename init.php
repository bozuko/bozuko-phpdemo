<?php

// set up the globals
require(dirname(__FILE__).'/lib/Bozuko/Api.php');
$api = new Bozuko_Api();

// globals for examples
$scripts = array();
$styles = array();

// configure

/**
 * Mark's Playground
 */

$api->setServer('https://playground.bozuko.com:8001');
$api->setApiKey('MSqJ31pCgiwuMslSLSk9uxBkGc0kOd4v');
$api->setApiSecret('ULmUdpCUivvmOUAlUzxfCFWaGUl0iwec');

/**
 * Chinoki Playground
 */
/*
$api->setServer('https://chinoki.bozuko.com');
$api->setApiKey('CxRi8sOZqwTaq0TtKHaS6LKqNIRIfDow');
$api->setApiSecret('sMZuUUudDlnyrOBZJgZTvsJQxavBTSNs');
*/