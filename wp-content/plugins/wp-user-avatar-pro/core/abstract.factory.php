<?php
/**
 * Factory Classes
 * @author Flipper Code <hello@flippercode.com>
 * @package Avatar
 */

if ( ! class_exists( 'AbstractFactoryFlipperCode' ) ) {

	/**
	 * Factory Class Abstract
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 4.0.0
	 * @package Avatar
	 */
	abstract class AbstractFactoryFlipperCode {
		/**
		 * Abstrct create object
		 * @param  string $object Object Type.
		 * @return object         Return class object.
		 */
		abstract public function create_object($object);
	}
}
