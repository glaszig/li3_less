<?php

namespace li3_less\core;

use lithium\core\Libraries;
use lithium\storage\Cache;
use lithium\util\Inflector;

use \Exception;

require dirname(__DIR__).'/libraries/lessphp/lessc.inc.php';

class Less extends \lithium\core\StaticObject {

	/**
	 * Get content of file, parse it with lessc and return formatted css
	 *
	 * @todo allow for css-file name only, and search it in all avail. webroots
	 * @param string $file full path to file
	 * @param array $options Additional options to control flow of method
	 *                       - header - controls, whether to prepend a header
	 *                       - cache - controls, whether to cache the result
	 *                       - cachePath - Where to cache files, defaults to
	 *                                     resources/tmp/cache
	 * @return string|boolean generated css, false in case of error
	 */
	public static function file($file, array $options = array()) {
		$defaults = array(
			'header' => true,
			'cache' => true,
			'cachePath' => Libraries::get(true, 'resources') . '/tmp/cache',
			'cacheKey' => Inflector::slug(str_replace(
				array(LITHIUM_APP_PATH, '.less'),
				array('', '.css'), $file)),
		);
		$options += $defaults;

		$css_file = $options['cachePath'] .'/'. $options['cacheKey'];
		if(file_exists($css_file) && filemtime($css_file) >= filemtime($file)) {
			return file_get_contents($css_file);
		}

		if (!file_exists($file)) {
			return false;
		}

		try {
			$less = new \lessc($file);
			$output = $less->parse();
		} catch(Exception $e) {
			$output = "/* less compiler exception: {$e->getMessage()} */";
		}

		if ($options['header']) {
			$output = static::_prependHeader($output);
		}

		if ($options['cache']) {
			file_put_contents($css_file, $output);
		}
		return $output;
	}

	/**
	 * parses input string with lessc and returns generated css
	 *
	 * @param string $input complete less code, may include @import statements
	 * @param array $options Additional options to control flow of method
	 *                       - cache - controls, whether to cache the result
	 *                       - cachePath - Where to cache files, defaults to
	 *                                     resources/tmp/cache
	 * @return string|boolean generated css, false in case of error
	 */
	public static function parse($input, array $options = array()) {
		$defaults = array(
			'header' => true,
			'cache' => true,
			'cacheConfig' => 'default',
			'cacheKey' => md5($input),
		);
		$options += $defaults;

		if ($output = Cache::read($options['cacheConfig'], $options['cacheKey'])) {
			return $output;
		}

		try {
			$less = new \lessc;
			$output = $less->parse($input);
		} catch(Exception $e) {
			header('Content-Type: text/css', true, 500);
			$output = "/* less compiler exception: {$e->getMessage()} */";
		}

		if ($options['header']) {
			$output = static::_prependHeader($output);
		}

		if ($options['cache']) {
			Cache::read($options['cacheConfig'], $options['cacheKey'], $output);
		}
		return $output;
	}

	/**
	 * prepends header before given $output
	 *
	 * @return string
	 */
	protected static function _prependHeader($output) {
		$header = array(
			'/**',
			' * generated '.date('r'),
			' * by li3_less/lessphp',
			' * ',
			' * @link https://github.com/bruensicke/li3_less',
			' * @link http://leafo.net/lessphp',
			' */'
		);
		return join(PHP_EOL, $header) . PHP_EOL . $output;
	}
}
