<?php

/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */
class View_Manager extends SWIFT_GeneralViewBase
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
	 * view for manage form action
	 *
	 * @author Atul Atri
	 *
	 * @param int $_menuId menu id
	 * @param int $_navId  navigation id
	 *
	 * @return boolean true
	 */
	public function ManageForm($_menuId = 1, $_navId = 1)
	{
		$this->UserInterface->Start('basecamp_manager', '/basecamp/Manager/CodeSubmit', SWIFT_UserInterface::MODE_EDIT);
		$_GeneralTabObject = $this->UserInterface->AddTab($this->Language->Get('basecamp_tab_general'), 'icon_form.gif', 'basecamp_tab_auth', true);
		$this->UserInterface->Toolbar->AddButton($this->Language->Get($this->Get('_buttonTxt')), 'icon_check.gif', "$('#basecampcode').val('');javascript:this.blur(); TabLoading('basecamp_manager', 'basecamp_tab_auth'); $('#basecamp_managerform').submit();", SWIFT_UserInterfaceToolbar::LINK_JAVASCRIPT, 'basecamp_managerform_submit');

		$_GeneralTabObject->Text('bc_app_name', $this->Language->Get('bc_app_name'), '', $this->Get('_appName'), 'text', 60);
		$_GeneralTabObject->Text('bc_app_email', $this->Language->Get('bc_email'), $this->Language->Get('d_bc_email'), $this->Get('_appEmail'), 'text', 60);
		$_GeneralTabObject->Text('bc_app_id', $this->Language->Get('bc_app_id'), '', $this->Get('_appId'), 'text', 60);
		$_GeneralTabObject->Text('bc_app_secret', $this->Language->Get('bc_app_secret'), '', $this->Get('_appSecret'), 'text', 60);

		$this->UserInterface->Hidden('basecampcode', '');

		ob_start();
		$this->RenderTplFile($_menuId, $_navId, $this->Language->Get('manage_basecamp'));
		$_html = ob_get_contents();
		ob_end_clean();

		$_GeneralTabObject->AppendHTML($_html);

		$this->UserInterface->End();

		return true;
	}
}

?>