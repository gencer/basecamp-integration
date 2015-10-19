<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * A base view class to do all kind of rendering
 *
 * @author Atul Atri
 */
class SWIFT_GeneralViewBase extends SWIFT_View
{

	//assigned variables
	protected $_vars = array();

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
	 * @author Atul Atri
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * assign a varibale to view
	 *
	 * @author Atul Atri
	 *
	 * @param string $_name  name of variable
	 * @param string $_value value of variable
	 *
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Assign($_name, $_value)
	{
		$this->_vars[$_name] = $_value;
	}

	/**
	 * Get a variable assigned to this view
	 *
	 * @author Atul Atri
	 *
	 * @param string $_name varibale name
	 *
	 * @return mixed $_name is set or empty string
	 */
	public function Get($_name)
	{
		if (isset($this->_vars[$_name])) {
			return $this->_vars[$_name];
		}

		return "";
	}

	/**
	 * Render a tpl file
	 *
	 * @author Atul Atri
	 *
	 * @param int    $_menuId          menu id
	 * @param int    $_navId           navigation id
	 * @param string $_documentTitle   document title
	 * @param string $_customNavHtml   custom nav html
	 * @param bool   $_includeTemplate include outer template
	 * @param bool   $_scritpPath      script path to be included
	 *
	 * @return void
	 */
	public function RenderTplFile($_menuId = 1, $_navId = 1, $_documentTitle = '', $_customNavHtml = '', $_includeTemplate = true, $_scritpPath = '')
	{
		if (!$_scritpPath) {
			$_appDirectory  = $this->Router->GetApp()->GetDirectory();
			$_action        = Clean($this->Router->GetAction());
			$_interfaceName = strtolower(Clean($this->Interface->GetName()));
			$_scritpPath    = $_appDirectory . '/themes/' . $_interfaceName . '/templates/' . $_action . '.tpl';
		}

		if ($_includeTemplate) {
			$this->UserInterface->Header($_documentTitle, $_menuId, $_navId, $_customNavHtml);
		}

		$this->Template->Render('   ', SWIFT_TemplateEngine::TYPE_FILE, $_scritpPath);

		if ($_includeTemplate) {
			$this->UserInterface->Footer();
		}
	}

}

?>