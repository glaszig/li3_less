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

Dispatcher::applyFilter('run', function($self, $params, $chain) {

	if(!strstr($params['request']->url, '.css')) {
		return $chain->next($self, $params, $chain);
	}

	// look for a matching less file
	$basename = basename($params['request']->url);
	$less_path =  LITHIUM_APP_PATH.'/webroot/less';
	$less_file = str_replace('.css', '.less', "$less_path/$basename");

	if(!file_exists($less_file)) {
		return $chain->next($self, $params, $chain);
	}

	// found, so we parse this file
	$output = Less::file($less_file, array('cache' => true));
	return new Response(array(
		'body' => $output,
		'headers' => array('Content-type' => 'text/javascript')
	));
});
