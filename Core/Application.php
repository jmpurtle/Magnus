<?php
namespace Magnus\Core {
	
	class Application {

		public $context = array(
			'debug'              => false,
			'parse'              => 'Requests',
			'route'              => 'ObjectRouter',
			'dispatch'           => 'SimpleDispatch',
			'contentNegotiation' => 'Accepts',
			'render'             => 'Echo',
			'extensions'         => array(
				'signals' => array(
					'start'     => array(), //Executed during Application construction
					'prepare'   => array(), //Executed during initial request processing
					'route'     => array(), //Executed during request routing
					'dispatch'  => array(), //Executed during request dispatching
					'negotiate' => array(), //Executed during request content negotiation
					'transform' => array(), //Executed during templating or response transformation
					'done'      => array() //Executed after transmitting response to client
				)
			)
		);

		public function __construct($root, $logger = null, $config = array()) {
			/* Creates the initial application context and populates values.
			 * No actions other than configuration should occur at this time
			 *
			 * Current configuration is limited to three arguments:
			 *
			 * `root` -- The object used as the starting point of routing each request
			 * `logger` -- either null to indicate no logging on this application (not recommended) or a Logger
			 * object that implements PSR-3, this is essentially eight methods, debug, info, notice, warning,
			 * error, critical, alert and emergency. If a logger is provided, the appropriate method will be 
			 * employed as necessary.
			 * `config` -- These are all the contextual properties and values you wish to provide in addition
			 * to standard ones. For example, if your application uses databases, you may pass database 
			 * credentials into it. Application version numbers are another example.
			 */

			$config['logger'] = $logger;
			$this->context = array_merge($this->context, $config);
			$this->context['root'] = $root;

			if ($this->context['debug'] && $this->context['logger']) {
				$logger->debug("Initial Magnus application config prepared.");
			}

			/* Execute extension startup callbacks. This is when your extension needs things from
			 * the application context. Extensions should have no expectation of modifying
			 * the original application context.
			 */
			foreach ($this->context['extensions']['signals']['start'] as $ext) {
				$ext->start($this->context);
			}

			if ($this->context['debug'] && $this->context['logger']) {
				$logger->debug("Magnus application prepared.");
			}
		}

		public function run() {
			/* Processes a single request/response cycle. The request/response cycle is composed of
			 * core extension signals triggering extensions as necessary. You can see the full lifecycle
			 * in Magnus/Extensions/Base.php (currently in $context->extensions). 
			 */

			// Begin request parsing
			$request = new \Magnus\Core\Requests($this->context, $this->context['extensions']['signals']);
			$obj = $this->context['root'];
			echo var_export($obj, true);

		}
	}

}