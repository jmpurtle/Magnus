<?php
$packageRoot = dirname(__DIR__);
require_once $packageRoot . '/autoload.php';
?>
Feature: Application
		 As a Website Builder
		 I want to build an application
		 So I can serve requests

Scenario: Loading blank application configuration

	Given an initialized application:
	<?php $app = new \Magnus\Core\Application(null); ?>

	Then the application should have just the blank extensions registry:
	<?= var_export($app->config == array('extensions' => array()), true); ?>

Scenario: Loading an application configuration

	Given an initialized application with configuration passed in:
	<?php $app = new \Magnus\Core\Application(null, array('foo' => 'bar')); ?>

	Then the application should have extensions plus config passed in:
	<?= var_export($app->config == array('foo' => 'bar', 'extensions' => array()), true); ?>

	