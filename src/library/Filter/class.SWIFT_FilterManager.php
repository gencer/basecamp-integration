<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Filter Manager
 *
 * @author Atul Atri
 */
class SWIFT_FilterManager extends SWIFT_Library implements SWIFT_FilterManager_Interface
{
	//validators
	//validate boolen, default implementation uses php filters

	const VALIDATE_BOOL = "boolean";
	//validate email, default implementation uses php filters
	const VALIDATE_EMAIL = "email";
	//validate flaot, default implementation uses php filters
	const VALIDATE_FLOAT = "float";
	//validate int, default implementation uses php filters
	const VALIDATE_INT = "int";
	//validate ip, default implementation uses php filters
	const VALIDATE_IP = "ip";
	//validate based on reguler expression, default implementation uses php filters
	const VALIDATE_REGEX = "regex";
	//validate url, default implementation uses php filters
	const VALIDATE_URL = "url";
	//validate string
	const VALIDATE_STR = "string";

	//flages
	const FLAG_VALIDATE_OPTIONAL = 2048;

	//instance
	private static $_instance = null;
	private $_validators = array(
		self::VALIDATE_BOOL  => 'SWIFT_FilterManager', self::VALIDATE_EMAIL => 'SWIFT_FilterManager',
		self::VALIDATE_FLOAT => 'SWIFT_FilterManager', self::VALIDATE_INT => 'SWIFT_FilterManager',
		self::VALIDATE_IP    => 'SWIFT_FilterManager', self::VALIDATE_REGEX => 'SWIFT_FilterManager',
		self::VALIDATE_URL   => 'SWIFT_FilterManager', self::VALIDATE_STR => 'SWIFT_FilterManager'
	);

	/**
	 * instantiation is forbidden
	 *
	 * @author Atul Atri
	 */
	public function __construct()
	{
		//instantiation is forbidden
	}

	/**
	 * get In
	 *
	 * @author Atul Atri
	 *
	 * @return SWIFT_FilterManager
	 */
	public static function GetInstance()
	{
		if (!self::$_instance) {
			$_className      = __CLASS__;
			self::$_instance = new $_className;
		}

		return self::$_instance;
	}

	/**
	 * Add a validator call back
	 *
	 * @author Atul Atri
	 *
	 * @param string $_class          validator class name
	 * @param mixed  $_validatorNames validator names
	 *
	 * @throws SWIFT_Filter_Exception if validator name is not a string or $_class does not implemets SWIFT_Filter_Interface
	 */
	public function AddValidator($_class, $_validatorNames)
	{
		if (is_array($_validatorNames)) {

			foreach ($_validatorNames as $_nextValidator) {

				if (!is_string($_nextValidator)) {
					break;
				}
				$this->_validators[$_nextValidator] = $_class;
			}

			return;
		} else if (is_string($_nextValidator)) {
			$this->_validators[$_nextValidator] = $_class;

			return;
		}

		throw new SWIFT_Filter_Exception('validator name must be a string or array of strings');
	}

