<?php
namespace Depicter\Services;

use Averta\WordPress\Utility\JSON;
use Depicter\GuzzleHttp\Exception\GuzzleException;

class UserAPIService {

	/**
	 * User Login
	 *
	 * @var string $email
	 * @var string $password
	 *
	 * @throws \Depicter\GuzzleHttp\Exception\GuzzleException
	 * @throws \Exception
	 *
	 * @return bool
	 */
	public static function login( $email, $password ) {
		$response = \Depicter::remote()->post( 'v1/remote/user/login', [
			'email' => $email,
			'password' => $password
		], 5);

		$response = JSON::decode( $response->getBody(), true );

		if ( !empty( $response['errors'] ) ) {
			throw new \Exception( $response['errors'] );
		}

		\Depicter::cache('base')->set( 'members_access_token', $response['access_token'], 2 * DAY_IN_SECONDS );
		\Depicter::cache('base')->set( 'members_refresh_token', $response['refresh_token'], 15 * DAY_IN_SECONDS );
		\Depicter::options()->set( 'members_refresh_token', $response['refresh_token'] );

		return $response;
    }

	/**
	 * User Register
	 *
	 * @param $email
	 * @param $password
	 * @param $fields
	 *
	 * @return mixed
	 *
	 * @throws GuzzleException
	 * @throws \Exception
	 */
	public static function register( $email, $password, $fields = [] ) {

		$options = array_merge( [ 'email' => $email, 'password' => $password ], $fields );
		$response = \Depicter::remote()->post( 'v1/remote/user/register', $options, 5);

		$response = JSON::decode( $response->getBody(), true );

		if ( !empty( $response['errors'] ) ) {
			throw new \Exception( $response['errors'] );
		}

		\Depicter::cache('base')->set( 'members_access_token', $response['access_token'], 2 * DAY_IN_SECONDS );
		\Depicter::cache('base')->set( 'members_refresh_token', $response['refresh_token'], 15 * DAY_IN_SECONDS );
		\Depicter::options()->set( 'members_refresh_token', $response['refresh_token'] );

		return $response;
	}

	/**
	 * Renew Access Token
	 *
	 * @return void
	 */
	public static function renewAccessToken() {
		try {
			$response = \Depicter::remote()->post( 'v1/remote/token/get/access', [
				'headers' => [
					'Authorization'   => 'Bearer ' . \Depicter::options()->get('members_refresh_token', '')
				]
			], 5);

			$response = JSON::decode( $response->getBody(), true );

			if ( !empty( $response['errors'] ) ) {
				error_log( $response['errors'], 0 );
			}

			if ( !empty( $response['token'] ) ) {
				\Depicter::cache('base')->set( 'members_access_token', $response['access_token'], 2 * DAY_IN_SECONDS );
			}
		} catch( GuzzleException $exception ) {
			error_log( $exception->getMessage(), 0 );
		}
	}

	/**
	 * Renew Refresh Token
	 *
	 * @return void
	 */
	public static function renewRefreshToken() {
		try {
			$response = \Depicter::remote()->post( 'v1/remote/token/get/refresh', [
				'headers' => [
					'Authorization'   => 'Bearer ' . \Depicter::cache('base')->get( 'members_access_token', '' )
				]
			], 5);

			$response = JSON::decode( $response->getBody(), true );

			if ( !empty( $response['errors'] ) ) {
				error_log( $response['errors'], 0 );
			}

			if ( !empty( $response['token'] ) ) {
				\Depicter::cache('base')->set( 'members_access_token', $response['access_token'], 2 * DAY_IN_SECONDS );
			}
		} catch( GuzzleException $exception ) {
			error_log( $exception->getMessage(), 0 );
		}
	}

	/**
	 * Get google client id
	 *
	 * @return mixed
	 * @throws GuzzleException
	 * @throws \Exception
	 */
	public static function googleClientID() {
		$response = \Depicter::remote()->get( 'v1/auth/google/id' );
		$response = JSON::decode( $response->getBody(), true );

		if ( !empty( $response['errors'] ) ) {
			throw new \Exception( $response['errors'] );
		}

		return $response;
	}

	/**
	 * Login by google
	 *
	 * @param $accessToken
	 *
	 * @return mixed
	 * @throws GuzzleException
	 * @throws \Exception
	 */
	public static function googleLogin( $accessToken ) {

		$response = \Depicter::remote()->post( 'v1/remote/user/login/google', [
			'accessToken' => $accessToken
		] );

		$response = JSON::decode( $response->getBody(), true );

		if ( !empty( $response['errors'] ) ) {
			throw new \Exception( $response['errors'] );
		}

		\Depicter::cache('base')->set( 'members_access_token', $response['access_token'], 2 * DAY_IN_SECONDS );
		\Depicter::cache('base')->set( 'members_refresh_token', $response['refresh_token'], 15 * DAY_IN_SECONDS );
		\Depicter::options()->set( 'members_refresh_token', $response['refresh_token'] );

		return $response;
	}
}
