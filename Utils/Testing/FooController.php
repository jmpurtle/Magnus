<?php
namespace Utils\Testing {
	
	class FooController {

		private $context;

		public function __construct($context = null) {
			$this->context = $context;
		}

		public function __invoke($path = array(), $context = null) {

			return array();

		}

		public function __get($id) {
			return new BazController($this->context, $id);
		}

	}
	
}
