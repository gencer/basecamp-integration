<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * The  exception
 *
 * @author Atul Atri
 */
class SWIFT_Json_Exception extends SWIFT_Exception
{

	/**
	 * Constructor
	 *
	 * @author Atul Atri
	 *
	 * @param string $_errorMessage The Error Message
	 * @param int    $_errorCode    The Error Code
	 */
	public function __construct($_errorMessage, $_errorCode = 0)
	{
		parent::__construct($_errorMessage, $_errorCode);

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Atul Atri
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

}

?>