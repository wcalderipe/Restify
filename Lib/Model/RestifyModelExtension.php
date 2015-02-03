<?php
App::uses('RestifyRepository', 'Restify.Model');
App::uses('RestifyPersister',  'Restify.Model');

class RestifyModelExtension {

	protected $model = null;

	protected $repository = null;

	protected $persister = null;

	public function __construct(Model $model) {
		$this->model = $model;
		$this->repository = new RestifyRepository();
		$this->persister = new RestifyPersister();
	}

	public function findAll($options = array()) {
		return $this->repository->findAll($this->model, $options);
	}

	public function findById($id, $options = array()) {
		return $this->repository->findById($this->model, $id, $options);
	}

	public function create($data = array(), $filterKey = false) {
		return $this->persister->create($this->model, $data, $filterKey);
	}

	public function save($data = null, $validate = true, $fieldList = array()) {
		return $this->persister->save($this->model,  $data, $validate, $fieldList);
	}

	public function saveAll($data = null, $options = array()) {
		return $this->persister->saveAll($this->model, $data, $options);
	}

	public function updateAll($fields, $conditions = true) {
		return $this->persister->updateAll($this->model, $fields, $conditions);
	}

	public function delete($id = null, $cascade = true) {
		return $this->persister->delete($this->model, $id, $cascade);
	}

	public function getErrors() {
		return $this->persister->getNotification()
			->getErrors();
	}

	public function getMetadata($cleanResultSet = true) {
		return array(
			'metadata' => array(
				'resultset' => $this->repository->getResultSet($cleanResultSet)
			)
		);
	}
}
