<?php
namespace Bitrix\Im\Replica;

class RelationHandler extends \Bitrix\Replica\Client\BaseHandler
{
	protected $tableName = "b_im_relation";
	protected $moduleId = "im";
	protected $className = "\\Bitrix\\Im\\RelationTable";
	protected $primary = array(
		"ID" => "auto_increment",
	);
	protected $predicates = array(
		"CHAT_ID" => "b_im_chat.ID",
		"USER_ID" => "b_user.ID",
	);
	protected $translation = array(
		"ID" => "b_im_relation.ID",
		"CHAT_ID" => "b_im_chat.ID",
		"USER_ID" => "b_user.ID",
		"START_ID" => "b_im_message.ID",
		"LAST_ID" => "b_im_message.ID",
		"LAST_SEND_ID" => "b_im_message.ID",
	);
	protected $fields = array(
		"LAST_READ" => "datetime",
	);

	/**
	 * Method will be invoked after new database record inserted.
	 *
	 * @param array $newRecord All fields of inserted record.
	 *
	 * @return void
	 */
	public function afterInsertTrigger(array $newRecord)
	{
	}
}
