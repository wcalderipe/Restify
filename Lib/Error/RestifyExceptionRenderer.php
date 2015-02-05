<?php
App::uses('ExceptionRenderer', 'Error');
App::uses('RestifyResponse', 'Restify.Network');

class RestifyExceptionRenderer extends ExceptionRenderer {

	protected function _outputMessage($template) {
		if ($this->isApiRequest()) {
			$this->controller->response = $this->getResponse(
				$this->controller->viewVars['error']
			);
			$this->controller->afterFilter();
			$this->controller->response->send();
		} else {
			try {
				$this->controller->render($template);
				$this->controller->afterFilter();
				$this->controller->response->send();
			} catch (MissingViewException $e) {
				$attributes = $e->getAttributes();
				if (isset($attributes['file']) && strpos($attributes['file'], 'error500') !== false) {
					$this->_outputMessageSafe('error500');
				} else {
					$this->_outputMessage('error500');
				}
			} catch (Exception $e) {
				$this->_outputMessageSafe('error500');
			}
		}
	}

	protected function getResponse($error) {
		$body  = array(
			'success' => false,
			'error'   => array(
				'code'    => $error->getCode(),
				'message' => $error->getMessage(),
				'stack'   => $error->getTraceAsString()
			)
		);

		return new RestifyResponse($body, $error->getCode());
	}

	protected function isApiRequest() {
		$request = $this->controller->request;
		$params  = $request->params;
		if (!array_key_exists('prefix', $params) || $params['prefix'] != 'api') {
			return false;
		}

		return true;
	}
}
