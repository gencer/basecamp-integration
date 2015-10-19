<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Basecamp api client for management of todo lists
 *
 * @author Atul Atri
 */
class SWIFT_APITodos extends SWIFT_APIBase
{

	/**
	 * Constructor
	 *
	 * @author Atul Atri
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
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Post a to do item
	 *
	 * @author Atul Atri
	 *
	 * @param int    $_bcProjectId project id
	 * @param int    $_todolistId  todolist id
	 * @param string $_content     todo content
	 * @param int    $_personId    person id if todo is being assigned to some one
	 * @param string $_dueDate     todo due date. Date format should be in ISO 8601 format (like "2012-03-27T16:00:00-05:00") otherwise posting will fail
	 * @param bool   $_isMulti     true if this request is part of multiple requests
	 *
	 * @return String json response from service or true is $_isMulti was true
	 * @throws SWIFT_API_Exception If curl request is failed or respose code is not 201
	 */
	public function PostTodo($_bcProjectId, $_todolistId, $_content, $_personId = null, $_dueDate = null, $_isMulti = false)
	{
		$_todoPostUrl = SWIFT_ConfigManager::Get('TODO_POST_SUB_URL');
		$_todoPostUrl = sprintf($_todoPostUrl, $_bcProjectId, $_todolistId);

		$_baseUrl      = $this->BcApiUrl();
		$_url          = $_baseUrl . $_todoPostUrl;
		$_CurlInstance = SWIFT_APIHttp::GetInstance();

		$_postArr = array('content' => $_content);

		if ($_dueDate) {
			$_postArr['due_at'] = $_dueDate;
		}

		if ($_personId) {
			$_postArr['assignee']['id']   = $_personId;
			$_postArr['assignee']['type'] = 'Person';
		}

		$_postStr = json_encode($_postArr);

		if (!$_isMulti) {
			$_responseArr = $_CurlInstance->SendSingedRequest($_url, $_postStr, 'POST');
		} else {
			$_CurlInstance->AddSingedRequest($_url, $_postStr, 'POST');

			return true;
		}

		return $this->HandlePostTodoRes($_responseArr);
	}

	/**
	 * Hnadle response returned by PostTodo
	 *
	 * @author Atul Atri
	 *
	 * @param string $_response json response string
	 *
	 * @return bool "true" on Success, "false" otherwise
	 * @throws SWIFT_API_Exception If curl request is failed or respose code is not 201
	 */
	public function HandlePostTodoRes($_response)
	{
		$_code        = $_response[SWIFT_APIHttp::SRV_CODE];
		$_responseStr = $_response[SWIFT_APIHttp::SRV_RESPONSE];

		$_curlError = $_response[SWIFT_APIHttp::SRV_ERROR];

		if ($_curlError) {
			throw new SWIFT_API_Exception($this->Language->Get('BC_ERR_INVALID_SETTINGS') . $_curlError);
		}

		if ($_code != 201) {
			throw new SWIFT_API_Exception($this->Language->Get('BC_TODO_POST_ERR') . $_responseStr, $_code);
		}

		return $_responseStr;
	}

	/**
	 * Delete a to do item
	 *
	 * @author Atul Atri
	 *
	 * @param int  $_bcProjectId project id
	 * @param int  $_todoId      todolist id
	 * @param bool $_isMulti     true if this request is part of multiple requests
	 *
	 * @return String json response from service or true is $_isMulti was true
	 * @throws SWIFT_API_Exception If curl request is failed or respose code is not 201
	 */
	public function DeleteTodo($_bcProjectId, $_todoId, $_isMulti = false)
	{
		$_todoDeleteUrl = SWIFT_ConfigManager::Get('TODO_DELETE_SUB_URL');
		$_todoDeleteUrl = sprintf($_todoDeleteUrl, $_bcProjectId, $_todoId);
		$_baseUrl       = $this->BcApiUrl();
		$_url           = $_baseUrl . $_todoDeleteUrl;

		$_CurlInstance = SWIFT_APIHttp::GetInstance();

		if (!$_isMulti) {
			$_responseArr = $_CurlInstance->SendSingedRequest($_url, null, 'DELETE');

			return $this->HandleDeleteTodoRes($_responseArr);
		}

		$_CurlInstance->AddSingedRequest($_url, null, 'DELETE');

		return true;
	}

	/**
	 * Hnadle response returned by delete todo
	 *
	 * @author Atul Atri
	 *
	 * @param string $_response json response string
	 *
	 * @return bool "true" on Success, "false" otherwise
	 * @throws SWIFT_API_Exception If curl request is failed or respose code is not 201
	 */
	public function HandleDeleteTodoRes($_response)
	{
		$_code        = $_response[SWIFT_APIHttp::SRV_CODE];
		$_responseStr = $_response[SWIFT_APIHttp::SRV_RESPONSE];

		$_curlError = $_response[SWIFT_APIHttp::SRV_ERROR];

		if ($_curlError) {
			throw new SWIFT_API_Exception($this->Language->Get('BC_ERR_INVALID_SETTINGS') . $_curlError);
		}

		if ($_code != 204 || $_code == 403) {
			throw new SWIFT_API_Exception($this->Language->Get('basecamp_todo_deleted_failure') . $_responseStr, $_code);
		}

		return $_responseStr;
	}

	/**
	 * Get a todo item
	 *
	 * @author Atul Atri
	 *
	 * @param int  $_bcProjectId project id
	 * @param int  $_todoId      todo id
	 * @param bool $_isMulti     true if this request is part of multiple requests
	 *
	 * @return String json response from service or true is $_isMulti was true
	 */
	public function GetTodo($_bcProjectId, $_todoId, $_isMulti = false)
	{
		$_todoGetUrl   = SWIFT_ConfigManager::Get('TODO_GET_SUB_URL');
		$_todoGetUrl   = sprintf($_todoGetUrl, $_bcProjectId, $_todoId);
		$_baseUrl      = $this->BcApiUrl();
		$_todoGetUrl   = $_baseUrl . $_todoGetUrl;
		$_CurlInstance = SWIFT_APIHttp::GetInstance();


		if (!$_isMulti) {
			$_responseArr = $_CurlInstance->SendSingedRequest($_todoGetUrl, null, 'GET');
		} else {
			$_CurlInstance->AddSingedRequest($_todoGetUrl, null, 'GET');

			return true;
		}

		return $this->HandleGetTodoRes($_responseArr);
	}

	/**
	 * handle Get todo response
	 *
	 * @author Atul Atri
	 *
	 * @param string $_response json response string
	 *
	 * @return bool "true" on Success, "false" otherwise
	 * @throws SWIFT_API_Exception If curl request is failed or respose code is not 201
	 */
	public function HandleGetTodoRes($_response)
	{
		$_code        = $_response[SWIFT_APIHttp::SRV_CODE];
		$_responseStr = $_response[SWIFT_APIHttp::SRV_RESPONSE];

		$_curlError = $_response[SWIFT_APIHttp::SRV_ERROR];

		if ($_curlError) {
			throw new SWIFT_API_Exception($this->Language->Get('BC_ERR_INVALID_SETTINGS') . $_curlError);
		}

		if ($_code != 200) {
			throw new SWIFT_API_Exception($this->Language->Get('basecamp_todo_get_failure') . $_responseStr, $_code);
		}

		return $_responseStr;
	}

}

?>