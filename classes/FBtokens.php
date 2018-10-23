<?php
/**
 * Core class file to get tokens for facebook operations.
 */
if ( ! class_exists( 'FBtokens' ) ) :
	/**
	 * Class defination for facebook tokens.
	 */
	class FBtokens {
		/**
		 * This variable contains the access token value.
		 *
		 * @var string
		 */
		public static $access_token = '';

		/**
		 * This variable contains the app access token value.
		 *
		 * @var string
		 */
		public static $app_access_token = '';

		/**
		 * Default constructor
		 */
		private function __construct() {}

		/**
		 * This function will set default access token as given in parameter.
		 *
		 * @param  string $access_token to set as default token to perform operations.
		 * @return void
		 */
		public static function set_default_access_token( $access_token = '' ) {
			if ( empty( $access_token ) ) {
				throw new Exception( 'Can not set empty access token.' );
			}

			$fb = FBloader::instance();

			$fb->setDefaultAccessToken( $access_token );
		}

		/**
		 * This function will return the app access token.
		 *
		 * @return string
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function get_app_access_token() {
			// check for valid app-id.
			if ( '{app-id}' === FacebookConfig::$app_id || empty( FacebookConfig::$app_id ) ) {
				throw new Exception( 'No valid app id found.' );
			}

			// check for valid app-secret.
			if ( '{app-secret}' === FacebookConfig::$app_secret || empty( FacebookConfig::$app_secret ) ) {
				throw new Exception( 'Requires valid app secret key.' );
			}

			return FacebookConfig::$app_id . '|' . FacebookConfig::$app_secret;
		}

		/**
		 * This function will return the user access token if he/she is logged in.
		 * Make sure you use it in callback url only.
		 *
		 * @return string
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function get_long_live_access_token() {
			$fb = FBloader::instance();

			$helper = $fb->getRedirectLoginHelper();

			try {
				$access_token = $helper->getAccessToken();
			} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}

			if ( ! isset( $access_token ) ) {
				if ( $helper->getError() ) {
					// $helper->getError()
					// $helper->getErrorCode()
					// $helper->getErrorReason()
					throw new Exception( $helper->getErrorDescription() );
				} else {
					throw new Exception( 'HTTP/1.0 400 Bad Request' );
				}
			}
			// The OAuth 2.0 client handler helps us manage access tokens.
			$oauth2_client = $fb->getOAuth2Client();

			// Get the access token metadata from /debug_token.
			$token_metadata = $oauth2_client->debugToken( $access_token );

			// Validation (these will throw FacebookSDKException's when they fail).
			$token_metadata->validateAppId( FacebookConfig::$app_id );

			// validate access_token expiration.
			$token_metadata->validateExpiration();

			if ( ! $access_token->isLongLived() ) {
				// Exchanges a short-lived access token for a long-lived one.
				try {
					$access_token = $oauth2_client->getLongLivedAccessToken( $access_token );

					$fb->setDefaultAccessToken( $access_token );
				} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
					throw new Exception( 'Error getting long-lived access token: ' . $e->getMessage() );
				}
			}

			self::$access_token = (string) $access_token;

			return self::$access_token;
		}

		/**
		 * This function will get the page access token to perform operations on pages.
		 *
		 * @param  string $page_id for facebook page.
		 * @return string
		 */
		public static function get_page_access_token( $page_id = '' ) {
			if ( empty( $page_id ) ) {
				throw new Exception( 'No facebook page id given.' );
			}

			$page_access_token = '';

			try {
				$fb = FBloader::instance();

				$res = $fb->get( $page_id . '?fields=access_token' );

				$feed = $res->getGraphNode();

				$page_access_token = $feed->getField( 'access_token' );
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}

			return $page_access_token;
		}

		/**
		 * This function will verify whether the access token is valid or not.
		 *
		 * @param  string  $access_token of token to verify.
		 * @return boolean either true or false for validity of token.
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function is_valid_access_token( $input_token = '', $access_token = '' ) {
			$fb = FBloader::instance();

			try {
				// more information here: https://developers.facebook.com/docs/facebook-login/access-tokens/debugging-and-error-handling.
				$res = $fb->get( 'debug_token?input_token=' . $input_token . '&access_token=' . $access_token );

				$feed = $res->getGraphNode();
				// print_r( $feed );

				$is_valid = $feed->getField( 'is_valid' );

				if ( $is_valid ) {
					return true;
				}
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}
			return false;
		}

		/**
		 * This function will destroy the given access token, from logged in user.
		 *
		 * @param  string $access_token of log in user.
		 * @return void
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function destroy_user_access_token( $access_token = '' ) {
			$fb = FBloader::instance();

			try {
				$del_response = $fb->delete(
					'/me/permissions?access_token=' . $access_token,
					array()
				);

				$del_feed = $del_response->getGraphNode();

				$success = $del_feed->getField( 'success' );
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}
		}
	}
endif;
