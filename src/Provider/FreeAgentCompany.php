<?php

namespace Polevaultweb\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class FreeAgentCompany implements ResourceOwnerInterface
{
	use ArrayAccessorTrait;

	/**
	 * Raw response
	 *
	 * @var array
	 */
	protected $response;

	/**
	 * Creates new resource owner.
	 *
	 * @param array  $response
	 */
	public function __construct(array $response = array())
	{
		$this->response = $response;
	}

	/**
	 * Get resource owner id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->getValueByKey($this->response, 'url');
	}

	/**
	 * Get resource owner name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getValueByKey($this->response, 'name');
	}

	/**
	 * Return all of the owner details available as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->response;
	}
}