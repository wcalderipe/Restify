<?php
App::uses('CakeRequest', 'Network');

class RequestQueryParser {

	protected $params = array();

	protected $apiParams = array();

	protected $nonApiParams = array();

	protected $validApiParams = array(
		'offset',
		'limit',
		'sort',
		'fields',
		'token'
	);

	public function __construct($mixed) {
		$params = $mixed instanceof CakeRequest ? $mixed->query : $mixed;

		if (null !== Configure::read('Restify.apiQueryParams')) {
			$this->validApiParams = Configure::read('Restify.apiQueryParams');
		}
		
		$this->setParams($params);
	}

	public function setParams($params) {
		$this->params = $params;
		$this->distributeBetweenApiAndNonApi();

		return $this;
	}

	protected function distributeBetweenApiAndNonApi() {
		foreach ($this->params as $key => $param) {
			if (in_array($key, $this->validApiParams)) {
				$this->apiParams[$key] = $param;
				continue;
			}

			$this->nonApiParams[$key] = $param;
		}
	}

	public function exists($key) {
		return isset($this->params[$key]);
	}

	public function hasParams() {
		return !empty($this->params);
	}

	public function get($key) {
		if (!$this->exists($key)) {
			return null;
		}

		return $this->params[$key];
	}

	public function getParams() {
		return $this->params;
	}

	public function getApiParams() {
		return $this->apiParams;
	}

	public function getNonApiParams() {
		return $this->nonApiParams;
	}
}