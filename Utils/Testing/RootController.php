<?php
namespace Utils\Testing {
	
	class RootController {

		private $context;
		public $foo = '\\Utils\\Testing\\FooController';

		public function __construct($context = null) {
			$this->context = $context;
		}

		public function __invoke($path = array(), $context = null) {

			return array();

		}

	}
	
}