	/**
	 * Validate an array of input
	 *
	 * @author Atul Atri
	 *
	 * @param array $_data            data to be validated e.g
	 *                                array(
	 *                                ['name'] => 'Atul Atri',
	 *                                ['money'] => 11
	 *                                )
	 * @param array $_filters         validators to be applied
	 *                                e.g.
	 *                                $_filterArray = array(
	 *                                ['money'] => array(
	 *                                array('float' , FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX, array(min_range => 1, max_range => 10), 'lang_key_invalid_float')
	 *                                ),
	 *                                ['name'] => array('required', 'lang_key_name_can_not_be_empty'
	 *                                )
	 * @param bool  $_breakOnError    break on first error
	 * @param bool  $_markFieldInForm mark error field array
	 *
	 * '
	 * @return mixed true if $_data passed all validations,  array of errors if error strings are given or empty array
	 */
	public function Validate(array $_data, array $_filters, $_breakOnError = false, $_markFieldInForm = true, $_checkCsrfHash = true)
	{
		$_errorArray       = array();
		$_ValidationFailed = false;

		if ($_checkCsrfHash && !SWIFT_Session::CheckCSRFHash($_data['csrfhash'])) {
			return array(SWIFT::GetInstance()->Language->Get('msgcsrfhash'));
		}

		foreach ($_data as $_key => $_value) {

			if (isset($_filters[$_key])) {
				$_filterArray = $_filters[$_key];
				$_isValid     = $this->IsValid($_value, $_filterArray);

				if ($_isValid !== true) {
					$_ValidationFailed = true;

					if (is_array($_isValid)) {
						$_errorArray[$_key] = $_isValid[0];
					}

					if ($_markFieldInForm) {
						SWIFT::GetInstance()->UserInterface->CheckFields($_key);
					}

					if ($_breakOnError) {
						break;
					}
				}
			}
		}

		if ($_ValidationFailed) {
			return $_errorArray;
		}

		return true;
	}

	/**
	 * Validate an array of input
	 *
	 * @author Atul Atri
	 *
	 * @param mixed $_data         data to be validated
	 * @param array $_filterArray  array of filters to be applied
	 *                             e.g.
	 *                             $_filterArray = array(
	 *                             array('float' , FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX, array(min_range => 1, max_range => 10), 'lang_key_invalid_float')
	 *                             )
	 * @param bool  $_breakOnError break on first error
	 *                             '
	 *
	 * @return true if $_data passed all validations, false or array of errors if error strings are given
	 * @throws SWIFT_Exception
	 */
	public function IsValid($_data, array $_filterArray, $_breakOnError = true)
	{
		$_errorArray       = array();
		$_ValidationFailed = false;

		foreach ($_filterArray as $_nextFilterConfig) {
			$_validatorId = null;
			$_flag        = 0;
			$_options     = array();
			$_errrorKey   = null;

			if (is_string($_nextFilterConfig)) {
				$this->IsValidatorRegistered($_nextFilterConfig);
				$_validatorId = $_nextFilterConfig;
			} else if (is_array($_nextFilterConfig)) {

				if (isset($_nextFilterConfig[0])) {
					$_validatorId = $_nextFilterConfig[0];
					$this->IsValidatorRegistered($_validatorId);
				}

				if (isset($_nextFilterConfig[1])) {
					$_flag = $_nextFilterConfig[1];
					$this->IsFlagTypeValid($_flag, $_validatorId);
				}

				if (isset($_nextFilterConfig[2])) {
					$_options = $_nextFilterConfig[2];
					$this->IsOptionsTypeValid($_options, $_validatorId);
				}

				if (isset($_nextFilterConfig[3])) {
					$_errrorKey = $_nextFilterConfig[3];
				}

				if (isset($_nextFilterConfig['error'])) {
					$_errrorKey = $_nextFilterConfig['error'];
				}
			}

			if ($_validatorId) {
				$_validatorClass = $this->_validators[$_validatorId];
				/* @var $_validatorClass SWIFT_Filter_Interface */
				$_isValid = $_validatorClass::Filter($_data, $_validatorId, $_flag, $_options, self::FILTER_TYPE_VALIDATE);

				if (!$_isValid) {
					$_ValidationFailed = true;

					if ($_errrorKey) {
						$_errorArray[] = SWIFT::GetInstance()->Language->Get($_errrorKey);
					}

					if ($_breakOnError) {
						break;
					}
				}
			} else {
				throw new SWIFT_Filter_Exception("Could not find valid validator name");
			}
		}

		if ($_ValidationFailed) {

			if (count($_errorArray) > 0) {
				return $_errorArray;
			}

			return false;
		}

		return true;
	}

