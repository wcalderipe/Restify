<?php

Configure::write('Restify.apiQueryParams', array(
	'offset',
	'limit',
	'sort',
	'fields',
	'token'
));

// Preload all exceptions
$exceptionFolder = APP . 'Plugin' . DS . 'Restify' . DS . 'Lib' . DS . 'Exception' . DS;
foreach (new \DirectoryIterator($exceptionFolder) as $fileInfo) {
	if ($fileInfo->isDot()) {
		continue;
	}

	App::uses(str_replace('.php', '', $fileInfo->getFilename()), 'Restify.Exception');
}
