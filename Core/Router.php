<?php
namespace Magnus\Core {
	
	class Router {

		public function __construct() {

		}

		public function routeIterator(&$path) {

			/* Iterate through the path, popping elements from the left as they are seen. */

			$last = null;

			while ($path) {
				yield [$last, $path[0]];
				$last = array_shift($path);

				/* By shifting elements after they're yielded, we avoid having to put back a value in the event of a
				 * readjustment in the routing path performed by the __construct() method in route objects. Our testing
				 * has shown that direct array maninpulation is more performant than implementing SPLDoublyLinkedList
				 * for deque behavior. Likewise, just tracking the array index is a bit slower and adds complexity
				 * when dealing with reorients or reroutes.
				 */
			}

		}

		public function __invoke($obj, Array $path) {

			$previous   = null;
			$current    = null;
			$isEndpoint = false;

			$routeIterator = $this->routeIterator($path);

			foreach ($routeIterator as list($previous, $current)) {

				// This section would only be hit if there's more than one element in the path
				if (!is_object($obj)) {
					if (class_exists($obj)) {
						$obj = new $obj();
					} else {
						yield [$previous, $obj, true];
						return;
					}
				}

				if (array_key_exists($current, get_object_vars($obj))) {
					yield [$previous, $obj, $isEndpoint];
					$obj = $obj->$current;
					continue;
				}

				yield [$previous, $obj, $isEndpoint];
			}

			// A little duplication but the performance hit of extracting isn't worth it
			// We've run out of path elements to consume (if any)
			if (!is_object($obj)) {
				if (class_exists($obj)) {
					$obj = new $obj();
				} else {
					yield [$previous, $obj, true];
					return;
				}
			}

			yield [$previous, $obj, $isEndpoint];
			return;

		}
		
	}

}