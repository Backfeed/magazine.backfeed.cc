<?php
/**
 * Controller Factory Class
 * @author Flipper Code <hello@flippercode.com>
 * @package Avatar
 */

if ( ! class_exists( 'FactoryControllerPMP' ) ) {

	/**
	 * Controller Factory Class
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 4.0.0
	 * @package Avatar
	 */
	class FactoryControllerPMP extends AbstractFactoryFlipperCode {
		/**
		 * FactoryController constructer.
		 */
		public function __construct() {
		}
		/**
		 * Create controller object by passing object type.
		 * @param  string $objectType Object Type.
		 * @return object         Return class object.
		 */
		public function create_object($objectType) {

			switch ( $objectType ) {

				default : if ( file_exists( WPUAP_CORE_CONTROLLER_CLASS ) ) {
						  require_once( WPUAP_CORE_CONTROLLER_CLASS ); }
				if ( class_exists( 'WPUAP_Core_Controller' ) ) {
					return new WPUAP_Core_Controller( $objectType ); }
						  break;

			}

		}

	}
}
