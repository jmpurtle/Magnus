<?php
$packageRoot = dirname(__DIR__);
require_once $packageRoot . '/autoload.php';
?>
Feature: Request routing
	As a Website Builder
	I want to route web requests to my application
	So I can receive a handler and endpoint to dispatch upon

Scenario: Empty path handling
	<?php $router = new \Magnus\Core\Router(); ?>

	Given an empty path:
	<?php $path = array(); ?>

	When the next path chunk is requested:
	<?php foreach ($router->routeIterator($path) as list($previous, $current)) {} ?>

	Then the call should fail to set $previous and $current:
	<?php
	echo var_export((!isset($previous) && !isset($current)), true);
	?>

Scenario: One chunk path handling
	<?php $router = new \Magnus\Core\Router(); ?>

	Given a path with one chunk:
	<?php $path = array('foo'); ?>

	When the path chunk is processed:
	<?php foreach ($router->routeIterator($path) as list($previous, $current)) {} ?>

	Then the call should set $previous and $current to null and foo:
	<?php
	echo var_export(($previous === null && $current == 'foo'), true);
	?>

Scenario: Multiple chunk path handling
	<?php $router = new \Magnus\Core\Router(); ?>

	Given a path with more than one chunk:
	<?php $path = array('foo', 'bar'); ?>

	When the path chunk is processed:
	<?php foreach ($router->routeIterator($path) as list($previous, $current)) {} ?>

	Then the call should set $previous and $current to foo and bar:
	<?php
	echo var_export(($previous === 'foo' && $current == 'bar'), true);
	?>

Scenario: Object Descent routing with empty paths
	<?php $router = new \Magnus\Core\Router(); ?>

	Given an empty path:
	<?php $path = array(); ?>

	And a Root Controller Object:
	<?php $rootObject = new \Utils\Testing\RootController(); ?>

	When routed:
	<?php
	foreach ($router($rootObject, $path) as list($previous, $obj, $isEndpoint)) {
		if ($isEndpoint) { break; }
	}
	?>

	Then the call should result in a handler of the original root object, indicating __invoke() should be called:
	<?php
	echo var_export((
		$previous == null &&
		get_class($obj) == "Utils\Testing\RootController" &&
		$isEndpoint === false
	), true); 
	?>

Scenario: Object Descent routing with controller reference instead of object
	<?php $router = new \Magnus\Core\Router(); ?>

	Given an empty path:
	<?php $path = array(); ?>

	And a Root Controller Object reference:
	<?php $rootObject = '\\Utils\\Testing\\RootController'; ?>

	When routed:
	<?php
	foreach ($router($rootObject, $path) as list($previous, $obj, $isEndpoint)) {
		if ($isEndpoint) { break; }
	}
	?>

	Then the call should result in a handler of the original root object, indicating __invoke() should be called:
	<?php
	echo var_export((
		$previous == null &&
		get_class($obj) == "Utils\Testing\RootController" &&
		$isEndpoint === false
	), true); 
	?>

Scenario: Object Descent routing with a property referring to a controller
	<?php $router = new \Magnus\Core\Router(); ?>

	Given a path with one chunk:
	<?php $path = array('foo'); ?>

	And a Root Controller Object:
	<?php $rootObject = new \Utils\Testing\RootController(); ?>

	And this Root Controller object contains a property named foo:
	<?php echo var_export(property_exists($rootObject, 'foo'), true); ?>

	And foo refers to another controller by name:
	<?php echo var_export($rootObject->foo === '\\Utils\\Testing\\FooController', true); ?>

	And this referenced controller exists:
	<?php echo var_export(class_exists($rootObject->foo), true); ?>

	When routed:
	<?php
	foreach ($router($rootObject, $path) as list($previous, $obj, $isEndpoint)) {
		if ($isEndpoint) { break; }
	}
	?>

	Then the call should result in a handler of FooController, indicating __invoke() should be called:
	<?php
	echo var_export((
		$previous == null &&
		get_class($obj) == "Utils\Testing\FooController" &&
		$isEndpoint === false
	), true); 
	?>

