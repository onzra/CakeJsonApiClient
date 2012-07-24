<?php
/**
 * JsonApiClient support for CakePHP
 *
 * ONZRA: Enterprise Development
 * Copyright 2012, ONZRA LLC (http://www.ONZRA.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, ONZRA LLC (http://www.ONZRA.com)
 * @link          https://github.com/onzra/CakeJsonApiClient CakeJsonApiClient
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('HttpSocket', 'Network/Http');
App::uses('JsonApiClientConnectionInterface', 'JsonApiClient.Lib');

/**
 * Provides HTTP support to Json Api Clients.  Handles authentication and
 * returns the body of the HTTP response as a string.
 */
class JsonApiClientHttpSocketAdapter implements JsonApiClientConnectionInterface {

	/**
	 * The low level Http socket
	 *
	 * @var HttpSocket
	 */
	protected $socket;

	public function __construct() {
		$this->socket = new HttpSocket();
	}

	public function __destruct() {
		$this->socket = false;
	}

	/**
	 * makeRequest performs an HTTP call
	 *
	 * @param string $method One of POST, GET, DELETE, PUT
	 * @param string $url This url includes query params
	 * @param array $post_data Array of POST data keys and values
	 * @return string Returns body of HTTP response
	 * @throws ErrorHttpResponseException
	 * @throws InvalidHttpMethodException
	 */
	public function makeRequest($method, $url, $post_data = array()) {
		$method = strtolower($method);
		if(in_array($method, array('post', 'delete', 'put', 'get'))) {
			if(is_callable(array($this->socket, $method))) {
				/* @var HttpResponse $response */
				$response = null;
				if($method == 'post') {
					$response = $this->socket->post($url, $post_data);
				} else {
					$response = $this->socket->$method($url);
				}

				if($response->isOk()) {
					$body = $response->body();
					return $body;
				} else {
					throw new ErrorHttpResponseException();
				}
			} else {
				throw new InvalidHttpMethodException();
			}
		} else {
			throw new InvalidHttpMethodException();
		}
	}

	/**
	 * setAuth uses Basic auth to authenticate with supplied username/password
	 *
	 * @param string $username The username for use with Basic auth
	 * @param string $password The password for use with Basic auth
	 */
	public function setAuth($username, $password) {
		$this->socket->configAuth('basic', $username, $password);
	}

	/**
	 * clearAuth removes any Basic auth that has been previously set
	 */
	public function clearAuth() {
		$this->socket->configAuth('');
	}
}

/**
 * Raised when an invalid HTTP method is tried
 */
class InvalidHttpMethodException extends Exception {

}

/**
 * Raised when a non-200 HTTP response is received
 */
class ErrorHttpResponseException extends Exception {

}