	/**
	 *  Check if validator filter is registred
	 *
	 * @author Atul Atri
	 *
	 * @param string $_filterName validator name
	 *
	 * @return bool "true"  if $_filterName is registered as validate function
	 * @throws SWIFT_Filter_Exception if $_filterName is not registered
	 */
	private function IsValidatorRegistered($_filterName)
	{
		if (!isset($this->_validators[$_filterName])) {
			throw new SWIFT_Filter_Exception("No validate filter registered for name $_filterName");
		}

		return true;
	}

	/**
	 *  Check if $_flag is int
	 *
	 * @author Atul Atri
	 *
	 * @param int    $_flag          flag
	 * @param string $_validatorName validator Name
	 *
	 * @return bool "true"  if $_flag is int
	 * @throws SWIFT_Filter_Exception if $_filterName is not int
	 */
	private function IsFlagTypeValid($_flag, $_validatorName)
	{
		if (!is_int($_flag)) {
			throw new SWIFT_Filter_Exception("Invalid flag type given for $_validatorName");
		}

		return true;
	}

	/**
	 *  Check if $_flag is int
	 *
	 * @author Atul Atri
	 *
	 * @param array  $_options       options array
	 * @param string $_validatorName validator Name
	 *
	 * @return bool "true"  if $_flag is int
	 * @throws SWIFT_Filter_Exception if $_filterName is not int
	 */
	private function IsOptionsTypeValid($_options, $_validatorName)
	{
		if (!is_array($_options)) {
			throw new SWIFT_Filter_Exception("Invalid options type given for $_validatorName");
		}

		return true;
	}

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
	public static function Filter($_data, $_id, $_flags = 0, array $_options = array(), $_type = self::FILTER_TYPE_VALIDATE)
	{
		$_instance = SWIFT_FilterManager::GetInstance();

		if ($_type !== self::FILTER_TYPE_VALIDATE) {
			throw new SWIFT_Filter_Exception("Only validation filters are supported yet.");
		}

		return $_instance->ValidateFilter($_data, $_id, $_flags, $_options);
	}

	/**
	 * Filter method
	 *
	 * @author Atul Atri
	 *
	 * @param mixed  $_data    data to be filtered
	 * @param string $_id      filter name
	 * @param int    $_flags   flags
	 * @param array  $_options options array
	 *
	 * @return mixed true|false if this is a validation method of  sanitized mixed $_data if this is sanitization method
	 */
	public function ValidateFilter($_data, $_id, $_flags = 0, array $_options = array())
	{
		$this->IsValidatorRegistered($_id);
		$this->IsFlagTypeValid($_flags, $_id);

		if ($_id === SWIFT_FilterManager::VALIDATE_EMAIL || $_id === SWIFT_FilterManager::VALIDATE_IP ||
			$_id === SWIFT_FilterManager::VALIDATE_REGEX || $_id === SWIFT_FilterManager::VALIDATE_URL
		) {
			$_id = 'validate_' . $_id;
		}

		if (($_flags & self::FLAG_VALIDATE_OPTIONAL) && empty($_data)) {
			return true;
		}

		if ($this->IsPhpFilter($_id)) {
			$_options = array();

			if ($_flags) {
				$_options['flags'] = $_flags;
			}

			if ($_options) {
				$_options['options'] = $_options;
			}

			return filter_var($_data, $_id, $_options);
		}

		if ($_id == self::VALIDATE_STR) {
			return SWIFT_FilterValidators::ValidateString($_data, $_id, $_options);
		}
	}

	/**
	 * Check if $_id is a php filter
	 *
	 * @author Atul Atri
	 *
	 * @param string $_id filter name
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	private function IsPhpFilter($_id)
	{
		if ($_id === self::VALIDATE_BOOL || $_id === self::VALIDATE_EMAIL ||
			$_id === self::VALIDATE_FLOAT || $_id === self::VALIDATE_INT ||
			$_id === self::VALIDATE_IP || $_id === self::VALIDATE_REGEX ||
			$_id === self::VALIDATE_URL
		) {

			return true;
		}

		return false;
	}

}