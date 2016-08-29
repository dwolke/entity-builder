#!/usr/bin/env php
<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use ZF\Console\Application;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require PROJECT_ROOT . '/vendor/autoload.php';

$application = new Application(
    'Entity Builder',
    'V1.0.1',
    include PROJECT_ROOT . '/config/routes.php'
);
$application->setFooter('');

// run application
$exit = $application->run();
exit($exit);
