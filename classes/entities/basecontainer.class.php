<?php

namespace Entities;

abstract class BaseContainer {

	protected $Info = [];

	public abstract function validateDataForSetting(array $Data);
	protected abstract function getInfoCheckMask();

	/**
	 * User have to use factory method
	 */
	private function __construct(array $BriefInfo)
	{
	}

	/**
	 * Factory method
	 * @param $Type string
	 * @return BaseContainer
	 * @throws \Exception
	 */
	public static function getInstance($Type)
	{
		$ClassName = "{$Type}Container";
		$Container = null;
		if ( $ClassName!==__CLASS__ && @file_exists(strtolower($ClassName)) ) {
			new $ClassName();
		} else {
			throw new \Exception("Unknown commodity!");
		}
		return $Container;
	}

	/**
	 * Setter for Container info
	 * @param array $Data
	 * @return bool
	 */
	public function setData(array $Data)
	{
		$Data = $this->validateDataForSetting($Data);
		foreach($this->getInfoCheckMask() as $Key=>$Val)
		{
			$this->Info[$Key] = $Data[$Key];
		}
		return true;
	}

	/**
	 * Getter for Container data
	 * @return array
	 */
	public function getInfo() {
		return $this->Info;
	}

}
