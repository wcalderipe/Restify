<?php
App::uses('RestifyNotification', 'Restify.Notification');

class RestifyPersister {

	protected $notification;

	public function __construct() {
		$this->notification = new RestifyNotification();
	}

	public function create(Model $model, $data = array(), $filterKey = false) {
		$data = $this->createModelKey($model, $data);
		return $model->create($data, $filterKey);
	}

	public function save(Model $model, $data = null, $validate = true, $fieldList = array()) {
		$data = $this->createModelKey($model, $data);
		if (!$this->validateModel($model, $data)) {
			return false;
		}

		return $model->save($data, $validate, $fieldList);
	}

	public function saveAll(Model $model, $data = null, $options = array()) {
		$data = $this->createModelKey($model, $data);
		if (!$this->validateModel($model, $data)) {
			return false;
		}

		return $model->saveAll($data, $options);
	}

	public function updateAll(Model $model, $fields, $conditions = true) {
		return $model->updateAll($fields, $conditions);
	}

	public function delete(Model $model, $id = null, $cascade = true) {
		return $model->delete($id, $cascade);
	}

	private function createModelKey(Model $model, $data) {
		return array(
			$model->alias => $data
		);
	}

	private function validateModel(Model $model, $data = array()) {
		$model->set($data);
		if (!$model->validates()) {
			$this->notification->catchCakeValidationErrors(
				$model->validationErrors
			);
			return false;
		}

		return true;
	}

	public function getNotification() {
		return $this->notification;
	}
}
