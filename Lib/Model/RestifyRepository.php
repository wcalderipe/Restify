<?php
App::uses('RequestQueryParser', 'Restify.Network/Parser');

class RestifyRepository {

	protected $baseQueryLimit = 20;

	protected $resultSet = array(
		'offset' => null,
		'limit'  => null,
		'count'  => null,
	);

	public function __construct() {
	}

	public function findAll(Model $model, $options = array()) {
		$options += $this->getRequestOptions($model);
 		$results = $model->find('all', $options);
 		$results = Set::extract("/{$model->alias}/.", $results);
 		$this->applyResultSet($model, $options);

 		return $this->mergeMetadata($results);
	}

	public function findById(Model $model, $id, $options = array()) {
		// if (!$model->exists($id)) {
		// 	throw new RestifyNotFoundException("{$model->alias} not found");
		// }

		$options += $this->getRequestOptions($model);
		$options += array(
			'conditions' => array(
				"{$model->alias}.id" => $id
			)
		);

		$result = $model->find('first', $options);
		$result = Set::extract("/{$model->alias}/.", $result);
		$this->applyResultSet($model, $options);

		return $this->mergeMetadata($result, array('count'));
	}

	protected function applyResultSet(Model $model, Array $options) {
		$this->resultSet['offset'] = isset($options['offset']) ?
			(int) $options['offset'] : null;

		$this->resultSet['limit'] = isset($options['limit']) ?
			(int) $options['limit'] : null;

		$this->resultSet['count'] = $model->find('count');
	}

	public function getResultSet($cleanResultSet = true, $excludeKeys = array()) {
		if ($cleanResultSet) {
			return $this->getCleanResultSet($excludeKeys);
		}

		return $this->getResultSetWithout($excludeKeys);
	}

	private function getResultSetWithout($excludeKeys = array()) {
		$tmp = $this->resultSet;
		foreach ($excludeKeys as $key) {
			unset($tmp[$key]);
		}

		return $tmp;
	}

	private function getCleanResultSet($excludeKeys = array()) {
		$resultSet = $this->getResultSetWithout($excludeKeys);
		foreach ($resultSet as $key => $value) {
			if (null === $resultSet[$key]) {
				unset($resultSet[$key]);
			}
		}

		return $resultSet;
	}

	private function getRequestOptions(Model $model) {
		$request = Router::getRequest();
		$parser  = new RequestQueryParser($request);
		$options = $parser->getApiParams();

		if (isset($options['offset']) && !isset($options['limit'])) {
			$options['limit'] = $this->baseQueryLimit;
		}

		// This is to avoid a PDO exception for a missing table field exception
		if (isset($options['fields'])) {
			$fields = explode(',', $options['fields']);
			foreach ($fields as $key => $field) {
				if (!$model->hasField($field)) {
					unset($fields[$key]);
				}
			}
			$options['fields'] = $fields;
		}

		return $options;
	}

	private function mergeMetadata($results = array(), $excludeKeys = array()) {
 		$resultSet = $this->getResultSet(true, $excludeKeys);
 		if (!empty($resultSet)) {
	 		$results += array(
	 			'metadata' => array(
	 				'resultset' => $resultSet
	 			)
	 		);
 		}

 		return $results;
	}
}
