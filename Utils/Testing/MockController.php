<?php
namespace Utils\Testing {
	
	class MockController {

		public $context;

		public function __construct($context) {
			$this->context = $context;
		}

		public function endpoint($a, $b) {
			return (string) (intval($a) * intval($b));
		}

		public function sum($v) {
			$sum = 0;
			foreach ($v as $i) {
				$sum += intval($i);
			}
		}

		public function notmod() {
			return 'HTTPNotModified';
		}

		public function rich($kwargs = array('data' => null)) {
			if (!isset($kwargs['data']) || !$kwargs['data']) {
				return null;
			}

			return json_encode(array('result' => $kwargs['data']));
		}

	}
	
}
