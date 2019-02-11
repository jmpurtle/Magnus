<?php
$packageRoot = dirname(__DIR__);
require_once $packageRoot . '/autoload.php';
?>
Feature: Request Dispatching
		 As a Website Builder
		 I want to dispatch my requests
		 So I can retrieve a response to render

Scenario: Static value dispatching
	<?php $dispatch = new \Magnus\Core\Dispatch(); ?>

	Given a static routed result:
	<?php
	$previous = null;
	$obj = 'hi';
	$isEndpoint = true;
	?>

	When dispatched:
	<?php $result = $dispatch($previous, $obj); ?>

	Then the call should result in "hi":
	<?= var_export(($result == "hi"), true); ?>

<?= "\n\n"; ?>