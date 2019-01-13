<?php
namespace Magnus\Core {
	
	class Dispatch {

		public $dispatchResponse = array();
		protected $context;
		protected $logger;

		public function __construct($context, $logger) {
			$this->context = $context;
			$this->logger  = $logger;
		}

		public function __invoke($context, $obj, $previous, $signals) {
			/* During invocation, this Dispatcher takes in the application context, the routed object,
			 * the final chunk emitted by routing and the signals set.
			 */

			// Instantiating a class reference if returned from router
			if (!is_object($obj) && !is_array($obj) && class_exists($obj)) {
				if ($context['debug'] && $this->logger) {
		            $this->logger->debug('Instantiating routed class reference', [
		                'context' => $context,
		                'current' => $obj
		            ]);
		            
		        }

				$obj = new $obj($context);
			}

			if (is_object($obj)) {

				if (in_array($previous, get_class_methods($obj))) {

					if ($context['debug'] && $this->logger) {
				        $this->logger->debug('Generating dispatch response from obj->method', [
				        	'context' => $context,
				            'obj'     => $obj,
				            'method'  => $previous
				        ]);
				        
		        	}

					$this->dispatchResponse = $obj->$previous();

				} else if (in_array('__invoke', get_class_methods($obj))) {

					if ($context['debug'] && $this->logger) {
				        $this->logger->debug('Generating dispatch response from obj->__invoke', [
				        	'context' => $context,
				            'obj'     => $obj
				        ]);
				        
		        	}

					$this->dispatchResponse = $obj->__invoke();

				}

			}

			if (is_array($obj)) {
				$this->dispatchResponse = $obj;
			}

		}

		public function getFinalResponse($context, $response = null) {
			if ($response === null) {
				// Creates a default response to use
				$response = [
					'HTTPStatusCode' => '404',
					'view'           => 'error/noResource',
					'context'        => $context
				];
			}

			if (is_array($this->dispatchResponse)) {

				if ($context['debug'] && $this->logger) {
			        $this->logger->debug('Merging array returned from dispatch with response', [
			        	'context' => $context,
			        	'context' => $this->dispatchResponse
			        ]);
		    	}

				$response = array_merge($response, $this->dispatchResponse);

			} else if ($this->dispatchResponse instanceof \Traversable) {
				/* for sake of simplicity, we assume generators do not emit anything but arrays. In the future, generators may 
				 * yield static elements, functions, objects, generators and more.
				 */
				if ($context['debug'] && $this->logger) {
			        $this->logger->debug('Generator returned from dispatch, iterating to build response', [
			        	'context' => $context,
			        ]);
		    	}

				foreach ($dispatchResponse() as $chunk) {
					$response = array_merge($response, $chunk);
				}

			}

			if ($context['debug'] && $this->logger) {
		        $this->logger->debug('Final server response is', [
		        	'context' => $context,
		        	'response' => $response
		        ]);
			}

			return $response;
		}
	}

}
