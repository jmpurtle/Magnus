<?php
namespace Utils\Testing {
	
	class RootController {

		private $context;
		public $foo = '\\Utils\\Testing\\FooController';
		public $qux = 'A static value';

		public function __construct($context = null) {
			$this->context = $context;
		}

		public function bar($path = array(), $context = null) {

			return array();

		}

		public function __invoke($path = array(), $context = null) {

			return array();

		}

	}
	
}
