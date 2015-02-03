<?php

/**
 * http://martinfowler.com/articles/replaceThrowWithNotification.html
 * http://martinfowler.com/eaaDev/Notification.html
 */
class RestifyNotification {

	protected $errors = array();

	public function __construct() {

	}

	public function addError($error) {
		$this->errors[] = $error;
	}

	public function catchCakeValidationErrors($errors) {
		$tmp = array();
		foreach ($errors as $key => $error) {
			if (is_array($error)) {
				foreach ($error as $fieldError) {
					$tmp[] = $fieldError;
				}

				continue;
			}

			$tmp[] = $error;
		}

		$this->errors += $tmp;
	}

	public function hasErrors() {
		return ! empty($this->errors);
	}

	public function getErrors() {
		return array(
			'error' => $this->errors
		);
	}
}