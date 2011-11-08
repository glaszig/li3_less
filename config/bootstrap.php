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

require dirname(__DIR__).'/libraries/lessphp/lessc.inc.php';

/**
 * TODO: Make sure, that subfolders are possible (currently not)
 *
 */
Dispatcher::applyFilter('run', function($self, $params, $chain) {
	
	if(strstr($params['request']->url, '.css')) {
		
		// look for a matching less file
		$basename = basename($params['request']->url);
		$css_file = "less/$basename";
		$less_file = "less/$basename.less";
		if(file_exists($less_file)) {
			
			header('Content-Type: text/css');

			// if there's an up-to-date css file, serve it
			if(file_exists($css_file) && filemtime($css_file) >= filemtime($less_file)) {
				return file_get_contents($css_file);
			}

			try {
				$less = new lessc($less_file);
				$output = array(
					'/**',
					' * generated '.date('r'),
					' * by li3_less/lessphp',
					' * ',
					' * @link https://github.com/glaszig/li3_less',
					' * @link http://leafo.net/lessphp',
					' */'
				);
				$output = join(PHP_EOL, $output) . PHP_EOL . $less->parse();
			} catch(Exception $e) {
				header('Content-Type: text/css', true, 500);
				$output = "/* less compiler exception: {$e->getMessage()} */";
			}

			file_put_contents("less/$basename", $output);
			return $output;

		}

	}
		
	return $chain->next($self, $params, $chain);

});

?>
