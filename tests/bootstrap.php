<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ?: 'test'));

require_once __DIR__.'/../vendors/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Hoborg\\Test' => __DIR__ . '/unit',
    'Hoborg' => __DIR__ . '/../src',
));
$loader->register();
