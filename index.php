<?php

namespace ArPC2LCD;

use ArPC2LCD\Controllers\BaseController;

require_once __DIR__ . '/vendor/autoload.php';

$arguments = PHP_SAPI == 'cli' && !empty( $argv )
    ? $argv
    : $_GET;
$controller = new BaseController();

$controller->run( $arguments );
