<?php
namespace Utils\Testing {
	
	class RootController {

		private $context;
		public $foo = '\\Utils\\Testing\\FooController';
		public $qux = 'A static value';

		public function __construct($context = null) {
			$this->context = $context;
		}

		public function bar($context = null, Array $args = array()) {

			return array('bar');

		}

		public function __invoke($context = null, Array $args = array()) {

			return array('__invoke');

		}

	}
	
}
