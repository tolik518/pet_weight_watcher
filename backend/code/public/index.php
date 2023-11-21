<?php

namespace oszimt\petweightwatcher;

require_once dirname(__DIR__) .'/vendor/autoload.php';
header('Content-Type: application/json');

$factory = new Factory();
$router = $factory->createRouter();

$router->getPage();