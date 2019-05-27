<?php
namespace \Magnus\Core {
	/* Magnus extension management.
	 *
	 * This extension registry handles loading and access to extensions as well as 
	 * the collection of standard Magnus Extension API callbacks. Reference the
	 * `SIGNALS` constant for a list of the individual callbacks that can be
	 * utilized and their meanings, and the `extensions.php` example for more
	 * more detailed descriptions.
	 *
	 * At the basic level, an extension is a class. That's it; attributes and
	 * methods are used to inform the manager of extension metadata and register
	 * callbacks for certain events. The most basic extension is one that does
	 * nothing:
	 *
	 * class Extension {}
	 *
	 * Your extension may define several additional properties:
	 *
	 * `provides` - declare a set of tags describing the features offered by
	 * the plugin
	 * `needs` - declare a set of tags that must be present for this extension
	 * to function appropriately
	 * `uses` - declare a set of tags that must be evaluated prior to this 
	 * extension but aren't hard requirements
	 * `first` - declare that this extension is a dependency of all other
	 * non-first extensions if truthy. Therefore evaluated as close to firstly
	 * as possible
	 * `last` - declare that this extension depends on all other non-last
	 * extensions if truthy. Therefore evaluated as close to lastly as possible
	 * `signals` - a set of additional signal names declared used by the manager
	 */

	// Extension Manager

	class WebExtensions {

		const SIGNALS = array(
			'start', // Executed during Application construction
			'stop', // Executed when the script stops
			'prepare', // Executed during initial request processing
			'before', // Executed after all `prepare`, prior to routing
			'route', // Executed once for each route event,
			'-mutate', // Executed prior to dispatch
			'-after', // Executed after dispatch, prior to negotiation
			'-transform', // Transforms the result returned and applies to resp.
			'-done' // Executed after sending response to client
		);
		public $feature;
		public $all;
		public $signal;

		public function __construct($context) {

			/* Extension registry constructor.
			 * 
			 * The extension registry is not meant to be instantiated by
			 * third-party software. Instead, access the registry as an
			 * attribute of the current Application or Request context:
			 * `context.extension`
			 *
			 * Currently, this uses some application-internal
			 * shenanigans to construct the initial extension set.
			 */

			$this->feature = array();
			// Dependency ordered
			$this->all = $this->order($context['app']['config']['extensions']);

			// Populate the initial set of signals from our own.
			$signals = array();
			$inverse = array();

			foreach (SIGNALS as $signal) {
				if (strpos($signal, '-') === 0) {
					$inverse[] = $signal;
				} else {
					$signals[$signal] = array();
				}
			}

			foreach ($this->all as $ext) {

				// Populate additional signals and general metadata
				if (isset($ext->provides)) {
					$this->feature[] = $ext->provides;
				} else {
					$this->feature[] = array();
				}

				if (isset($ext->signals)) {
					foreach ($ext->signals as $signal) {
						if (strpos($signal, '-') === 0) {
							$inverse[] = $signal;
						} else {
							$signals[$signal] = array();
						}
					}
				}
			}

			// Prepare the callback cache
			foreach ($this->all as $ext) {
				foreach ($signals as $signal) {
					if (isset($ext[$signal])) {
						$signals[$signal][] = $ext[$signal];
					}
				}
			}

			/* Certain operations act as a stack, i.e. "before" are executed
			 * in dependency order, but "after" are executed in reverse
			 * dependency order. This is also the case with "mutate" (incoming)
			 * and "transform" (outgoing).
			 */
			foreach ($inverse as $signal) {
				array_reverse($signals[$signal]);
			}

			$this->signal = $signals;

		}

		protected function order(Array $config = null, $prefix = '') {
			return $config;
		}

	}
}

