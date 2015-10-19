/**
 * Common functions for basecamp module, Use as a singleton
 *
 * @author Atul Atri<atul.atri@kayko.com>
 * @class
 * @extends SWIFT_BaseClass
 */
SWIFT.Library.BasecampAdmin = SWIFT.Base.extend({
	//save current window location hash
	hash:             window.location.hash,
	//intval to check window location hash
	checkHashTimeOut: 400,

	/**
	 * Check location hash
	 *
	 *  @return SWIFT.Library.BasecampAdmin
	 */
	CheckHash: function() {
		if (window.location.hash != this.hash) {
			this.hash = window.location.hash;
			this.ProcessHash(this.hash);
		}

		if ($('#basecampcode')) {
			setTimeout("SWIFT.Basecamp.AdminObject.CheckHash()", this.checkHashTimeOut);
		}

		return this;
	},

	/**
	 * Process chaned location hash
	 *
	 * @param {String} hash location hash
	 *
	 * @return SWIFT.Library.BasecampAdmin
	 */
	ProcessHash: function(hash) {
		var hashArr = hash.split('=');

		if (hashArr.length == 2 && hashArr[0] == '#bs_auth_code') {
			$('#basecampcode').val(hashArr[1]);
			TabLoading('basecamp_manager', 'basecamp_tab_auth');
			$('#basecamp_managerform').submit();
			$('#auth_txt').hide();
			$('#auth_wait').show();
		}

		return this;
	},

	/**
	 * Open authorization window
	 *
	 * @param {String} authLink url for new window
	 *
	 * @return SWIFT.Library.BasecampAdmin
	 */
	OpenAuthWindow: function(authLink) {
		var newWIndow = window.open(authLink, 'resizable=1, width=350, height=250');
		newWIndow.focus();

		return this;
	},

	/**
	 * Open Integartion dialog
	 *
	 * @param {String} windowTitle window title
	 *
	 * @return SWIFT.Library.BasecampAdmin
	 */
	IntegrateNowDialog: function(windowTitle) {
		var _windowTitle = '<img src="' + themepath + 'images/icon_window.gif" align="absmiddle" border="0" /> ' + windowTitle;

		$('#bc_integrate_now').dialog({
			height:    122,
			width:     500,
			minHeight: 122,
			minWidth:  500,
			modal:     true,
			draggable: true,
			resizable: true,
			close:     function(event, ui) {
				$(this).dialog('destroy').remove();
			},
			title:     _windowTitle,
			open:      function() {
				$('.ui-dialog').each(function() {
					$(this).css('overflow', 'visible');
				});
				$('.ui-dialog-container').each(function() {
					$(this).css('overflow', 'hidden');
				})
			}
		});

		return SWIFT.Basecamp.AdminObject;
	},

	/**
	 * Handle basecamp project selection
	 *
	 * @return SWIFT.Library.BasecampAdmin
	 */
	TodoProjectSelect: function() {
		SWIFT.Basecamp.AdminObject.AddBcLoader('selecttodlistloader');
		_url = _baseName + '/basecamp/TodoManager/AjaxTodoList/' + this.value;

		$.getJSON(_url, null, function(response) {

			if (response) {

				$.each(response, function(key, val) {

					if (key == 'error') {

						if (val != "") {

							if ($('#bc_todo_list_error')) {
								$('#bc_todo_list_error').remove();
								//remove default error container
								var dialogerrorcontainer = $('#window_exportbasecamp').children('.dialogerrorcontainer');

								if (dialogerrorcontainer) {
									$(dialogerrorcontainer).parent().remove();
								}
							}
							$('#window_exportbasecamp').children(":first").after(val);
						} else {
							$('#bc_todo_list_error').remove();
						}
					}

					if (key == 'todoOptions') {
						$('.selecttodlistloader').remove();
						var todoselectlist = $("#selecttodolist");
						todoselectlist.empty();
						$.each(val, function(k, optArr) {
							optKey = optArr['value'];
							optValue = optArr['title'];
							todoselectlist.append($("<option></option>")
								.attr("value", optKey).text(optValue));
						});
					}
				});

			}
		});

		return SWIFT.Basecamp.AdminObject;
	},

	/**
	 * Add loader image basecamp todo selcect box
	 *
	 * @param {String} classname style class name
	 *
	 * @return SWIFT.Library.BasecampAdmin
	 */
	AddBcLoader: function(classname) {

		$("#selecttodolist").after('<div style="display:inline-block; margin-left:5px;" class="' + classname + '">&nbsp;</div>');
		$("." + classname).html('<img src="' + themepath + '/images/loadingcircle.gif"/>');
		$("#selecttodolist").html('<option>' + "_[bc_wait]" + '</option>');

		return SWIFT.Basecamp.AdminObject;
	},

	/**
	 * Restore todo export form once it is destroyed after form submission
	 *
	 *  @return SWIFT.Library.BasecampAdmin
	 */
	RestoreTodoExportForm: function() {
		var thisobj = SWIFT.Basecamp.AdminObject;
		$("#duedate").datepicker('destroy');
		var _response = $('#todoExportFormResHolder').html();

		var swiftJsonObj = new SWIFT.Library.Json();
		var swiftJson = swiftJsonObj.Parse(_response);

		//if this is a success show flash message
		if (swiftJson && swiftJson.GetSuccessCode() == 200) {
			var resData = swiftJson.GetData();
			var task = resData.task;

			if (window.$UIObject) {
				var flashMessage = "_[basecamp_todo_posted]";
				if (task == 'todo_comment') {
					flashMessage = "_[basecamp_todo_comment_posted_success]";
				}
				SWIFT_Notification.Info(flashMessage);
			}

			if (task == 'todo') {
				//change menu to post comment
				thisobj.CreateBasecampMenu(resData.ticketId, resData.todoId, resData.url);
			}

			return thisobj;
		}

		//do not use 'this' in this function this is being called as a callback function from many places
		//first destroy datepicker
		$('#todoExportFormResHolder').html("");

		var _divElement = UICreateWindowStart('exportbasecamp');
		$(_divElement).html(_response);

		UICreateWindowEnd(_divElement, 'exportbasecamp', "_[basecamptodo]", 800, 700, "");
		bindFormSubmit('View_TodoManagerform', 'todoExportFormResHolder', SWIFT.Basecamp.AdminObject.RestoreTodoExportForm);
		//again add date picker
		$('#duedate').datepicker(window.datePickerDefaults);

		return SWIFT.Basecamp.AdminObject;
	},

	/**
	 * Add events to page basecamp todo page
	 *
	 *  @return SWIFT.Library.BasecampAdmin
	 */
	AddtodoEvents: function() {
		$("#selecttodoproject").die();
		$("#selecttodoproject").live('change', SWIFT.Basecamp.AdminObject.TodoProjectSelect);
		//Add a hidden div in dom it will contain data returned by TodoExportForm
		$('#TodoExportFormResHolder').remove();
		$('body').append('<div style="display:none" id="todoExportFormResHolder"></div>');

		return SWIFT.Basecamp.AdminObject;
	},

	/**
	 * Create basecamp menu
	 *
	 * @param {Integer} [ticketId= Null] ticket id
	 * @param {Integer} [todoId= Null] todo id
	 * @param {String} [basecampTodoUrl= Null] url to todo link on basecamp
	 *
	 *  @return SWIFT.Library.BasecampAdmin
	 */
	CreateBasecampMenu: function(ticketId, todoId, basecampTodoUrl) {
		var thisObj = SWIFT.Basecamp.AdminObject;
		var loadingwindowMessage = "_[loadingwindow]";

		if (!todoId) {
			thisObj.OpenTodoForm(ticketId);
		} else {
			if ($('#basecamp_menu_wrapper')) {
				$('#basecamp_menu_wrapper').remove();
			}
			var template = _.template(SWIFT.Template.Get('basecampMenu'));
			var basecampMenuHtml = template({basecampTodoUrl: basecampTodoUrl});
			var newDiv = $("<div>").attr({"id": "basecamp_menu_wrapper"}).html(basecampMenuHtml);
			$('body').append(newDiv)

			$('#basecamp').off('click.openTodoForm');
			thisObj.AddDropImage('#basecamp a');
			var openDropDown = function(event) {
				UIDropDown('basecamp_menu', event, 'basecamp', 'tabtoolbartable');
			};
			$('#basecamp').on('click.openDropDown', openDropDown);

			$('#basecamp_add_comment').off('click.addComment');
			var addCommentMessage = "_[basecamp_add_comment]";
			$('#basecamp_add_comment').on('click.addComment', function() {
				UICreateWindow(_baseName + "/basecamp/TodoManager/AddCommentForm/" + ticketId, 'basecamp_add_comment', addCommentMessage, loadingwindowMessage, 800, 255, true, window);
			});

			$('#basecamp_delete_todo').off('click.deleteTodo');
			$('#basecamp_delete_todo').on('click.deleteTodo', function() {
				var x = confirm("_[basecamp_todo_delete_confirm]");
				if (!x) {
					return;
				}
				//change drop image to load image
				var dropImageUrl = $('#basecamp_dropImage').attr("src");
				$('#basecamp_dropImage').attr("src", _swiftPath + "__swift/themes/__cp/images/loadingcircle.gif");
				$('#basecamp').off('click.openDropDown');

				var deleteUrl = _baseName + "/basecamp/TodoManager/DeleteTodo/" + ticketId;
				var deleteFailFunc = function() {
					SWIFT_Notification.Error("_[basecamp_todo_deleted_failure]");
					$('#basecamp_dropImage').attr("src", dropImageUrl);
					$('#basecamp').on('click.openDropDown', openDropDown);
				};
				$.get(deleteUrl, function(data) {
					var swiftJsonObj = new SWIFT.Library.Json();
					var swiftJson = swiftJsonObj.Parse(data);
					if (swiftJson.GetSuccessCode() == 200) {//delete suceess
						SWIFT_Notification.Info("_[basecamp_todo_deleted_success]");
						$('#basecamp_dropImage').remove();
						thisObj.OpenTodoForm(ticketId);
					} else {
						deleteFailFunc();
					}
				}).error(deleteFailFunc);
			});

			$('#basecamp_view_todo').off('click.viewTodo');
			$('#basecamp_view_todo').on('click.viewTodo', function() {
				UICreateWindow(_baseName + "/basecamp/TodoManager/ViewTodo/" + ticketId, 'basecamp_view_todo', "_[basecamp_todo_view]", "_[loadingwindow]", 800, 700, true, window);
			});

		}
	},

	/**
	 * Add drop image to menu
	 *
	 * @param {String} selector selector
	 */
	AddDropImage: function(selector) {
		var dropImage = $("<img id='basecamp_dropImage'>").attr('src', _swiftPath + "__swift/themes/__cp/images/menudropgray.gif").attr('border', 0).attr('align', "absmiddle");
		$(selector).append(dropImage);
	},

	/**
	 * Open todo form event
	 *
	 * @param {Integer} ticketId ticket id
	 */
	OpenTodoForm: function(ticketId) {
		var thisObj = SWIFT.Basecamp.AdminObject;
		$('#basecamp').off('click.openDropDown');
		$('#basecamp').off('click.openTodoForm');
		$('#basecamp').on('click.openTodoForm', function() {
			UICreateWindow(_baseName + "/basecamp/TodoManager/TodoExportForm/" + ticketId, 'exportbasecamp', "_[basecamptodo]", "_[loadingwindow]", 800, 700, true, window);
		});
	}

});

/**
 *  Basecamp container
 */
SWIFT.Basecamp = {};
/**
 * Basecamp admin object, Use as a singleton
 */
SWIFT.Basecamp.AdminObject = SWIFT.Library.BasecampAdmin.create();