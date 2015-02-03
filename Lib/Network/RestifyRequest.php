<?php
App::uses('CakeRequest', 'Network');

class RestifyRequest extends CakeRequest {

	public function getData() {
		return (array) $this->input('json_decode');
	}

	public function castCakeRequest(CakeRequest $request) {
		foreach (get_object_vars($request) as $varName => $value) {
			$this->$varName = $value;
		}
	}
}  