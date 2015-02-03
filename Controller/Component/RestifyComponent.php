<?php
App::uses('RestifyResponse', 'Restify.Network');
App::uses('RestifyRequest',  'Restify.Network');
App::uses('RestifyVersion',  'Restify.Version');

class RestifyComponent extends Component {

	public $request = null;

	protected $controller = null;

	public function initialize(Controller $controller) {
		if (null === $this->controller) {
			$this->controller = $controller;
		}

		$this->setRequest($this->controller->request);
		$this->dispatchApiVersion();
	}


	private function dispatchApiVersion() {
		$request = $this->controller->request;
		$params  = $request->params;

		if (!isset($params['prefix']) || $params['prefix'] !== 'api') {
			return;
		}

		if (!isset($params['version'])) {
			return;
		}

		$availableVersions = Configure::read('Restify.versions') ?
			Configure::read('Restify.versions') : array();

		if (!in_array($params['version'], $availableVersions)) {
			// TODO: Change to a custom exception
			throw new RuntimeException('Not supported API version');
		}

		$versionInfo = RestifyVersion::getVersionInfoFromRequestParams($params);
		if (method_exists($this->controller, $versionInfo['prefixedAction'])) {
			$this->controller->request->params['action'] = $versionInfo['prefixedAction'];
		} else {
			if (method_exists($this->controller, $versionInfo['downgradeAction'])) {
				$this->controller->request->params['action'] = $versionInfo['downgradeAction'];
			} else {
				// TODO: Change to a custom exception
				throw new RuntimeException("Action does not exists");
			}
		}
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
