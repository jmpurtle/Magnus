<?php
$packageRoot = dirname(__DIR__);
require_once $packageRoot . '/autoload.php';

function printEval($expr) {
	return var_export($expr, true);
}
?>
Feature: Application
		 As a Website Builder
		 I want to build an application
		 So I can serve requests

Scenario: Loading blank application configuration

	Given an initialized application:
	<?php $app = new \Magnus\Core\Application(null); ?>

	Then the application should have just the blank extensions registry:
	<?= printEval($app->config == array('extensions' => array())); ?>

Scenario: Loading an application configuration

	Given an initialized application with configuration passed in:
	<?php $app = new \Magnus\Core\Application(null, array('foo' => 'bar')); ?>

	Then the application should have extensions plus config passed in:
	<?= printEval($app->config['foo'] == 'bar' && isset($app->config['extensions'])); ?>

Scenario: Loading an application configuration with extensions

	Given an initialized application with configuration passed in:
	<?php $app = new \Magnus\Core\Application(null, array('extensions' => array('bar'))); ?>

	Then the application should have extensions plus config passed in:
	<?= printEval($app->config['extensions'][1] == 'bar'); ?>

Scenario: Including BaseExtension by default

	Given an initialized application:
	<?php $app = new \Magnus\Core\Application(null); ?>

	Then the application should have a BaseExtension in it:
	<?= printEval(is_a($app->config['extensions'][0], 'Magnus\\Extensions\\BaseExtension')); ?>

<?= "\r\n"; ?>