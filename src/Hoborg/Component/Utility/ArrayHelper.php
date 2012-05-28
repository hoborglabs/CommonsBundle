<?php
namespace Hoborg\Component\Utility;

class ArrayHelper {

	/**
	 * Merge two arrays recursively overwriting the keys in the first array
	 * if such key already exists
	 *
	 * @param mixed $a Left array to merge right array into
	 * @param mixed $arrayB Right array to merge over the left array
	 * @return mixed
	 */
	public static function arrayMergeRecursive($arrayA, $arrayB) {
		// merge arrays if both variables are arrays
		if (is_array($arrayA) && is_array($arrayB)) {
			// loop through each right array's entry and merge it into $arrayA
			foreach ($arrayB as $key => $value) {
				if (isset($arrayA[$key])) {
					$arrayA[$key] = self::arrayMergeRecursive($arrayA[$key], $value);
				} else {
					if ($key === 0) {
						$arrayA= array(0 => self::arrayMergeRecursive($arrayA, $value));
					} else {
						$arrayA[$key] = $value;
					}
				}
			}
		} else {
			// one of values is not an array
			$arrayA = $arrayB;
		}

		return $arrayA;
	}

	/**
	 * Returns configuration option for key in format `foo.bar.baz`.
	 *
	 * @param Zend_Config $config
	 * @param string $key
	 *
	 * @return Zend_Config
	 */
	public static function getValueByKey($config, $key, $default = null) {
		if (empty($config)) {
			return $default;
		}
		if (!is_array($config)) {
			$config = $config->toArray();
		}

		$keys = explode('.', $key, 2);
		if (!isset($keys[1])) {
			return $config[$keys[0]];
		}
		if ( !is_array($config[$keys[0]]) ) {
			return $default;
		}

		return static::getValueByKey($config[$keys[0]], $keys[1], $default);
	}

	/**
	 * Returns configuration option for key in format `foo.bar.baz`.
	 *
	 * @param Zend_Config $config
	 * @param string $key
	 *
	 * @return Zend_Config
	 */
	public static function getValueByBestMatchingKey(array $config, $key) {
		$keys = explode('.', $key, 2);

		if (!isset($config[$keys[0]])) {
			return $config;
		}

		if ( !is_array($config[$keys[0]]) ) {
			return $config;
		}

		if (!isset($keys[1])) {
			return $config[$keys[0]];
		}

		return static::getValueByBestMatchingKey($config[$keys[0]], $keys[1]);
	}
}