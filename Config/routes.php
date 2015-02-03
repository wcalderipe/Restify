<?php

// TODO: Read more about Router::connectNamed method

Router::connect(
	'/api/:version/:controller',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'index',
		'[method]' => 'GET',
	),
	array(
		'pass' => 'version'
	)
);

Router::connect(
	'/api/:version/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'view',
		'[method]' => 'GET',
	),
	array(
		'pass' => array(
			'id',
			'version'
		)
	)
);

Router::connect(
	'/api/:version/:controller',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'add',
		'[method]' => 'POST',
	),
	array(
		'pass' => 'version'
	)
);

Router::connect(
	'/api/:version/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'edit',
		'[method]' => 'PUT',
	),
	array(
		'pass' => array(
			'id',
			'version'
		)
	)
);

Router::connect(
	'/api/:version/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'delete',
		'[method]' => 'DELETE',
	),
	array(
		'pass' => array(
			'id',
			'version'
		)
	)
);
