<?php
/**
 * Core class file to get facebook controls.
 */
if ( ! class_exists( 'FBcontrols' ) ) :
	/**
	 * Class defination for facebook controls.
	 */
	class FBcontrols {
		/**
		 * Default constructor
		 */
		private function __construct() {}

		/**
		 * This function will return all the necessary info to generate login button or url in application.
		 *
		 * @return Array
		 */
		public static function get_login_control() {
			if ( empty( FacebookConfig::$permissions ) || ! is_array( FacebookConfig::$permissions ) ) {
				// if permissions are missing, set it to default.
				FacebookConfig::set_permissions();
			}

			// callback url check.
			if ( FacebookConfig::$callback_url === '' ) {
				throw new Exception( 'No valid callback url found.' );
			}

			// more info: https://developers.facebook.com/docs/php/FacebookRedirectLoginHelper/5.0.0.
			$fb = FBloader::instance();

			$helper = $fb->getRedirectLoginHelper();

			$login_url = $helper->getLoginUrl( FacebookConfig::$callback_url, FacebookConfig::$permissions );

			return [
				'login_url'    => $login_url,
				'callback_url' => FacebookConfig::$callback_url,
				'permissions'  => FacebookConfig::$permissions,
			];
		}
	}
endif;
