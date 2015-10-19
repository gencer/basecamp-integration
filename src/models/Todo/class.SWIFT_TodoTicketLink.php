<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/basecamp-integration
 */

/**
 * Todo model
 *
 * @author Varun Shoor
 */
class SWIFT_TodoTicketLink extends SWIFT_Model
{
	const TABLE_NAME = 'basecamptodoticketlinks';
	const PRIMARY_KEY = 'basecamptodoticketlinkid';

	const TABLE_STRUCTURE = "basecamptodoticketlinkid I PRIMARY AUTO NOTNULL,
								ticketid I DEFAULT '0' NOTNULL,
								todoid I DEFAULT '0' NOTNULL,
								projectid I DEFAULT '0' NOTNULL";

	const INDEX_1 = 'ticketid';
	const INDEX_2 = 'todoid';

	/**
	 * Check if ticket id is already linked to basecamp task
	 *
	 * @param int $_ticketId ticket id
	 *
	 * @return int todo item id on basecamp or false
	 */
	public static function getTodoInfo($_ticketId)
	{
		$_query = "SELECT * FROM " . TABLE_PREFIX . self::TABLE_NAME . " WHERE  ticketid = $_ticketId";

		$_Swift  = SWIFT::GetInstance();
		$_result = $_Swift->Database->QueryFetch($_query);

		if ($_result === false) {
			return false;
		}

		return $_result;
	}

	/**
	 * Delete todo record
	 *
	 * @param int $_todoId todo id
	 *
	 * @return mixed result of SWIFT_Database::Execute
	 */
	public static function deleteTodo($_todoId)
	{
		$_query = "DELETE  FROM " . TABLE_PREFIX . self::TABLE_NAME . " WHERE  todoid = $_todoId";
		$_Swift = SWIFT::GetInstance();

		return $_Swift->Database->Execute($_query);
	}

	/**
	 * Insert a todo record
	 *
	 * @param int $_ticketId ticket id
	 * @param int $_todoId   todo id
	 * @param int $_pId      basecamp project id
	 *
	 * @return mixed result of SWIFT_Database::Execute
	 */
	public static function insertTodo($_ticketId, $_todoId, $_pId)
	{
		$_Swift                  = SWIFT::GetInstance();
		$_dataArray              = array();
		$_dataArray['ticketid']  = intval($_ticketId);
		$_dataArray['todoid']    = intval($_todoId);
		$_dataArray['projectid'] = intval($_pId);

		return $_Swift->Database->AutoExecute(TABLE_PREFIX . self::TABLE_NAME, $_dataArray);
	}
}