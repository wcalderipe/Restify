<?php
App::uses('CakeResponse', 'Network');
App::uses('JsonBody', 'Restify.Network');

class RestifyResponse extends CakeResponse {

	protected $body;

	// TODO: we could also recieve a JsonBody as parameter
	public function __construct($body, $status, $options = array()) {
		$this->body = new JsonBody();
		$this->body->setSuccess($status)
			->enqueue($body)
		;

		$options += array(
			'body' => $this->body->serialize(true), // performs a clean serialization
			'type' => 'json',
			'status' => $status
		);

		parent::__construct($options);
	}
}
