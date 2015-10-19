<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Write a json response
 *
 * @author Atul Atri
 */
class SWIFT_JsonWriter extends SWIFT_Library
{

	private  static $_instance = null;

	private $_messages = array();
	private $_data = array();
	private $_responseCode = 0;

	//message types
	const MESSAGE_SUCCESS = 'suceess';
	const MESSAGE_WARN = 'warnings';
	const MESSAGE_ERROR = 'errors';

	/**
	 * Constructor
	 *
	 * @author Atul Atri
	 * @throws SWIFT_Exception
	 */
	public function __construct()
	{
		parent::__construct();

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Atul atri
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Get a singleton instance
	 *
	 * @author Atul Atri
	 *
	 * @return SWIFT_JsonWriter
	 */
	public static function GetInstance()
	{
		if (!self::$_instance) {
			$_class = __CLASS__;
			self::$_instance = new $_class;
		}

		return self::$_instance;
	}

	/**
	 * Add a message
	 *
	 * @author Atul Atri
	 * @param mixed $_message a message
	 * @param string $_type message type, three message type are defined by this,
	 *				   though it is not restricted to these three types
	 *
	 * @return SWIFT_JsonWriter
	 * @throws SWIFT_Json_Exception $_type is not string
	 */
	public function AddMessgae($_message, $_type = self::MESSAGE_SUCCESS)
	{
		if(!is_string($_type)){
			throw new SWIFT_Json_Exception('type must be a string');
		}

		if(!isset($_messages[$_type])){
			$this->_messages[$_type] = array();
		}

		$this->_messages[$_type][] = $_message;

		return $this;
	}

	/**
	 * set data
	 *
	 * @author Atul Atri
	 * @return SWIFT_JsonWriter
	 */
	public function SetData($_data)
	{
		$this->_data = $_data;

		return $this;
	}

	/**
	 * Set a response code
	 *
	 * @author Atul Atri
	 * @param int $_code response code
	 *
	 * @return SWIFT_JsonWriter
	 *
	 * @throws SWIFT_Json_Exception $code is not integer
	 */
	public function setResponseCode($_code)
	{
		if(!is_int($_code)){
			throw new SWIFT_Json_Exception('input param must be an integer');
		}

		$this->_responseCode = $_code;

		return $this;
	}

	/**
	 * return a json string. String is prepended with an infinte for loop
	 * <br />
	 * e.g.
	 * <br />
	 * for( ; ; );{json response}
	 *
	 * @author Atul Atri
	 *
	 * @return String
	 */
	public function toJson()
	{
		$_returnMe = array();

		if($this->_responseCode){
			$_returnMe['responseCode'] = $this->_responseCode;
		}

		if(!empty($this->_messages)){
			$_returnMe['messages'] = $this->_messages;
		}

		if(!empty($this->_data)){
			$_returnMe['data'] = $this->_data;
		}

		$_jsonStr = json_encode($_returnMe);
		$_jsonStr = "for( ; ; );$_jsonStr";

		return $_jsonStr;
	}

}