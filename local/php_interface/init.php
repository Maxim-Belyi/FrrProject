<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

try {
    \Xpage\Local::registerEventsHandlers();
} catch (\Throwable $e) {
    \Xpage\Tools::log($e, 'init', 'errors');

}


