<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * This is the base class to be extendibale by all basecamp apis. This class will
 * provide abstact functionality that can be used by all basecamp api calls.
 *
 * @author Atul Atri
 */
class SWIFT_APIBase extends SWIFT_Library
{

	//used count the req retry count
	private $_reqRetryCount = 0;
	//max req retires
	private $_maxSendReqRetries = 1;

	/**
	 * Constructor
	 *
	 * @author Atul Atri
	 *
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
	 * @author Atul Atri
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Gives basecamp base api url
	 *
	 * @author Atul Atri
	 *
	 * @return string basecamp base api url
	 */
	protected function BcApiUrl()
	{
		$_baseUrl = SWIFT_ConfigManager::get('BC_BASE_URL');
		$_accountId = $this->Settings->Get('bc_base_acc_id');
		$_subApiUrl = SWIFT_ConfigManager::get('API_SUB_URL');

		return $_baseUrl . $_accountId . $_subApiUrl;
	}

	/**
	 * Get Url to todo on basecmap
	 *
	 * @author Atul Atri
	 *
	 * @param Int $_todoProjectId project id where todo is posted
	 * @param Int $_todoId todo id
	 *
	 * @return string basecamp Url to todo on basecmap
	 */
	public static function BasecampTodoUrl($_todoProjectId, $_todoId)
	{
		$_SWIFT = SWIFT::GetInstance();
		$_basecampTodoUrl = SWIFT_ConfigManager::Get("BC_BASE_URL", 'basecamp').SWIFT_ConfigManager::Get("TODO_SUB_URL", 'basecamp');
		$_accountId = $_SWIFT->Settings->Get('bc_base_acc_id');
		$_basecampTodoUrl = sprintf($_basecampTodoUrl, $_accountId, $_todoProjectId, $_todoId);

		return $_basecampTodoUrl;
	}

}

?>