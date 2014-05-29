<?php

putenv("SERVER_TYPE=test");

$applicationRoot = '/../';

chdir(__DIR__ . $applicationRoot);

$loader = require_once('vendor/autoload.php');

$loader->add('Zoop\Promotion\Test', __DIR__);
