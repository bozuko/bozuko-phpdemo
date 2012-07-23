<?php

// set up the globals
require(dirname(__FILE__).'/lib/Bozuko/Api.php');
$config = include(dirname(__FILE__).'/config.php');
$api = new Bozuko_Api();

// globals for examples
$scripts = array();
$styles = array();

// configure

/**
 * Mark's Playground
 */
$api->setServer($config['server']);
$api->setApiKey($config['key']);
$api->setApiSecret($config['secret']);

/**
 * Chinoki Playground
 */
/*
$api->setServer('https://chinoki.bozuko.com');
$api->setApiKey('CxRi8sOZqwTaq0TtKHaS6LKqNIRIfDow');
$api->setApiSecret('sMZuUUudDlnyrOBZJgZTvsJQxavBTSNs');
*/