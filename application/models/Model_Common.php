<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_Common extends MY_Model
{
	public bool    $isAutoincrement = false;
	public bool    $isDelYn = false;
	public bool    $isUseYn = false;
	public bool    $isCreatedDt = false;
	public bool    $isCreatedId = false;
	public bool    $isUpdatedDt = false;

	function __construct()
	{
		parent::__construct();
	}

	function getList($select = [], $where = [], $like = [], $limit = [], $orderBy = [])
	{
		if(count($where) > 0) $this->where($this->table, $where, $like);
		if(count($orderBy) > 0) {
			$this->orderBy($orderBy);
		}else{
			if($this->identifier && $this->isAutoincrement === true) {
				$this->orderBy(["$this->table.$this->identifier" => 'DESC']);
			}else if($this->isCreatedDt) {
				$this->orderBy(["$this->table.".CREATED_DT_COLUMN_NAME => 'DESC']);
			}
		}
		if(empty($select)) $this->db->select($this->getSelectList());
		if($this->isDelYn) $this->db->where($this->table.".".DEL_YN_COLUMN_NAME, 'N');
		if($this->isUseYn && !array_key_exists(USE_YN_COLUMN_NAME, $where)) $this->db->where($this->table.".".USE_YN_COLUMN_NAME, 'Y');

		return parent::getListPDO($this->table, $select);
	}

	function getData($select = [], $where = [], $like = [])
	{
		if(count($where) > 0) $this->where($this->table, $where, $like);
		if(empty($select)) $this->db->select($this->getSelectList());
		if($this->isDelYn) $this->db->where($this->table.".".DEL_YN_COLUMN_NAME, 'N');
		if($this->isUseYn && !array_key_exists(USE_YN_COLUMN_NAME, $where)) $this->db->where($this->table.".".USE_YN_COLUMN_NAME, 'Y');

		return parent::getDataPDO($this->table, $select);
	}

	function getCnt($where = [], $like = [])
	{
		if(count($where) > 0) $this->where($this->table, $where, $like);
		if($this->isDelYn) $this->db->where($this->table.".".DEL_YN_COLUMN_NAME, 'N');
		if($this->isUseYn && !array_key_exists(USE_YN_COLUMN_NAME, $where)) $this->db->where($this->table.".".USE_YN_COLUMN_NAME, 'Y');

		return parent::getCntPDO($this->table);
	}

	function addList($set)
	{
		$set = $this->getValidSetList($set);

		return parent::addListPDO($this->table, $set);
	}

	function addData($set, $bool = false)
	{
		if($this->isCreatedId) $this->db->set(CREATED_ID_COLUMN_NAME, is_empty($set, CREATED_ID_COLUMN_NAME) ? 1 : $set[CREATED_ID_COLUMN_NAME]);
		if(!$this->isAutoincrement) $bool = false;

		$set = $this->getValidSetData($set);

		return parent::addDataPDO($this->table, $set, $bool);
	}

	function modData($set, $where, $bool = false)
	{
		if($this->isUpdatedDt) {
			$this->db->set(UPDATED_DT_COLUMN_NAME, 'now()', false);
			if($this->isCreatedId) $this->db->set(UPDATED_ID_COLUMN_NAME, is_empty($set, UPDATED_ID_COLUMN_NAME) ? 1 : $set[UPDATED_ID_COLUMN_NAME]);
		}
		if(!$this->isAutoincrement) $bool = false;

		$set = $this->getValidSetData($set);

		return parent::modDataPDO($this->table, $set, $where, $bool);
	}

	function modNumb($field, $count, $where, $bool = false)
	{
		if ($count > 0) {
			$this->db->set($field, $field . '+' . $count, false);
		} else {
			$this->db->set($field, $field . $count, false);
		}

		return $this->modDataPDO($this->table, [], $where, $bool);
	}

	function delData($where, $bool = false, $isSoftDelete = true, $set = [])
	{
		if($this->isDelYn) {
			if($isSoftDelete) {
				$this->db->set(DEL_YN_COLUMN_NAME, 'Y')->set(UPDATED_DT_COLUMN_NAME, 'now()', false);
				if($this->isCreatedId) $this->db->set(UPDATED_ID_COLUMN_NAME, is_empty($set, UPDATED_ID_COLUMN_NAME) ? 1 : $set[UPDATED_ID_COLUMN_NAME]);
				return parent::modDataPDO($this->table, [], $where, $bool);
			}else{
				return parent::delDataPDO($this->table, $where, $bool);
			}
		}else{
			return parent::delDataPDO($this->table, $where, $bool);
		}
	}

	function checkDuplicate($where, $isIncludeDeleted = true)
	{
		if(empty($where)) throw new Exception("checkDuplicate : where parameter empty");

		if(count($this->uniqueKeyList) > 0) {
			$this->where($this->table, $where);
			if($this->isDelYn && $isIncludeDeleted === false) $this->db->where($this->table.".".DEL_YN_COLUMN_NAME, 'N');
			if($this->isUseYn && $isIncludeDeleted === false) $this->db->where($this->table.".".USE_YN_COLUMN_NAME, 'N');
			return parent::getCntPDO($this->table);
		}else{
			return false;
		}
	}

	function reorder($where, $sortField, $sortItem = null, $newIndex = 0)
	{
		$columnList = [...$this->notNullList, ...$this->nullList];
		if(!in_array($sortField, $columnList)) return false;
		if(!$this->identifier && !count($this->primaryKeyList)) return false;

		if($sortItem) {
			foreach ($sortItem as $key=>$val) $this->db->where("$key <> $val");

			$list = $this->getList([], $where, [], [], [$sortField => 'ASC']);
			$idx = 1;
			$matched = false;
			foreach ($list as $item) {
				if((int)$item->{$sortField} >= (int)$newIndex && !$matched) {
					$matched = true;
					$idx++;
				}

				$itemWhere = [];
				if($this->identifier) {
					$itemWhere = [$this->identifier => $item->{$this->identifier}];
				}else{
					foreach ($this->primaryKeyList as $key) $itemWhere[$key] = $item->{$key};
				}

				$this->modData([$sortField => $idx], $itemWhere);
				$idx++;
			}

			$this->modData([$sortField => $newIndex], $sortItem);
		}else{
			$list = $this->getList([], $where, [], [], [$sortField => 'ASC']);
			foreach ($list as $i=>$item) {
				$itemWhere = [];
				if($this->identifier) {
					$itemWhere = [$this->identifier => $item->{$this->identifier}];
				}else{
					foreach ($this->primaryKeyList as $key) $itemWhere[$key] = $item->{$key};
				}

				$this->modData([$sortField => $i+1], $itemWhere);
			}
		}
	}

	protected function getSelectList(): array
	{
		$columnList = [...$this->notNullList, ...$this->nullList];
		if($this->isDelYn) $columnList[] = DEL_YN_COLUMN_NAME;
		if($this->isUseYn) $columnList[] = USE_YN_COLUMN_NAME;
		if($this->isCreatedDt) {
			$columnList[] = CREATED_DT_COLUMN_NAME;
			if($this->isCreatedId) $columnList[] = CREATED_ID_COLUMN_NAME;
		}
		if($this->isUpdatedDt) {
			$columnList[] = UPDATED_DT_COLUMN_NAME;
			if($this->isCreatedId) $columnList[] = UPDATED_ID_COLUMN_NAME;
		}
		foreach ($columnList as $idx=>$column) $columnList[$idx] = "$this->table.$column";
		return $columnList;
	}

	protected function getValidSetList($set): array
	{
		$columnList = [...$this->notNullList, ...$this->nullList];
		return array_map(function($item) use ($columnList) {
			if(!is_array($item)) $item = (array)$item;
			return array_filter($item, function($key) use ($columnList) {
				return in_array($key, $columnList);
			}, ARRAY_FILTER_USE_KEY);
		}, $set);
	}

	protected function getValidSetData($set): array
	{
		$columnList = [...$this->notNullList, ...$this->nullList];
		return array_filter($set, function($key) use ($columnList) {
			return in_array($key, $columnList);
		}, ARRAY_FILTER_USE_KEY);
	}
}
