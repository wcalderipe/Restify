<?php

// TODO: Read more about Router::connectNamed method

Router::connect(
	'/api/:controller',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'index',
		'[method]' => 'GET',
	)
);

Router::connect(
	'/api/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'view',
		'[method]' => 'GET',
	),
	array(
		'pass' => array('id')
	)
);

Router::connect(
	'/api/:controller',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'add',
		'[method]' => 'POST',
	)
);

Router::connect(
	'/api/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'edit',
		'[method]' => 'PUT',
	),
	array(
		'pass' => array('id')
	)
);

Router::connect(
	'/api/:controller/:id',
	array(
		'prefix' => 'api',
		'api' => true,
		'ext' => 'json',
		'action' => 'delete',
		'[method]' => 'DELETE',
	),
	array(
		'pass' => array('id')
	)
);
