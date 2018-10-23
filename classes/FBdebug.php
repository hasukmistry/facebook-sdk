<?php
/**
 * Core class file to debug objects.
 */
if ( ! class_exists( 'FBdebug' ) ) :
	/**
	 * Class defination for facebook debug.
	 */
	class FBdebug {
		/**
		 * Default constructor
		 */
		private function __construct() {}

		/**
		 * This function will update the facebook scraping info.
		 *
		 * @param  string $url to scrape opengraph info.
		 * @param  string $access_token of user to scrape url with facebook sdk.
		 * @throws Exception For missing values, function throws an exceptions.
		 * @return array
		 */
		public static function scrape_info( $url = '', $access_token = '' ) {
			if ( empty( $url ) || filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
				throw new Exception( 'Not a valid url to scrape information as facebook.' );
			}

			if ( empty( $access_token ) ) {
				throw new Exception( 'Please provide an access token.' );
			}

			$fb = FBloader::instance();

			try {
				$response = $fb->post(
					'?scrape=true&id=' . stripslashes( $url ),
					array(),
					$access_token
				);

				$feed = $response->getGraphNode();

				return $feed->asArray();
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}
			return [];
		}
	}
endif;
