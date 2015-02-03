<?php
App::uses('RestifyModelExtension', 'Restify.Model');

class RestifyBehavior extends ModelBehavior {


	public function setup(Model $model, $config = array()) {
		$model->Restify = new RestifyModelExtension($model);
	}
}
