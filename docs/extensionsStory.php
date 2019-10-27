<?php
$packageRoot = dirname(__DIR__);
require_once $packageRoot . '/autoload.php';

function printEval($expr) {
	return var_export($expr, true);
}
?>
Feature: Magnus Extensions Manager
		 As a Website Builder
		 I want to extend Magnus
		 So I can execute special functionality during the request lifecycle


Scenario: Loading blank MagnusExtensions

	Given an initialized MagnusExtensions:
	<?php $exts = new \Magnus\Core\MagnusExtensions(null); ?>

	Then the Extensions Manager should initialize successfully:
	<?= printEval(isset($exts)); ?>

	And signal should contain multiple signal keywords:
	<?= printEval(
		$exts->signal['start'] == array() &&
		$exts->signal['prepare'] == array() &&
		$exts->signal['route'] == array() &&
		$exts->signal['dispatch'] == array() &&
		$exts->signal['before'] == array() &&
		$exts->signal['mutate'] == array() &&
		$exts->signal['-after'] == array() &&
		$exts->signal['-transform'] == array() &&
		$exts->signal['-done'] == array()
	); ?>


