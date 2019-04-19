<?php
// vendor at component dir
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require dirname(__DIR__) . '/vendor/autoload.php';
    // application's vendor
} elseif (file_exists(dirname(__DIR__, 5) . '/autoload.php')) {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require dirname(__DIR__, 5) . '/autoload.php';

    // need load testing psr4 config map
    $componentDir  = dirname(__DIR__, 3);
    $componentJson = $componentDir . '/composer.json';
    $composerData  = json_decode(file_get_contents($componentJson), true);

    foreach ($composerData['autoload-dev']['psr-4'] as $prefix => $dir) {
        $loader->addPsr4($prefix, $componentDir . '/' . $dir);
    }
} else {
    exit('Please run "composer install" to install the dependencies' . PHP_EOL);
}

// Composer autoload
$autoloadFile = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadFile)) {
    require_once $autoloadFile;
}

\Swoole\Runtime::enableCoroutine();
$application = new \Swoft\Test\TestApplication();
$application->setBeanFile(__DIR__ . '/testing/bean.php');
$application->run();