<?php

namespace Shop;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once '../../vendor/autoload.php';
require_once __DIR__ . '/src/User.php';
require_once __DIR__ . '/src/Cart.php';

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . '/src'],
    $isDevMode,
    null,
    null,
    false
);
$config->addEntityNamespace('Shop', 'Shop');
$conn = [
    'driver' => 'pdo_mysql',
    'user' => 'doctrine',
    'dbname' => 'doctrine',
    'password' => 'doctrine',
    'host' => '127.0.0.1'
];
$entityManager = EntityManager::create($conn, $config);
