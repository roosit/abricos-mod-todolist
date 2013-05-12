<?php 
/**
 * @package Abricos
 * @subpackage TodoList
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author Alexander Kuzmin <roosit@abricos.org>
 */

require_once 'dbquery.php';

class TodoGroup extends TodoList_Item {
	public $title;
	
	public function __construct($d){
		parent::__construct($d);
		$this->title = strval($d['tl']);
	}
	
	public function ToAJAX(){
		$ret = parent::ToAJAX();
		$ret->tl = $this->title;
		return $ret;
	}
}

class TodoGroupList extends TodoList_ItemList { }


class TodoPriority extends TodoList_Item {
}

class TodoPriorityList extends TodoList_ItemList { }

class TodoLike extends TodoList_Item {
}

class TodoLikeList extends TodoList_ItemList { }

class TodoItem extends TodoList_Item {
	
	public $title;
	public $groupid;
	public $priorityid;
	public $likeid;
	
	public function __construct($d){
		parent::__construct($d);
		
		$this->title = strval($d['tl']);
		$this->groupid = intval($d['gid']);
		$this->priorityid = intval($d['prtid']);
		$this->likeid = intval($d['lkid']);
	}
	
	public function ToAJAX(){
		$ret = parent::ToAJAX();
		$ret->tl = $this->title;
		$ret->gid = $this->groupid;
		$ret->prtid = $this->priorityid;
		$ret->lkid = $this->likeid;
		
		return $ret;
	}
}

class TodoList extends TodoList_ItemList {
}

class TodoListConfig {

	/**
	 * @var TodoListConfig
	 */
	public static $instance;

	public function __construct($cfg){
		TodoListConfig::$instance = $this;

		if (empty($cfg)){ $cfg = array(); }

		/*
		 if (isset($cfg['subscribeSendLimit'])){
		$this->subscribeSendLimit = intval($cfg['subscribeSendLimit']);
		}
		/**/
	}
}


class TodoList_Item {
	public $id;

	public function __construct($d){
		$this->id = intval($d['id']);
	}

	public function ToAJAX(){
		$ret = new stdClass();
		$ret->id = $this->id;
		return $ret;
	}
}

class TodoList_ItemList {

	protected $_list = array();
	protected $_map = array();
	protected $_ids = array();

	protected $isCheckDouble = false;

	public function __construct(){
		$this->_list = array();
		$this->_map = array();
	}

	public function Add(TodoList_Item $item = null){
		if (empty($item)){
			return;
		}

		if ($this->isCheckDouble){
			$checkItem = $this->Get($item->id);
			if (!empty($checkItem)){
				return;
			}
		}

		$index = count($this->_list);
		$this->_list[$index] = $item;
		$this->_map[$item->id] = $index;

		array_push($this->_ids, $item->id);
	}

	/**
	 * Массив идентификаторов
	 */
	public function Ids(){
		return $this->_ids;
	}

	public function Count(){
		return count($this->_list);
	}

	/**
	 * @param integer $index
	 * @return TodoList_Item
	 */
	public function GetByIndex($index){
		return $this->_list[$index];
	}

	/**
	 * @param integer $id
	 * @return TodoList_Item
	 */
	public function Get($id){
		$index = $this->_map[$id];
		return $this->_list[$index];
	}

	public function ToAJAX(){
		$list = array();
		$count = $this->Count();
		for ($i=0; $i<$count; $i++){
			array_push($list, $this->GetByIndex($i)->ToAJAX());
		}

		$ret = new stdClass();
		$ret->list = $list;

		return $ret;
	}
}
?>