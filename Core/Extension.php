<?php
namespace Magnus\Core {

	/* Magnus extension management
	 *
	 * This extension registry handles loading and access to extensions as
	 * well as the collection of standard Magnus Extension API callbacks.
	 * Reference the `SIGNALS` constant for a list of the individual
	 * event callback names that can be utilized and their meanings, and 
	 * the `extensions.php` example for more detailed descriptions.
	 *
	 * At the basic level, an extension is a class. That's it; attributes 
	 * and methods are used to inform the manager of extension metadata 
	 * and register callbacks for certain events. The most basic extension
	 * is one that does nothing:
	 *
	 * class Extension {}
	 *
	 * To register your extension, add it to your configuration's 
	 * extensions key:
	 *
	 * $config['extensions'] = array(
	 *     'myNamespace\myExtension'
	 * );
	 *
	 * Your extension may define several additional properties:
	 *
	 * - `provides` -- declare a set of tags describing the features
	 * offered by the plugin
	 * - `needs` -- declare a set of tags that must be present for this
	 * extension to function
	 * - `uses` -- declare a set of tags that must be evaluated prior to
	 * this extension, but aren't hard requirements
	 * - `first` -- declare that this extension depends on all other 
	 * non-last extensions if truthy
	 * - `last` -- declare that this extension depends on all non-first
	 * first extensions if truthy
	 * - `signals` -- a set of additional signal names declared for
	 * use and cacheable by the extension manager
	 *
	 * Tags used as `provides` value should also be registered in your
	 * configuration extensions key. Additional signals may be
	 * prefixed with a minus symbol (-) to request reverse ordering.
	 */

	class MagnusExtensions {

		// Principal Magnus extension manager

		// Optional extension callback attributes
		const SIGNALS = array(
			'start', // Executed during Application construction
			'prepare', // Executed during initial request processing
			'route', // Executed during the routing process
			'dispatch', // Executed during the resolving step when dispatching endpoints
			'before', // Executed after all extension `prepare` methods have been called, prior to routing
			'mutate', // Inspect and potentially mutate arguments prior to dispatching
			'-after', // Executed after dispatch has returned and a response populated
			'-transform', // Transform the result returned by the dispatch handler and apply it to the response
			'-done', // Executed after the response has been consumed by the client (clean up)
		);

		public function __construct($context = null) {
			/* Extension registry constructor.
			 *
			 * The extension registry is not meant to be instantiated by
			 * third-party software. Instead, access the registry as an
			 * attribute of the current Application or Request context:
			 * 'context.extension'
			 */

			$signals = array();
			$inverse = array();

			// Populate the initial set of signals from our own
			foreach ($this::SIGNALS as $signal) {
				$signalSet = $this->add_signal($signal);
				$signals = array_merge($signals, $signalSet[0]);
				$inverse = array_merge($inverse, $signalSet[1]);
			}

			$this->signal = $signals;

		}

		protected function add_signal($name) {

			$signals = array();
			$inverse = array();
			
			if (substr($name, 0) == '-') {
				$name = substr($name, 1);
				$inverse[] = $name;
			}

			$signals[$name] = array();

			return array($signals, $inverse);
		}

	}

}
