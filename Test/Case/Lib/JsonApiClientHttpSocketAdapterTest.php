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
App::uses('JsonApiClientConnectionInterface', 'JsonApiClient.Lib');
App::uses('HttpResponse', 'Network/Http');

/**
 * Unit tests for JsonApiClientHttpSocketAdapter
 */
class JsonApiClientHttpSocketAdapterTest extends CakeTestCase {

	/**
	 * Verify the GET method is forwarded to the HTTP socket
	 */
	public function testGetMethodIsUsed() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our method into makeRequest
		$connection->makeRequest('GET', 'test');

		//Verify the socket was built with the correct method
		$actual = $connection->socket->_testRequest['method'];
		$expected = 'GET';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify the POST method is forwarded to the HTTP socket
	 */
	public function testPostMethodIsUsed() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our method into makeRequest
		$connection->makeRequest('POST', 'test');

		//Verify the socket was built with the correct method
		$actual = $connection->socket->_testRequest['method'];
		$expected = 'POST';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify the DELETE method is forwarded to the HTTP socket
	 */
	public function testDeleteMethodIsUsed() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our method into makeRequest
		$connection->makeRequest('DELETE', 'test');

		//Verify the socket was built with the correct method
		$actual = $connection->socket->_testRequest['method'];
		$expected = 'DELETE';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify the PUT method is forwarded to the HTTP socket
	 */
	public function testPutMethodIsUsed() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our method into makeRequest
		$connection->makeRequest('PUT', 'test');

		//Verify the socket was built with the correct method
		$actual = $connection->socket->_testRequest['method'];
		$expected = 'PUT';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify invalid HTTP methods raise an exception
	 */
	public function testInvalidMethodRaisesException() {
		$connection = new JsonApiClientHttpSocketAdapter();
		$this->expectException('InvalidHttpMethodException');
		$connection->makeRequest('asdf', 'url');
	}

	/**
	 * Set authentication and verify the socket has those values
	 */
	public function testSetAuth() {
		$connection = new TestJsonApiClientHttpSocketAdapter();
		$connection->setAuth('username', 'password');

		//Retrieve auth values from the socket
		$actual = $connection->socket->_auth;
		$expected = array('basic' => array('user' => 'username', 'pass' => 'password'));
		$this->assertEqual($expected, $actual);
	}

	/**
	 * Set authentication and ensure clearAuth actually removes the values from the socket
	 */
	public function testClearAuth() {
		$connection = new TestJsonApiClientHttpSocketAdapter();
		$connection->setAuth('username', 'password');
		$connection->clearAuth();

		//Retrieve auth values from the socket
		$actual = $connection->socket->_auth;
		$expected = array();
		$this->assertEqual($expected, $actual);
	}

	/**
	 * Verify the socket uses the url passed in for GET, DELETE, PUT
	 */
	public function testUsesUrl() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our url into makeRequest
		$connection->makeRequest('GET', 'http://test.com');

		//Verify socket has the url
		$actual = $connection->socket->_testRequest['uri'];
		$expected = 'http://test.com';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify the socket uses the url passed in for POST
	 */
	public function testPostUsesUrl() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass our url into makeRequest
		$connection->makeRequest('POST', 'http://test.com');

		//Verify socket has the url
		$actual = $connection->socket->_testRequest['uri'];
		$expected = 'http://test.com';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Verify the socket uses the post data for POST method
	 */
	public function testPostUsesPostData() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Pass in POST data
		$connection->makeRequest('POST', 'http://test.com', array('test'=>'image'));

		//Verify the socket has the data
		$actual = $connection->socket->_testRequest['body'];
		$expected = array('test'=>'image');
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Non-200 response should raise exception
	 */
	public function testHttpResponseRaisesException() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set response to an error
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 404;
		$connection->socket->_testResponse = $response;

		$this->expectException('ErrorHttpResponseException');
		$connection->makeRequest('POST', 'http://test.com');
	}

	/**
	 * 200 responses should return the body for GET, DELETE, PUT
	 */
	public function testResponseReturnsData() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Make call and verify response
		$actual = $connection->makeRequest('GET', 'http://test.com');
		$expected = 'test';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * 200 responses should return the body for POST
	 */
	public function testPostResponseReturnsData() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Pre-set our response so we are not actually making an HTTP call
		$response = new HttpResponse();
		$response->body = 'test';
		$response->code = 200;
		$connection->socket->_testResponse = $response;

		//Make call and verify response
		$actual = $connection->makeRequest('POST', 'http://test.com');
		$expected = 'test';
		$this->assertEqual($actual, $expected);
	}

	/**
	 * Ensure that our socket has valid, callable methods
	 */
	public function testInvalidSocketMethodRaisesException() {
		$connection = new TestJsonApiClientHttpSocketAdapter();

		//Setting put method to private will mimic a situation where our
		//socket class was extended and the method was closed or removed.
		$reflection_class = new ReflectionClass('TestHttpSocket');
		$method = $reflection_class->getMethod('put');
		$method->setAccessible(false);
		$connection->socket = $reflection_class;

		$this->expectException('InvalidHttpMethodException');
		$connection->makeRequest('PUT', 'http://test.com');
	}
}

/**
 * Class used for testing
 */
class TestJsonApiClientHttpSocketAdapter extends JsonApiClientHttpSocketAdapter {

	/**
	 * Open $socket to allow for pre-setting responses
	 */
	public $socket;

	public function __construct() {
		$this->socket = new TestHttpSocket();
	}

}

/**
 * Class used for testing
 */
class TestHttpSocket extends HttpSocket {

	/**
	 * Open $_auth to allow access for verify setAuth/clearAuth
	 */
	public $_auth;

	public $_testResponse;

	public $_testRequest;

	/**
	 * Log the request and pass back our pre-generated response.
	 * This prevents actual HTTP calls from going out.
	 */
	public function request($request = array()) {
		$this->_testRequest = $request;
		return $this->_testResponse;
	}
}
