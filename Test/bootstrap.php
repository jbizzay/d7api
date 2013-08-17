<?php
// Show all errors
error_reporting(-1);
$conf['error_level'] = 2;
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

// $ phpunit --bootstrap bootstrap.php .
// Bootstrap drupal for all Latch library tests
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_SOFTWARE'] = 'nginx/1.2.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
define('DRUPAL_ROOT', __DIR__ .'/../../../../../');
require_once DRUPAL_ROOT .'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
