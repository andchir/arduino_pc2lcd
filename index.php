<?php

namespace ArPC2LCD;

use ArPC2LCD\Controllers\BaseController;

require_once __DIR__ . '/vendor/autoload.php';

$controller = new BaseController();

$controller->printOnLCD();
