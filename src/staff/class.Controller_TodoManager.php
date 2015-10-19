<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * This controller to exporting of  tickets to todo list in basecamp
 *
 * @author Atul Atri
 */
class Controller_TodoManager extends Controller_Staff
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
	 * Renders todo export form
	 *
	 * @author Atul Atri
	 *
	 * @param int $_ticketId    ticket id
	 * @param int $_bcProjectId selected basecamp project id if null is given first project is selected
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function TodoExportForm($_ticketId = null, $_bcProjectId = null)
	{
		$_error = '';

		$_projetcsOpts = array(array('value' => 0, 'title' => $this->Language->Get('select')));
		$_todoOpts     = array(array('value' => 0, 'title' => $this->Language->Get('select')));
		$_peopleOpts   = array(array('value' => 0, 'title' => $this->Language->Get('select')));

		//check if we have authorization token if not, user has not integrated to basecamp
		if (!self::IsIntegrated()) {
			$_error = $this->Language->Get('basecamp_notintegrated');
			$this->View->RenderTodoExportForm($_projetcsOpts, $_todoOpts, $_peopleOpts, $_ticketId, '', $_error);

			return true;
		}


		$_CurlInstance = SWIFT_APIHttp::GetInstance();
		$_CurlInstance->InitMultiCurl();
		$_ProjectsSrv = new SWIFT_APIProject();
		$_ProjectsSrv->GetProjects(true);
		$_PeopleSrv = new SWIFT_APIPeople();
		$_PeopleSrv->GetPeople(true);

		if ($_bcProjectId) {
			//if project id is given also select list of projects
			$_TodolistsSrv = new SWIFT_APITodolists();
			$_TodolistsSrv->GetTodolists($_bcProjectId, true);
		}

		$_responses = $_CurlInstance->ExecuteMultiCurl();

		try {
			$_projetcsRes      = $_ProjectsSrv->HandleGetProjectsRes($_responses[0]);
			$_projetcsOptsTemp = $_ProjectsSrv->GetProjectSelectList($_bcProjectId, $_projetcsRes);

			if (count($_projetcsOptsTemp) == 0) {
				$_error = $this->Language->Get('nobcproject');
			} else {
				$_projetcsOpts = array_merge($_peopleOpts, $_projetcsOptsTemp);

				$_peoples        = $_PeopleSrv->HandleGetPeopleRes($_responses[1]);
				$_peopleOptsTemp = $_PeopleSrv->GetPeopleSelectList($_peoples);
				$_peopleOpts     = array_merge($_peopleOpts, $_peopleOptsTemp);

				if ($_bcProjectId) {
					try {
						//its ok if failed to retive todo list, user can change project in ui and todo list will get loaded
						$_todoListsRes = $_TodolistsSrv->HandleGetTodolistsRes($_responses[2]);
						$_todoOptsTemp = $_TodolistsSrv->GetTodolistSelectList($_todoListsRes);
						$_todoOpts     = array_merge($_todoOpts, $_todoOptsTemp);
					} catch (Exception $_e) {
						//ignorre
					}
				}
			}
		} catch (Exception $_e) {
			$_error = $_e->getMessage();
		}

		$_CurlInstance->EndMultiCurl();

		if ($_error) {
			$this->View->RenderTodoExportForm($_projetcsOpts, $_todoOpts, $_peopleOpts, $_ticketId, '', $_error);

			return true;
		}

		$this->View->RenderTodoExportForm($_projetcsOpts, $_todoOpts, $_peopleOpts, $_ticketId, '', $_error);

		return true;
	}

	/**
	 * Check If basecamp is integeragted
	 *
	 * @author Atul Atri
	 *
	 * @return bool "true" if basecamp is integeragted, "false" otherwise
	 */
	public static function IsIntegrated()
	{
		$_SWIFT     = SWIFT::GetInstance();
		$_authToken = $_SWIFT->Settings->Get("bc_auth_token");

		if (!$_authToken) {
			return false;
		}

		return true;
	}

	/**
	 * Render Add comment form
	 *
	 * @author Atul Atri
	 *
	 * @param int $_ticketId ticket id
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function AddCommentForm($_ticketId)
	{
		$_error = '';

		//check if this ticket id is linked
		$_todoInfo = SWIFT_TodoTicketLink::getTodoInfo($_ticketId);

		if (!$_todoInfo) {
			$_error = $this->Language->Get('basecamp_ticket_not_linked');
		}

		//check if we have authorization token if not, user has not integrated to basecamp
		if (!self::IsIntegrated()) {
			$_error = $this->Language->Get('basecamp_notintegrated');
		}

		$this->View->RenderAddCommentsForm($_ticketId, '', $_error);

		return true;
	}

	/**
	 * Handle the add comment form submit
	 *
	 * @author Atul Atri
	 *
	 * @return String view
	 */
	public function AddCommentSubmit()
	{
		$_error    = '';
		$_ticketId = $_POST['todo_ticketid'];
		//if this ticket id is already linked throw exception
		$_todoInfo = SWIFT_TodoTicketLink::getTodoInfo($_ticketId);

		if ($_todoInfo === false) {
			$_error = $this->Language->Get('basecamp_todo_not_linked');

			return $this->View->RenderAddCommentsForm($_ticketId, '', $_error);
		}

		$_filters = array(
			'todocomment' => array(array('string', 'error' => 'basecamp_empty_comment'))
		);

		$_isValid = SWIFT_FilterManager::GetInstance()->Validate($_POST, $_filters, true);

		if ($_isValid !== true) {
			$_error = array_pop($_isValid);

			return $this->View->RenderAddCommentsForm($_ticketId, '', $_error);
		}

		try {
			$_files = array();
			if (isset($_POST['todo_files'])) {
				$_files = $_POST['todo_files'];
			}
			$this->PostTodoComment($_ticketId, $_todoInfo['projectid'], $_todoInfo['todoid'], $_POST['todocomment'], $_files);

			return $this->View->TodoCommentPostSuccess();
		} catch (Exception $_e) {
			$_error = $_e->getMessage();
		}

		$this->View->RenderAddCommentsForm($_ticketId, '', $_error);
	}

	/**
	 * retuns json response for ajax query fro todo list
	 *
	 * @author Atul Atri
	 *
	 * @param int $_bcProjectId selected basecamp project id if null is given first project is selected
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function AjaxTodoList($_bcProjectId)
	{
		$_TodolistsSrv = new SWIFT_APITodolists();
		$_error        = '';
		$_todoOpts     = array(array('value' => 0, 'title' => $this->Language->Get('select')));

		try {
			$_response     = $_TodolistsSrv->GetTodolists($_bcProjectId);
			$_todoOptsTemp = $_TodolistsSrv->GetTodolistSelectList($_response);

			if (count($_todoOptsTemp) == 0) {
				$_error = $this->Language->Get('basecamp_notolist');
			} else {
				$_todoOpts = array_merge($_todoOpts, $_todoOptsTemp);
			}
		} catch (Exception $_e) {
			$_error = $_e->getMessage();
		}

		$this->View->AjaxTodoListView($_todoOpts, $_error);

		return true;
	}

	/**
	 * Handle comment posting to basecamp
	 *
	 * @author Atul Atri
	 *
	 * @param int    $_projectId project id
	 * @param int    $_ticketId  ticket id
	 * @param int    $_todoId    todo id
	 * @param string $_comment   Comment'
	 * @param array  $_fileNames files array
	 *
	 * @return mixed basecamp response on Success, "false" otherwise
	 * @throws SWIFT_Exception If comment could not be posted to basecamp
	 */
	private function PostTodoComment($_ticketId, $_projectId, $_todoId, $_comment = "", array $_fileNames = array())
	{
		if (!empty($_comment) || !empty($_fileNames)) {
			$_CommentsSrv       = new SWIFT_APIComments();
			$_filesToBeUploaded = array();

			if (empty($_comment)) {
				$_comment = "";
			}

			if (!empty($_fileNames)) {
				$_SWIFT_TicketObject = SWIFT_Ticket::GetObjectOnID($_ticketId);
				$_attachments        = $_SWIFT_TicketObject->GetAttachmentContainer();

				foreach ($_attachments as $_tmpArr) {

					foreach ($_tmpArr as $_nextAttachment) {
						$_fileId = $_nextAttachment['attachmentid'];

						if (in_array($_fileId, $_fileNames)) {
							$_tmp['name']                 = $_nextAttachment['filename'];
							$_tmp['path']                 = SWIFT_BASEPATH . '/' . SWIFT_BASEDIRECTORY . '/' . SWIFT_FILESDIRECTORY . '/' . $_nextAttachment['storefilename'];
							$_tmp['type']                 = $_nextAttachment['filetype'];
							$_fileId                      = $_nextAttachment['attachmentid'];
							$_filesToBeUploaded[$_fileId] = $_tmp;
						}
					}
				}

				if (count($_filesToBeUploaded) > 0) {
					$_CurlInstance = SWIFT_APIHttp::GetInstance();
					$_CurlInstance->InitMultiCurl();
					$_count = 0;

					foreach ($_filesToBeUploaded as &$_nextAttachment) {
						$this->Load->Library('API:APIAttachments', false, false);
						$_AttachmentsSrv = new SWIFT_APIAttachments();
						$_AttachmentsSrv->Upload($_nextAttachment['path'], $_nextAttachment['type'], true);
						$_nextAttachment['service']        = $_AttachmentsSrv;
						$_nextAttachment['multiCurlIndex'] = $_count;
						$_count++;
					}

					$_responses = $_CurlInstance->ExecuteMultiCurl();
					$_CurlInstance->EndMultiCurl();

					foreach ($_filesToBeUploaded as &$_nextAttachment) {
						$_srv                     = $_nextAttachment['service'];
						$_multiCurlIndex          = $_nextAttachment['multiCurlIndex'];
						$_res                     = $_srv->HandleUploadRes($_responses[$_multiCurlIndex]);
						$_resArr                  = json_decode($_res, true);
						$_nextAttachment['token'] = $_resArr['token'];
					}
				}
			}

			return $_CommentsSrv->PostComment($_projectId, SWIFT_APIComments::SECTION_TODOS, $_todoId, $_comment, false, $_filesToBeUploaded);
		}

		return false;
	}

	/**
	 * Handle todo export form submit
	 *
	 * @author Atul Atri
	 * @return bool "true" on Success, "false" otherwise
	 * @throws SWIFT_Exception
	 */
	public function TodoExportFormSubmit()
	{
		$_pId        = $_POST['todoproject'];
		$_todolistId = $_POST['todolist'];
		$_assigneeId = $_POST['assignee'];
		$_todo       = trim($_POST['todoitem']);
		$_comment    = trim($_POST['todocomment']);
		$_ticketId   = $_POST['todo_ticketid'];
		$_date       = trim($_POST['duedate']);
		$_fileNames  = array();

		//if this ticket id is already linked throw exception
		$_todoId = SWIFT_TodoTicketLink::getTodoInfo($_ticketId);

		if ($_todoId !== false) {
			throw new SWIFT_Exception("TIcket id $_ticketId is already linked to basecamp todo.");
		}

		if (isset($_POST['todo_files'])) {
			$_fileNames = $_POST['todo_files'];
		}

		if ($this->CheckTodoExportForm()) {
			$this->Load->Library('API:APITodos', false, false);
			$this->Load->Library('API:APIComments', false, false);

			$_TodoService = new SWIFT_APITodos();

			try {

				if (!empty($_date)) {
					$_date = date("c", strtotime($_date));
				}

				$_reponse    = $_TodoService->PostTodo($_pId, $_todolistId, $_todo, $_assigneeId, $_date);
				$_reponseArr = json_decode($_reponse, true);
				$_newTodoId  = $_reponseArr['id'];
				$this->PostTodoComment($_ticketId, $_pId, $_newTodoId, $_comment, $_fileNames);

				$this->Load->LoadModel('AuditLog:TicketAuditLog');
				$_SWIFT_TicketObject = SWIFT_Ticket::GetObjectOnID($_ticketId);
				SWIFT_TicketAuditLog::AddToLog($_SWIFT_TicketObject, null, SWIFT_TicketAuditLog::ACTION_UPDATESTATUS, $this->Language->Get('basecamp_audit_todo_posted'), SWIFT_TicketAuditLog::VALUE_NONE, 0, '', 0, '');

				//all well make link in table
				SWIFT_TodoTicketLink::insertTodo($_ticketId, $_newTodoId, $_pId);

				//return success message
				$this->View->TodoPostSuccess($_ticketId, $_pId, $_newTodoId);

				return true;
			} catch (Exception $_e) {
				$_error = $_e->getMessage();
				$_SWIFT = SWIFT::GetInstance();
				SWIFT::Error($_SWIFT->Language->Get('error'), $_error);
			}
		}

		$this->Load->TodoExportForm($_ticketId, $_pId);

		return true;
	}

	/**
	 * Checks todo export form data
	 *
	 * @author Atul Atri
	 * @return bool "true" on Success, "false" otherwise
	 */
	private function CheckTodoExportForm()
	{
		$_SWIFT = SWIFT::GetInstance();

		if (!SWIFT_Session::CheckCSRFHash($_POST['csrfhash'])) {
			SWIFT::Error($_SWIFT->Language->Get('titlecsrfhash'), $_SWIFT->Language->Get('msgcsrfhash'));

			return false;
		}

		$_pId        = $_POST['todoproject'];
		$_todolistId = $_POST['todolist'];
		$_todo       = trim($_POST['todoitem']);
		$_date       = trim($_POST['duedate']);

		if (empty($_pId)) {
			$this->UserInterface->CheckFields('todoproject');
			$this->UserInterface->Error($this->Language->Get('basecamp_error_title'), $this->Language->Get('basecamp_empty_todoproject'));

			return false;
		}

		if (empty($_todolistId)) {
			$this->UserInterface->CheckFields('todlist');
			$this->UserInterface->Error($this->Language->Get('basecamp_error_title'), $this->Language->Get('basecamp_empty_todolist'));

			return false;
		}

		if (empty($_todo)) {
			$this->UserInterface->CheckFields('todoitem');
			$this->UserInterface->Error($this->Language->Get('basecamp_error_title'), $this->Language->Get('basecamp_empty_todo'));

			return false;
		}

		if (!empty($_date) && (date('m/d/Y', strtotime($_date)) != $_date)) {
			$this->UserInterface->CheckFields('duedate');
			$this->UserInterface->Error($this->Language->Get('basecamp_error_title'), $this->Language->Get('basecamp_empty_duedate'));

			return false;
		}

		return true;
	}

	/* *
	 * Delete linked todo task
	 *
	 * @author Atul Atri
	 * @param int  $_ticketId ticekt id
	 */
	public function DeleteTodo($_ticketId)
	{
		$_JsonWriter = new SWIFT_JsonWriter();
		$_todoInfo   = SWIFT_TodoTicketLink::getTodoInfo($_ticketId);

		if ($_todoInfo !== false) {
			$_todoId    = $_todoInfo['todoid'];
			$_projectId = $_todoInfo['projectid'];

			try {
				$_TodoSrv = new SWIFT_APITodos();
				$_TodoSrv->DeleteTodo($_projectId, $_todoId);
			} catch (Exception $_e) {
				echo $_JsonWriter->setResponseCode(502)->toJson();

				return;
			}

			SWIFT_TodoTicketLink::deleteTodo($_todoId);
		}
		echo $_JsonWriter->setResponseCode(200)->toJson();
	}

	/**
	 * View todo
	 *
	 * @author Atul Atri
	 *
	 * @param int $_ticketId TicketId
	 */
	public function ViewTodo($_ticketId)
	{
		$_todoInfo = SWIFT_TodoTicketLink::getTodoInfo($_ticketId);
		$_todoData = null;
		$_error    = "";

		if ($_todoInfo === false) {
			$_error = $this->Language->Get('basecamp_todo_not_linked');
		} else {
			try {
				$_todoId    = $_todoInfo['todoid'];
				$_projectId = $_todoInfo['projectid'];
				$_TodoSrv   = new SWIFT_APITodos();
				$_todoJson  = $_TodoSrv->GetTodo($_projectId, $_todoId);
				$_todoData  = json_decode($_todoJson, true);

				if (isset($_todoData['content'])) {
					$this->Template->Assign('_task', $_todoData['content']);
					$this->Template->Assign('_isCompleted', $_todoData['completed']);

					if (isset($_todoData['due_at'])) {
						$this->Template->Assign('_dueAt', $_todoData['due_at']);
					}

					if (isset($_todoData['assignee']) && isset($_todoData['assignee']['name'])) {
						$this->Template->Assign('_assignee', $_todoData['assignee']['name']);
					}

					if (isset($_todoData['comments'])) {

						$_commentsToSet = $_todoData;

						foreach ($_todoData['comments'] as $key => $_nextItem) {
							$_nextItemToSet                   = $_nextItem;
							$_comment                         = $_nextItem['content'];
							$_commentSanitized                = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $_comment);
							$_commentSanitized                = strip_tags($_commentSanitized);
							$_commentSanitized                = nl2br($_commentSanitized);
							$_nextItemToSet['content']        = $_commentSanitized;
							$_commentsToSet['comments'][$key] = $_nextItemToSet;
						}
						$this->Template->Assign('_comments', $_commentsToSet['comments']);
					}
				} else {
					$_error = $this->Language->Get('basecamp_todo_get_failure');
				}
			} catch (Exception $_e) {
				$_error = $_e->getMessage();
			}//print_r($_todoData);
		}

		$this->Template->Assign('_titleCompleted', $this->Language->Get('basecamp_title_completed'));
		$this->Template->Assign('_tipDueDate', $this->Language->Get('basecamp_tip_due_date'));
		$this->Template->Assign('_tipAssigned', $this->Language->Get('basecamp_tip_assigned'));
		$this->Template->Assign('_errorHeader', $this->Language->Get('error'));
		$this->Template->Assign('_swiftpath', SWIFT::Get('swiftpath'));
		$this->Template->Assign('_errorHeader', $this->Language->Get('error'));
		$this->Template->Assign('_error', $_error);
		$this->Template->Assign('_themePath', SWIFT::Get('themepath'));
		$this->View->RenderTplFile();
	}

}

?>