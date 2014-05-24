<?php
chdir(dirname(__DIR__) . '/../../../');
include 'vendor/autoload.php';

if(!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'testing');

//load testing config on top
$config = include 'config/application.config.php';
$config['module_listener_options']['config_glob_paths'][] = 'config/autoload/{,*.}testing.php';
$application = Zend\Mvc\Application::init($config);