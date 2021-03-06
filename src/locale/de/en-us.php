<?php
$__LANG = array(
	'basecamp'                             => 'Basecamp',
	'error'                                => 'Error',
	'success'                              => 'Success',
	'warning'                              => 'Warning',
	'insert'                               => 'Insert',
	'update'                               => 'Update',
	'delete'                               => 'Delete',

	//actioon titles
	'BC_ACTION_POST_MSG'                   => 'Post As A Meesgae',

	//errors
	'BC_ERR_INVALID_SETTINGS'              => 'Helpdesk could not contact to basecamp. Please review your basecamp settings and check your internet connection.',
	'BC_PROJECT_LIST_ERR'                  => 'Failed to retrive list of projects. ',
	'BC_ERR_MSG_POST_FAILED'               => 'Failed to post a message to basecamp. ',
	'BC_PEOPLE_LIST_ERR'                   => 'Failed to retrive list of people. Error: ',
	'BC_TODO_LIST_ERR'                     => 'Failed to retrive list of todos in selected project. ',
	'BC_TODO_POST_ERR'                     => 'Failed to to post todo item. ',
	'BC_TODO_COMMENT_ERR'                  => 'Failed to to post comment. ',
	'BC_UPLOAD_ERR'                        => 'File upload failed. ',
	'BC_GET_TOKEN_ERROR'                   => 'Could not complete authorization process',
	'BC_AUTHORIZATION_ERR'                 => 'Failed to get authorized account information.',

	//staff ticket panel
	'basecamp_button'                      => 'Basecamp ',
	'basecamptodo'                         => 'New Basecamp Todo',

	//staff todo export form view
	'basecamp_todoexportform'              => 'Export ticket to Basecamp todo',
	'basecamp_todoproject'                 => 'Basecamp project',
	'd_basecamp_todoproject'               => 'Select a project on basecamp',
	'export'                               => 'Export',
	'basecamp_tab_export'                  => 'General',
	'basecamp_todlist'                     => 'Todo list',
	'd_basecamp_todlist'                   => 'New todo will be added in selected todo list.',
	'nobcproject'                          => 'You do not have any project on basecamp. First create some projects on basecamp.',
	'basecamp_notintegrated'               => 'Your helpdesk is not integrated with basecamp yet. Ask your administrator to enable this integration.',
	'select'                               => 'Select',
	'basecamp_assignee'                    => 'Assignee',
	'd_basecamp_assignee'                  => '(optional)',
	'basecamp_notolist'                    => 'No todo list found in selected project. Please change selected project or create some todo list on basecamp.',
	'bc_wait'                              => 'Please wait...',
	'basecamp_todoitem_name'               => 'Todo Item',
	'bc_user'                              => 'User',
	'basecamp_todocomment'                 => 'Comment',
	'd_basecamp_todocomment'               => 'Your first comment on this new todo item. (optional)',
	'bc_wrote_on'                          => 'wrote at',
	'basecamp_error_title'                 => 'Error',
	'basecamp_empty_todoproject'           => 'Please select a basecamp project.',
	'basecamp_empty_todolist'              => 'Please select a todo list.',
	'basecamp_empty_todo'                  => 'Todo Item can not be empty.',
	'basecamp_empty_duedate'               => 'Please provide a valid date',
	'basecamp_todo_posted'                 => 'New todo has been posted on Basecamp.',
	'basecamp_audit_todo_posted'           => 'New todo posted on Basecamp.',
	'basecamp_duedate'                     => 'Due Date',
	'd_basecamp_duedate'                   => '(optional)',
	'basecamp_todo_files'                  => 'Attachments',
	'd_basecamp_todo_files'                => 'Upload attachments to basecamp. Files upload may take time. (optional)',

	//manager controller
	'manage_basecamp'                      => 'Manage Basecamp',
	'basecamp_already_authorised_txt'      => 'Registered Basecamp application is already authorized to use your basecamp account. If you have revoked permissions from our Basecamp application or Helpdesk is unable to connect to Basecamp, you can always re-authorize our Basecamp application to fix such problems.',
	'basecamp_authorise_txt'               => 'To authorise registered Basecamp application to use your Basecamp account ',
	//'Authorization code is needed to authorize our Basecamp application to use your account. To get authorization code ',
	'basecamp_click_here_lnk'              => 'Click Here',
	'basecamp_enter_auth_token'            => 'Enter authentication code here',
	'basecamp_tab_general'                 => 'General',
	'basecamp_please_wait_auth'            => 'Please wait while our Basecamp application is getting authorized to use your Basecamp account....',
	'basecamp_get_auth_token_error'        => 'Could not complete authorization process',
	'basecamp_get_token_success'           => 'Basecamp Application is successfully authorized to use your Basecamp account.',
	'basecamp_multiple_accounts'           => 'You have multiple Basecamp account. Please goto Basecamp settings and provide your Basecamp account id that you want to integrate with helpdesk.',
	'basecamp_no_account'                  => 'Although Basecamp Application is successfully authorized, but you do not have any basecamp account. Please create some basecamp account and try again.',
	'basecamp_update_app_txt'              => 'If your Basecamp application information has been changed, please update below. Make sure you entered redirect URL as %s while registering your basecamp application.',
	'basecamp_new_app_txt'                 => 'You have to register an application on Basecamp.  %s to register a new Basecamp application. Make sure you enter redirect URL as %s while creating your basecamp application.',

	'bc_app_name'                          => 'Basecamp application name',
	'bc_email'                             => 'Your email',
	'd_bc_email'                           => '..as you registered with basecamp application.',
	'bc_app_id'                            => 'Basecamp Application Id',
	'bc_app_secret'                        => 'Basecamp Application Secret',
	'bc_app_redirect_url'                  => 'Basecamp Application Redirect URL',
	'basecamp_save_button'                 => 'Save',
	'basecamp_update_button'               => 'Update',

	//manager controller new application form
	'empty_bc_app_name'                    => 'Please provide your Basecamp application name.',
	'empty_bc_app_email'                   => 'Please provide email address that was used to register Basecamp application.',
	'invalid_bc_app_email'                 => 'Please provide a valid email address.',
	'empty_bc_app_id'                      => 'Please provide your Basecamp application ID.',
	'empty_bc_app_secret'                  => 'Please provide your Basecamp application Secret.',
	'basecamp_new_app_saved_success'       => 'New Basecamp application information is saved.',
	'basecamp_js_new_app_saved'            => 'Your new Basecamp application information is saved. Would you like to intgrate Helpdesk with new Basecamp application?',
	'yes'                                  => 'Yes',
	'cancel'                               => 'Cancel',
	'basecamp_wrong_app_info'              => 'You provided wrong application information. Please check and submit again.',

	'basecamp_click_here_todo_task'        => 'Click Here to check linked todo task on Basecamp.',
	'basecamp_todo_add_comment'            => 'Add Comment',

	//add todo comments
	'basecamp_ticket_not_linked'           => 'Covert this ticket to todo task first. ',
	'basecamp_add_comment'                 => 'Add Comment',
	'add'                                  => 'Add',
	'basecamp_empty_comment'               => 'Comment can not be empty.',
	'basecamp_todo_not_linked'             => 'This ticket is not linked to any basecamp task',
	'basecamp_todo_comment_posted_success' => 'A new comment has been added to linked todo task.',
	'basecamp_todo_delete'                 => 'Delete Todo',
	'basecamp_todo_delete_confirm'         => 'Are you sure to delete linked basecamp todo task?',
	'basecamp_todo_deleted_success'        => 'Basecamp todo task linked to this ticket is deleted.',
	'basecamp_todo_deleted_failure'        => 'We could not delete todo task linked to this ticket.',
	'basecamp_todo_get_failure'            => 'We could not get updates for todo task linked to this ticket.',
	'basecamp_todo_view'                   => 'View Todo',
	'basecamp_tip_due_date'                => 'Due Date',
	'basecamp_tip_assigned'                => 'Assignee',
	'basecamp_title_completed'             => 'Completed'
);