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

App::uses('JsonApiClientHttpSocketAdapter', 'JsonApiClient.Lib');

/**
 * JsonApiClient is a CakePHP Plugin which supplies a client for APIs where the response is JSON.
 * Supports authentication and automatically decodes the response to an array
 */
class JsonApiClient {

    /**
	 * Stores the JsonApiClientConnectionInterface
	 *
     * @var JsonApiClientConnectionInterface
     */
    protected $connection;

    public function __construct() {
		$this->connection = new JsonApiClientHttpSocketAdapter();
	}

	/**
	 * makeRequest performs an HTTP call
	 *
	 * @param string $method One of POST, GET, DELETE, PUT
	 * @param string $url This url includes query params
	 * @param array $post_data Array of POST data keys and values
	 * @return array Returns the json_decoded HTTP response
	 * @throws InvalidJsonResponseException
	 * @throws EmptyResponseException
	 */
	public function makeRequest($method, $url, $post_data = array()) {
		$response = $this->connection->makeRequest($method, $url, $post_data);
		if($response) {
			$json = json_decode($response, true);
			if($json !== null) {
				return $json;
			} else {
				throw new InvalidJsonResponseException();
			}
		} else {
			throw new EmptyResponseException();
		}
	}

	/**
	 * setAuth uses Basic auth to authenticate with supplied username/password
	 *
	 * @param string $username The username for use with Basic auth
	 * @param string $password The password for use with Basic auth
	 */
	public function setAuth($username, $password) {
		$this->connection->setAuth($username, $password);
	}

	/**
	 * clearAuth removes any Basic auth that has been previously set
	 */
	public function clearAuth() {
		$this->connection->clearAuth();
	}

}

/**
 * Raised when the HTTP response body is not valid JSON
 */
class InvalidJsonResponseException extends Exception {

}

/**
 * Raised when the HTTP response body is empty
 */
class EmptyResponseException extends Exception {

}