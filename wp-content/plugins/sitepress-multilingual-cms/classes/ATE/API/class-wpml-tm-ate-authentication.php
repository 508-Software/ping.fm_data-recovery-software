<?php

/**
 * @author OnTheGo Systems
 */
class WPML_TM_ATE_Authentication {
	const AMS_DATA_KEY          = 'WPML_TM_AMS';
	const AMS_STATUS_NON_ACTIVE = 'non-active';
	const AMS_STATUS_ENABLED    = 'enabled';
	const AMS_STATUS_ACTIVE     = 'active';

	/** @var string|null $site_id */
	private $site_id = null;

	public function get_signed_url_with_parameters( $verb, $url, $params = null ) {
		if ( $this->has_keys() ) {
			$url = $this->add_required_arguments_to_url( $verb, $url, $params );
			return $this->signUrl( $verb, $url, $params );
		}

		return new WP_Error( 'auth_error', 'Unable to authenticate' );
	}

	public function signUrl( $verb, $url, $params = null ) {
		$url_parts = wp_parse_url( $url );

		$query              = $this->get_url_query( $url );
		$query['signature'] = $this->get_signature( $verb, $url, $params );

		$url_parts['query'] = $this->build_query( $query );

		return http_build_url( $url_parts );
	}

	private function get_signature( $verb, $url, array $params = null ) {
		if ( $this->has_keys() ) {
			$verb      = strtolower( $verb );
			$url_parts = wp_parse_url( $url );

			$query_to_sign = $this->get_url_query( $url );

			if ( $params && 'get' !== $verb ) {
				$query_to_sign['body'] = md5( (string) wp_json_encode( $params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
			}

			$url_parts_to_sign          = $url_parts;
			$url_parts_to_sign['query'] = $this->build_query( $query_to_sign );

			$url_to_sign = http_build_url( $url_parts_to_sign );

			$string_to_sign = strtolower( $verb ) . $url_to_sign;

			$sha1 = hash_hmac( 'sha1', $string_to_sign, $this->get_secret(), true );

			return base64_encode( $sha1 );
		}

		return null;
	}

	public function has_keys() {
		return $this->get_secret() && $this->get_shared();
	}

	private function get_secret() {
		return $this->get_ams_data_property( 'secret' );
	}

	private function get_shared() {
		return $this->get_ams_data_property( 'shared' );
	}

	private function get_ams_data_property( $field ) {
		$data = $this->get_ams_data();
		if ( array_key_exists( $field, $data ) ) {
			return $data[ $field ];
		}

		return null;
	}

	/**
	 * @return array
	 */
	private function get_ams_data() {
		return get_option( self::AMS_DATA_KEY, [] );
	}

	/**
	 * @param string     $verb
	 * @param string     $url
	 * @param array|null $params
	 *
	 * @return string
	 */
	private function add_required_arguments_to_url( $verb, $url, array $params = null ) {
		$verb = strtolower( $verb );

		$url_parts = wp_parse_url( $url );

		$query = $this->get_url_query( $url );
		if ( $params && 'get' === $verb ) {
			foreach ( $params as $key => $value ) {
				$query[ $key ] = $value;
			}
		}

		$query['wpml_core_version'] = ICL_SITEPRESS_VERSION;
		$query['wpml_tm_version']   = WPML_TM_VERSION;
		$query['shared_key']        = $this->get_shared();
		$query['token']             = uuid_v5( wp_generate_uuid4(), $url );
		$query['website_uuid']      = $this->get_site_id();
		$query['ui_language_code']  = apply_filters(
			'wpml_get_user_admin_language',
			wpml_get_default_language(),
			get_current_user_id()
		);
		if( function_exists( 'OTGS_Installer' ) ) {
			$query['site_key'] = OTGS_Installer()->get_site_key( 'wpml' );
		}

		$url_parts['query'] = http_build_query( $query );

		return http_build_url( $url_parts );
	}

	/**
	 * @param string $url
	 *
	 * @return array
	 */
	private function get_url_query( $url ) {
		$url_parts = wp_parse_url( $url );
		$query     = array();
		if ( is_array( $url_parts ) && array_key_exists( 'query', $url_parts ) ) {
			parse_str( $url_parts['query'], $query );
		}

		return $query;
	}

	/**
	 * @param $query
	 *
	 * @return mixed|string
	 */
	protected function build_query( $query ) {
		if ( PHP_VERSION_ID >= 50400 ) {
			$final_query = http_build_query( $query, '', '&', PHP_QUERY_RFC3986 );
		} else {
			$final_query = str_replace(
				array( '+', '%7E' ),
				array( '%20', '~' ),
				http_build_query( $query )
			);
		}

		return $final_query;
	}

	/**
	 * @param string|null $site_id
	 */
	public function override_site_id( $site_id ) {
		$this->site_id = $site_id;
	}

	public function get_site_id() {
		return $this->site_id ? $this->site_id : wpml_get_site_id( WPML_TM_ATE::SITE_ID_SCOPE );
	}
}
