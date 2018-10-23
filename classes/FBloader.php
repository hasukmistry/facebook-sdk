<?php
/**
 * Core class file to create object and initialize facebook.
 */
if ( ! class_exists( 'FBloader' ) ) :
	/**
	 * Class defination for loading facebook sdk object.
	 */
	final class FBloader {
		/**
		 * Create null object for \Facebook\Facebook.
		 *
		 * @var Object
		 */
		private static $instance = null;

		/**
		 * Default constructor
		 */
		private function __construct() {}

		/**
		 * Initialise object for \Facebook\Facebook.
		 *
		 * @return Object of \Facebook\Facebook.
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				// check for valid app-id.
				if ( '{app-id}' === FacebookConfig::$app_id || empty( FacebookConfig::$app_id ) ) {
					throw new Exception( 'No valid app id found.' );
				}

				// check for valid app-secret.
				if ( '{app-secret}' === FacebookConfig::$app_secret || empty( FacebookConfig::$app_secret ) ) {
					throw new Exception( 'Requires valid app secret key.' );
				}

				self::$instance = new \Facebook\Facebook([
					'app_id'                => FacebookConfig::$app_id,
					'app_secret'            => FacebookConfig::$app_secret,
					'default_graph_version' => FacebookConfig::$graph_version,
				]);
			}

			return self::$instance;
		}
	}
endif;
