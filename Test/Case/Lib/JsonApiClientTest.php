<?php

App::uses('JsonApiClient', 'JsonApiClient.Lib');
App::uses('JsonApiClientHttpSocketAdapter', 'JsonApiClient.Lib');
App::uses('JsonApiClientConnectionInterface', 'JsonApiClient.Lib');

/**
 * Unit tests for JsonApiClient
 */
class JsonApiClientTest extends CakeTestCase {

	/**
	 * When the body is JSON it should be decoded to an array
	 */
	public function testValidJsonResponseReturnsArray() {
		$client = new TestJsonApiClient();

		//Pre-set response to prevent HTTP request
		$client->connection->_testResponse = '{"test":"success"}';

		//Verify response
		$actual = $client->makeRequest('get', 'http://www.example.com');
		$expected = array('test'=>'success');
		$this->assertEqual($expected, $actual);
	}

	/**
	 * Invalid JSON should raise an error
	 */
	public function testInvalidJsonResponseRaisesException() {
		$client = new TestJsonApiClient();

		//Pre-set response to prevent HTTP request
		$client->connection->_testResponse = 'invalid json';

		//Verify exception is raised
		$this->expectException('InvalidJsonResponseException');
		$client->makeRequest('get', 'http://www.example.com');
	}

	/**
	 * Empty responses should also raise an error
	 */
	public function testEmptyResponseRaisesException() {
		$client = new TestJsonApiClient();

		//Pre-set response to prevent HTTP request
		$client->connection->_testResponse = '';

		//Verify exception is raised
		$this->expectException('EmptyResponseException');
		$client->makeRequest('get', 'http://www.example.com');
	}

	/**
	 * Verify setAuth modifies the connection
	 */
	public function testSetAuth() {
		$client = new TestJsonApiClient();
		$client->setAuth('username', 'password');

		$actual = array($client->connection->_testUsername, $client->connection->_testPassword);
		$expected = array(
			'username',
			'password'
		);
		$this->assertEqual($expected, $actual);
	}

	/**
	 * Verify clearAuth modifies the connection
	 */
	public function testClearAuth() {
		$client = new TestJsonApiClient();
		$client->setAuth('user', 'password');
		$client->clearAuth();

		$actual = array($client->connection->_testUsername, $client->connection->_testPassword);
		$expected = array(
			null,
			null
		);
		$this->assertEqual($expected, $actual);
	}

	/**
	 * Constructor should generate an extended JsonApiClientConnectionInterface
	 */
	public function testConstructorGeneratesCorrectClass() {
		$client = new JsonApiClient();

		//Use Reflection class to open $connection and check it's class
		$reflection_class = new ReflectionClass('JsonApiClient');
		$property = $reflection_class->getProperty('connection');
		$property->setAccessible(true);

		$this->assertIsA($property->getValue($client), 'JsonApiClientConnectionInterface');
	}
}

/**
 * Class used for testing
 */
class TestJsonApiClient extends JsonApiClient {

	/**
	 * open $connection to allow for pre-setting responses
	 */
	public $connection;

	public function __construct() {
		$this->connection = new TestJsonApiClientHttpSocketAdapter();
	}
}

/**
 * Class used for testing
 */
class TestJsonApiClientHttpSocketAdapter extends JsonApiClientHttpSocketAdapter {

	public $_testResponse;

	public $_testUsername;
	public $_testPassword;

	/**
	 * Open $socket to allow for pre-setting responses
	 */
	public $socket;

	/**
	 * Just return our pre-set response.
	 * This prevents HTTP requests from going out.
	 */
	public function makeRequest($method, $url, $post_data = array()) {
		return $this->_testResponse;
	}

	/**
	 * Store request for verification then pass along
	 */
	public function setAuth($username, $password) {
		$this->_testUsername = $username;
		$this->_testPassword = $password;
		parent::setAuth($username, $password);
	}

	/**
	 * Store request for verification then pass along
	 */
	public function clearAuth() {
		$this->_testUsername = null;
		$this->_testPassword = null;
		parent::clearAuth();
	}
}