<?php
/**
 * Core class file to work with facebook pages.
 */
if ( ! class_exists( 'FBpage' ) ) :
	/**
	 * Class defination for facebook page.
	 */
	class FBpage {
		/**
		 * This variable contains the page_id for current FBpage object.
		 *
		 * @var string
		 */
		public $page_id = '';

		/**
		 * This variable contains the page_access_token for current FBpage object.
		 *
		 * @var string
		 */
		public $page_access_token = '';

		/**
		 * Default constructor
		 */
		public function __construct() {}

		/**
		 * Initialises the page_id for FBpage
		 *
		 * @param string $page_id to perform operations.
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public static function get( $page_id ) {
			if ( empty( $page_id ) ) {
				throw new Exception( 'No facebook page id given.' );
			}

			$instance          = new self();
			$instance->page_id = $page_id;
			return $instance;
		}

		/**
		 * This function will get the page access token to perform operations on pages.
		 *
		 * @return string
		 */
		public function get_page_access_token() {
			if ( empty( $this->page_access_token ) ) {
				$this->page_access_token = FBtokens::get_page_access_token( $this->page_id );
			}

			return $this->page_access_token;
		}

		/**
		 * This function will create post on given page.
		 *
		 * @return string
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public function create_post( $message, $url ) {
			if ( empty( $this->page_id ) ) {
				throw new Exception( 'No facebook page id given.' );
			}

			if ( empty( $message ) ) {
				throw new Exception( 'No content is given for facebook post.' );
			}

			if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
				throw new Exception( 'Not a valid url to create post.' );
			}

			if ( empty( $this->page_access_token ) ) {
				$this->get_page_access_token();
			}

			$fb = FBloader::instance();

			$post_id = '';

			try {
				$post_data = [];
				if ( empty( $url ) ) {
					$post_data = [
						'message' => $message,
					];
				} else {
					$post_data = [
						'message' => $message,
						'link'    => $url,
					];
				}

				$response = $fb->post(
					'/' . $this->page_id . '/feed',
					$post_data,
					$this->page_access_token
				);

				$graph_node = $response->getGraphNode();

				$post_id = $graph_node->getField( 'id' );
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}
			return $post_id;
		}

		/**
		 * This function will remove the post from facebook.
		 *
		 * @param  string $post_id for post to delete.
		 * @return boolean
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public function remove_post( $post_id = '' ) {
			if ( empty( $this->page_id ) ) {
				throw new Exception( 'No facebook page id given.' );
			}

			if ( empty( $post_id ) ) {
				throw new Exception( 'No post id given.' );
			}

			if ( empty( $this->page_access_token ) ) {
				$this->get_page_access_token();
			}

			$fb = FBloader::instance();

			try {
				$del_response = $fb->delete(
					'/' . $post_id,
					array(),
					$this->page_access_token
				);

				$del_feed = $del_response->getGraphNode();

				$success = $del_feed->getField( 'success' );

				return $success;
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}
			return false;
		}

		/**
		 * This function will get the post from facebook.
		 *
		 * @param  string $post_id for post to delete.
		 * @return array
		 * @throws Exception For missing values, function throws an exceptions.
		 */
		public function get_post( $post_id = '' ) {
			if ( empty( $this->page_id ) ) {
				throw new Exception( 'No facebook page id given.' );
			}

			if ( empty( $post_id ) ) {
				throw new Exception( 'No post id given.' );
			}

			if ( empty( $this->page_access_token ) ) {
				$this->get_page_access_token();
			}

			$fb = FBloader::instance();

			$post_data = [];

			try {
				$res = $fb->get(
					'/' . $post_id . '?fields=id,message,full_picture,picture,created_time,link',
					$this->page_access_token
				);

				$feed = $res->getGraphNode();

				$date_time    = $feed->getField( 'created_time' );
				$message      = $feed->getField( 'message' );
				$id           = $feed->getField( 'id' );
				$full_picture = $feed->getField( 'full_picture' );
				$picture      = $feed->getField( 'picture' );
				$link         = $feed->getField( 'link' );

				$post_data = [
					'id'           => $id,
					'message'      => $message,
					'date_time'    => $date_time,
					'full_picture' => $full_picture,
					'picture'      => $picture,
					'link'         => $link,
				];
			} catch ( FacebookExceptionsFacebookResponseException $e ) {
				throw new Exception( 'Graph returned an error: ' . $e->getMessage() );
			} catch ( FacebookExceptionsFacebookSDKException $e ) {
				throw new Exception( 'Facebook SDK returned an error: ' . $e->getMessage() );
			}

			return $post_data;
		}
	}
endif;
