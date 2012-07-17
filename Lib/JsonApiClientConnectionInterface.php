<?php

/**
 * Interface for Json Api Client Connections.  Supplies interface for Json Api Clients to make HTTP requests.
 */
interface JsonApiClientConnectionInterface {

	/**
	 * makeRequest performs an HTTP call
	 *
	 * @abstract
	 * @param string $method One of POST, GET, DELETE, PUT
	 * @param string $url This url includes query params
	 * @param array $post_data Array of POST data keys and values
	 * @return string Returns body of HTTP response
	 * @throws ErrorHttpResponseException
	 * @throws InvalidHttpMethodException
	 */
	public function makeRequest($method, $url, $post_data = array());

	/**
	 * setAuth uses Basic auth to authenticate with supplied username/password
	 *
	 * @abstract
	 * @param string $username The username for use with Basic auth
	 * @param string $password The password for use with Basic auth
	 */
	public function setAuth($username, $password);

	/**
	 * clearAuth removes any Basic auth that has been previously set
	 *
	 * @abstract
	 */
	public function clearAuth();

}