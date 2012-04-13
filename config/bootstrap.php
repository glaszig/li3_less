<?php
/**
 * li3_less
 *
 * Copyright 2010, glaszig at gmail dot com
 *
 * @author glaszig at gmail dot com
 * @copyright Copyright 2010, glaszig at gmail dot com
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use lithium\action\Dispatcher;
use lithium\core\Libraries;
use lithium\storage\Cache;
use lithium\action\Response;
use li3_less\core\Less;

Dispatcher::applyFilter('run', function($self, $params, $chain) {

	if(!strstr($params['request']->url, '.less')) {
		return $chain->next($self, $params, $chain);
	}

	// look for a matching less file
	$less_file =  LITHIUM_APP_PATH.'/webroot/' . str_replace('.less', '.css.less', $params['request']->url);

	if(!file_exists($less_file)) {
		return $chain->next($self, $params, $chain);
	}

	// found, so we parse this file
	$output = Less::file($less_file, array('cache' => true));
	return new Response(array(
		'body' => $output,
		'headers' => array('Content-type' => 'text/css')
	));
});

?>
