<?php
namespace SiteGround_Optimizer\Rest;

use SiteGround_Optimizer\Supercacher\Supercacher;
use SiteGround_Optimizer\Options\Options;
use SiteGround_Optimizer\Htaccess\Htaccess;
use SiteGround_Optimizer\Memcache\Memcache;
/**
 * Rest Helper class that manages caching options.
 */
class Rest_Helper_Cache extends Rest_Helper {

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->memcache = new Memcache();
		$this->options = new Options();
		$this->htaccess = new Htaccess();
	}

	/**
	 * Method used for executing the enable/disble method
	 *
	 * @since  6.0.0
	 *
	 * @param  object $request Request data.
	 */
	public function manage_dynamic_cache( $request ) {
		// Validate rest request and prepare data.
		$data = $this->validate_rest_request( $request, array( 'enable_cache' ) );

		// On Disable - disable all sub settings as well.
		if ( 0 === $data['value'] ) {
			Options::disable_option( 'siteground_optimizer_enable_cache' );

			// Disable Automatic Purge.
			Options::disable_option( 'siteground_optimizer_autoflush_cache' );
			// Disable Browser-specific Caching.
			Options::disable_option( 'siteground_optimizer_user_agent_header' );
			// Disable REST API flush.
			Options::disable_option( 'siteground_optimizer_purge_rest_cache' );

			// Send the response.
			self::send_json_success(
				self::get_response_message( true, 'enable_cache', 0 ),
				array(
					'enable_cache'      => 0,
					'autoflush_cache'   => 0,
					'user_agent_header' => 0,
					'purge_rest_cache'  => 0,
				)
			);
		}

		// Enable Dynamic Caching option.
		if ( false === Options::enable_option( 'siteground_optimizer_enable_cache' ) ) {
			// Send the response.
			self::send_json_error(
				self::get_response_message( false, 'enable_cache', 1 ),
				array(
					'enable_cache' => 0,
				)
			);
		}

		// Enable Automatic Purge.
		Options::enable_option( 'siteground_optimizer_autoflush_cache' );

		// Send the response.
		self::send_json_success(
			self::get_response_message( true, 'enable_cache', 1 ),
			array(
				'enable_cache'      => 1,
				'autoflush_cache'   => 1,
			)
		);
	}

	/**
	 * Enable/Disable Memcached option.
	 *
	 * @since 6.0.0
	 *
	 * @param object $request Request data.
	 */
	public function manage_memcache( $request ) {
		// Validate rest request and prepare data.
		$data = $this->validate_rest_request( $request, array( 'enable_memcached' ) );

		// Enable or disable the option.
		if ( 1 === $data['value'] ) {
			// Bail if no port is found.
			if ( empty( $this->memcache->is_connection_working() ) ) {
				self::send_json_error(
					self::get_response_message( false, 'enable_memcache_empty_port', null ),
					array(
						'enable_memcached' => 0,
					)
				);
			}
			// Try to enable Memcached.
			$result = $this->memcache->enable_memcache();
		} else {
			// Disable the Memcached.
			$result = $this->memcache->disable_memcache();
		}

		// Send the response.
		self::send_json_response(
			$result,
			self::get_response_message( $result, 'enable_memcached', $data['value'] ),
			array(
				'enable_memcached' => 1 === intval( $result ) ? $data['value'] : intval( ! $data['value'] ),
			)
		);
	}

	/**
	 * Enable/Disable Automatic Purge option.
	 *
	 * @since 6.0.0
	 *
	 * @param object $request Request data.
	 */
	public function manage_automatic_purge( $request ) {
		// Validate rest request and prepare data.
		$data = $this->validate_rest_request( $request, array( 'autoflush_cache' ) );

		// Disable the option.
		if ( 0 === $data['value'] ) {
			Options::disable_option( 'siteground_optimizer_autoflush_cache' );

			// Send the response.
			self::send_json_success(
				self::get_response_message( true, 'autoflush_cache', 0 ),
				array(
					'autoflush_cache' => 0,
				)
			);
		}

		// Enable Dynamic Cache.
		Options::enable_option( 'siteground_optimizer_enable_cache' );

		// Enable Automatic Purge.
		Options::enable_option( 'siteground_optimizer_autoflush_cache' );

		// Send the response.
		self::send_json_success(
			self::get_response_message( true, 'autoflush_cache', 1 ),
			array(
				'enable_cache'    => 1,
				'autoflush_cache' => 1,
			)
		);
	}

	/**
	 * Manage Browser-specific Caching option.
	 *
	 * @since 6.0.0
	 *
	 * @param object $request Request data.
	 */
	public function manage_user_agent_header( $request ) {
		// Validate rest request and prepare data.
		$data = $this->validate_rest_request( $request, array( 'user_agent_header' ) );

		// Disable the option.
		if ( 0 === $data['value'] ) {
			Options::disable_option( 'siteground_optimizer_user_agent_header' );

			$this->htaccess->enable( 'user-agent-vary' );

			// Send the response.
			self::send_json_success(
				self::get_response_message( true, 'user_agent_header', 0 ),
				array(
					'user_agent_header' => 0,
				)
			);
		}

		// Enable Dynamic Cache.
		Options::enable_option( 'siteground_optimizer_enable_cache' );

		// Enable Automatic Purge.
		Options::enable_option( 'siteground_optimizer_autoflush_cache' );

		// Enable Browser-specific Caching.
		Options::enable_option( 'siteground_optimizer_user_agent_header' );

		$this->htaccess->disable( 'user-agent-vary' );

		// Send the response.
		self::send_json_success(
			self::get_response_message( true, 'user_agent_header', 1 ),
			array(
				'enable_cache'      => 1,
				'autoflush_cache'   => 1,
				'user_agent_header' => 1,
			)
		);
	}

	/**
	 * Test if url is cached.
	 *
	 * @since 5.0.0
	 *
	 * @param object $request Request data.
	 */
	public function test_cache( $request ) {
		// Get the url.
		$url           = $this->validate_and_get_option_value( $request, 'url' );
		$is_cloudflare = Options::is_enabled( 'siteground_optimizer_cloudflare_optimization_status' );

		$is_cached = Supercacher::test_cache( $url, true, (bool) $is_cloudflare );

		// Send the response.
		self::send_json_response(
			$is_cached,
			true === $is_cached
				? __( 'The URL is cached', 'sg-cachepress' )
				: __( 'The URL is not cached', 'sg-cachepress' )
		);
	}

	/**
	 * Purge the cache and send json response.
	 *
	 * @since 5.0.0
	 */
	public function purge_cache_from_rest() {
		// Purge the cache.
		Supercacher::purge_cache();

		// Send the response.
		self::send_json_success(
			__( 'Dynamic Caching successfully purged', 'sg-cachepress' )
		);
	}

	/**
	 * Enable memcached.
	 *
	 * @since  5.0.0
	 */
	public function enable_memcache() {
		$port = $this->memcache->get_memcached_port();

		if ( empty( $port ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'SiteGround Optimizer was unable to connect to the Memcached server and it was disabled. Please, check your SiteGround control panel and turn it on if disabled.', 'sg-cachepress' ),
				)
			);
		}

		// First enable the option.
		$result = Options::enable_option( 'siteground_optimizer_enable_memcached' );

		// Remove notices.
		Options::disable_option( 'siteground_optimizer_memcache_notice' );
		Options::disable_option( 'siteground_optimizer_memcache_crashed' );
		Options::disable_option( 'siteground_optimizer_memcache_dropin_crashed' );

		// Send success if the dropin has been created.
		if ( $result && $this->memcache->create_memcached_dropin() ) {
			wp_send_json_success(
				array(
					'message' => __( 'Memcached Enabled', 'sg-cachepress' ),
				)
			);
		} else {
			if ( 11211 === $port ) {
				wp_send_json_error(
					array(
						'message' => __( 'SiteGround Optimizer was unable to connect to the Memcached server and it was disabled. Please, check your SiteGround control panel and turn it on if disabled.', 'sg-cachepress' ),
					)
				);
			}
		}

		// Dropin cannot be created.
		wp_send_json_error(
			array(
				'message' => __( 'Could Not Enable Memcache!', 'sg-cachepress' ),
			)
		);
	}

	/**
	 * Disable memcached.
	 *
	 * @since  5.0.0
	 */
	public function disable_memcache() {
		// First disable the option.
		$result = Options::disable_option( 'siteground_optimizer_enable_memcached' );

		// Send success if the option has been disabled and the dropin doesn't exist.
		if ( ! $this->memcache->dropin_exists() ) {
			wp_send_json_success(
				array(
					'message' => __( 'Memcached Disabled!', 'sg-cachepress' ),
				)
			);
		}

		// Try to remove the dropin.
		$is_dropin_removed = $this->memcache->remove_memcached_dropin();

		// Remove notices.
		Options::disable_option( 'siteground_optimizer_memcache_notice' );
		Options::disable_option( 'siteground_optimizer_memcache_crashed' );
		Options::disable_option( 'siteground_optimizer_memcache_dropin_crashed' );

		// Send success if the droping has been removed.
		if ( $is_dropin_removed ) {
			wp_send_json_success(
				array(
					'message' => __( 'Memcached Disabled!', 'sg-cachepress' ),
				)
			);
		}

		// The dropin cannot be removed.
		wp_send_json_error(
			array(
				'message' => __( 'Could Not Disable Memcache!', 'sg-cachepress' ),
			)
		);
	}

}
