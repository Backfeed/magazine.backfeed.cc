<?php
/**
 * Model Factory Class
 * @author Flipper Code <hello@flippercode.com>
 * @package Avatar
 */

if ( ! class_exists( 'FactoryModelPMP' ) ) {

	/**
	 * Model Factory Class
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 4.0.0
	 * @package Avatar
	 */
	class FactoryModelPMP extends AbstractFactoryFlipperCode{
		/**
		 * FactoryModel constructer.
		 */
		public function __construct() {

		}
		/**
		 * Create model object by passing object type.
		 * @param  string $objectType Object Type.
		 * @return object         Return class object.
		 */
		public function create_object($objectType) {
			switch ( $objectType ) {

				default:
					require_once( WPUAP_MODEL.$objectType.'/model.'.$objectType.'.php' );
					$object = 'WPUAP_Model_'.$objectType;

				return new $object();
				break;
			}

		}

	}
}
