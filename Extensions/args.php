<?php
namespace Magnus\Extensions {
	
	class ArgumentExtension {
		// Not for direct use
	}

	class ValidateArgumentsExtension {
		/* Use this to enable validation of endpoint arguments.
		 * 
		 * You can determine when validation is executed (never, always
		 * or developmment) and what action is taken when a conflict occurs.
		 */

		public $last = true;
		public $provides = array('args.validation', 'kwargs.validation');

		public function __construct($kwargs = array('enabled' => 'development', 'correct' => false)) {
			/* Configure when validation is performed and the action performed.
			 *
			 * If `enabled` is true, validation will always be performed, if
			 * false, never. If set to `development`, the callback will not
			 * be assigned and no code will be executed during runtime.
			 *
			 * When `correct` is falsy (the default), a `NotFound` will be 
			 * returned if a conflict occurs. If truthy, the conflicting
			 * arguments are removed
			 */

		}
	}

	class ContextArgsExtension extends ArgumentExtension {
		// Adds the context as the first positional arg, conditionally

		public $first    = true;
		public $provides = array('args.context');
		public $always;

		public function __construct($always = false) {
			/* Configure the conditions under which the context is added to
			 * endpoint positional arguments.
			 *
			 * When `always` is truthy, the context is always included,
			 * otherwise it's only included for callables that are
			 * not bound methods
			 */
			$this->always = $always;
		}
		
	}

}