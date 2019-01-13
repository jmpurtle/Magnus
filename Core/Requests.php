<?php
namespace Magnus\Core {
	
	class Requests {
		/* Requests is a request processing class for interpreting urls into path elements and additional
		 * arguments such as query strings and request bodies
		 */
		public $originalURL;
		public $headers;
		public $path;
		public $qsa;
		public $body;

		public function __construct($context, $signals) {
			if ($context['debug'] && $context['logger']) {
				$context['logger']->debug("Beginning request processing.");
			}

			$this->originalURL = $_SERVER['REQUEST_URI'];
			$this->headers = $this->extractHeaders();
			$this->path = $this->getPath();
			$this->qsa = $this->getQSA();
			$this->body = $this->getRequestBody();

			// Activate any request processing related extensions
			foreach($signals['prepare'] as $ext) {
				$ext->prepare($context);
			}

			if ($context['debug'] && $context['logger']) {
				$context['logger']->debug("Completed request processing.", get_object_vars($this));
			}
		}

		protected function extractHeaders() {
			if (!function_exists('getallheaders')) {
				if (!is_array($_SERVER)) {
		            return array();
		        }

		        $headers = array();
		        foreach ($_SERVER as $name => $value) {
		            if (substr($name, 0, 5) == 'HTTP_') {
		                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
		            }
		        }
		        return $headers;
			} 

			return getallheaders();
		}

		protected function getPath($url = null) {
			if ($url === null) {
				$url = $_SERVER['REQUEST_URI'];
			}
			// We don't want to capture the QSA for this part
			$path = explode('?', $url)[0];

			// Splitting the url into chunks for consumption in routing
			$path = explode('/', $path);

			// Empty elements aren't tasty, no siree, get rid of them
			return array_values(array_filter($path));
		}

		protected function getQSA() {
			return isset($_GET) ? $_GET : array();
		}

		protected function getRequestBody() {
			return isset($_POST) ? $_POST : array();
		}

	}
}