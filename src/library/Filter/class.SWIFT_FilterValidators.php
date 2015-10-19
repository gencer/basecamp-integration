<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Validation functions
 *
 * @author Atul Atri
 */
class SWIFT_FilterValidators
{
	//flags
	const STR_INCLUDE_NUMERIC = 1;
	const STR_ALPHANUMERIC = 2;
	const STR_ALLOW_EMPTY = 4;

	//patterns
	const PATTERN_ALPHANUMERIC = '/^[a-zA-Z0-9]+$/';

	/**
	 * Validate  a string
	 *
	 * @author Atul Atri
	 *
	 * @param string $_string  string to be checked
	 * @param int    $_flags   flags
	 * @param array  $_options options e.g. array('min' => 1, 'max' => 10)
	 *
	 * @return bool "true" if validation passed, false otherwise
	 */
	public static function ValidateString($_string, $_flags = 0, array $_options = array())
	{
		if (is_string($_string)) {
			//should allow numeric strings
			if (is_numeric($_string) && !($_flags & self::STR_INCLUDE_NUMERIC)) {
				return flase;
			}
			//should allow empty
			if ($_string === "" && !($_flags & self::STR_ALLOW_EMPTY)) {
				return false;
			}
			//string alpha numeric
			if (($_flags & self::STR_ALPHANUMERIC) && !preg_match(self::PATTERN_ALPHANUMERIC, $str)) {
				return false;
			}
			//check length
			if (isset($_options['min']) && mb_strlen($_string) < $_options['min']) {
				return false;
			}

			if (isset($_options['max']) && mb_strlen($_string) > $_options['max']) {
				return false;
			}

			return true;
		}

		return false;
	}
}