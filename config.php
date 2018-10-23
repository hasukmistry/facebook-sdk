<?php
/**
 * Core class file to save initial configurations for facebook sdk.
 */
if ( ! class_exists( 'FacebookConfig' ) ) :
	/**
	 * Class defination for facebook sdk integration.
	 */
	class FacebookConfig {
		/**
		 * This variable contains the app id for the facebook app.
		 *
		 * @var string
		 */
		public static $app_id = '{app-id}';

		/**
		 * This variable contains the app secret key for the facebook app.
		 *
		 * @var string
		 */
		public static $app_secret = '{app-secret}';

		/**
		 * This variable contains the graph version of facebook sdk to use.
		 *
		 * @var string
		 */
		public static $graph_version = 'v2.10';

		/**
		 * This variable contains the callback url to use.
		 *
		 * @var string
		 */
		public static $callback_url = '';

		/**
		 * This variable contains the permissions for user who logins.
		 *
		 * @var Array
		 */
		public static $permissions = [];

		/**
		 * Default constructor
		 */
		public function __construct() {}

		/**
		 * This function will set the static class variables from given parameters.
		 *
		 * @param string $app_id for facebook application.
		 * @param string $app_secret for facebook application.
		 * @param string $graph_version for facebook application.
		 * @return void
		 */
		public static function set( $app_id, $app_secret, $graph_version = 'v2.10' ) {
			self::$app_id = $app_id;

			self::$app_secret = $app_secret;

			self::$graph_version = $graph_version;
		}

		/**
		 * This function will set the static class variables from given parameters.
		 *
		 * @param string $callback_url for facebook application.
		 * @return void
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function set_callback( $callback_url = '' ) {
			if ( filter_var( $callback_url, FILTER_VALIDATE_URL ) === false ) {
				throw new Exception( 'Not a valid callback url.' );
			}
			self::$callback_url = $callback_url;
		}

		/**
		 * This function will set the static class variables from given parameters.
		 *
		 * @param Array $permissions for users.
		 * @return void
		 */
		public static function set_permissions( $permissions = [] ) {
			if ( empty( $permissions ) || ! is_array( $permissions ) ) {
				// default permissions for this library.
				// find out more here: https://developers.facebook.com/docs/facebook-login/permissions/.
				self::$permissions = [
					'manage_pages',
					'publish_pages',
					'pages_show_list',
					'publish_to_groups',
				];
			} else {
				self::$permissions = $permissions;
			}
		}
	}
endif;

?>
