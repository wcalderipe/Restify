<?php
App::uses('RestifyResponse', 'Restify.Network');
App::uses('RestifyRequest',  'Restify.Network');

class RestifyComponent extends Component {

	public $request = null;

	protected $controller = null;

	public function initialize(Controller $controller) {
		if (null === $this->controller) {
			$this->controller = $controller;
		}

		$this->setRequest($this->controller->request);
	}

	protected function setRequest(CakeRequest $request) {
		if ($request instanceof RestifyRequest) {
			$this->request = $request;
			return $this;
		}

		$request = new RestifyRequest();
		$request->castCakeRequest($this->controller->request);
		$this->request = $request;
		return $this;
	}

	public function response($mixed, $status = 200, $options = array()) {
		return new RestifyResponse($mixed, $status, $options);
	}
}
