<?php

class RequestQueryValidator {

	protected $apiQueryParams = array(
		'limit',
		'offset',
		'fields',
		'token'
	);

	protected $request;

	public function __construct(CakeRequest $request) {
		$this->request = $request;
	}

	public function validates() {

	}
}
