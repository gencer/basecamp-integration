<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * A filter interface
 *
 * @author Atul Atri
 */
interface SWIFT_FilterManager_Interface
{
	//filter types
	const FILTER_TYPE_VALIDATE = 1;

	/**
	 * Filter method
	 *
	 * @author Atul Atri
	 *
	 * @param mixed  $_data    data to be filtered
	 * @param string $_id      filter name
	 * @param int    $_flags   flags
	 * @param int    $_type    filter type
	 * @param array  $_options options array
	 *
	 * @return mixed true|false if this is a validation method of  sanitized mixed $_data if this is sanitization method
	 */
	public static function Filter($_data, $_id, $_flags = null, array $_options = array(), $_type = self::FILTER_TYPE_VALIDATE);
}

?>