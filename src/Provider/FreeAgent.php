<?php

namespace Polevaultweb\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class FreeAgent extends AbstractProvider
{
	use BearerAuthorizationTrait;

	protected $baseURL = 'https://api.freeagent.com/v2/';

	/**
	 * FreeAgent constructor.
	 *
	 * @param array $options
	 * @param array $collaborators
	 */
	public function __construct( array $options = [], array $collaborators = [] ) {
		parent::__construct( $options, $collaborators );
		if ( isset( $options['sandbox'] ) && $options['sandbox'] ) {
			$this->baseURL = 'https://api.sandbox.freeagent.com/v2/';
		}
	}

	/**
	 * Get authorization url to begin OAuth flow
	 *
	 * @return string
	 */
	public function getBaseAuthorizationUrl()
	{
		return $this->baseURL . 'approve_app';
	}

	/**
	 * Get access token url to retrieve token
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function getBaseAccessTokenUrl(array $params)
	{
		return $this->baseURL . 'token_endpoint';
	}

	/**
	 * Get provider url to fetch user details
	 *
	 * @param  AccessToken $token
	 *
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token)
	{
		return $this->baseURL . 'company';
	}

	/**
	 * Get the default scopes used by this provider.
	 *
	 * This should not be a complete list of all scopes, but the minimum
	 * required for the provider user interface!
	 *
	 * @return array
	 */
	protected function getDefaultScopes()
	{
		return [];
	}

	protected function checkResponse(ResponseInterface $response, $data)
	{
		if (empty($data['error'])) {
			return;
		}

		$code = 0;
		$error = $data['error'];

		if (is_array($error)) {
			$code = $error['code'];
			$error = $error['message'];
		}

		throw new IdentityProviderException($error, $code, $data);
	}

	/**
	 * Generate a user object from a successful user details request.
	 *
	 * @param array       $response
	 * @param AccessToken $token
	 *
	 * @return FreeAgentCompany
	 */
	protected function createResourceOwner(array $response, AccessToken $token)
	{
		return new FreeAgentCompany($response['company']);
	}
}