<?php

namespace Entities;

abstract class BaseContainer {

	protected $Info = [
		'Id' => null,
	];
	protected $GoodsGroup = "Undefined"; //Must be set in child class

	protected abstract function getInfoCheckMask();

	/**
	 * User have to use factory method
	 */
	private function __construct()
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
		$FilePath = '\\entities\\'.$ClassName;
		if ( $ClassName!==__CLASS__ && class_exists($FilePath) ) {
			$Object = new $FilePath();
		} else {
			throw new \Exception("Unknown commodity!", 400);
		}
		return $Object;
	}

	/**
	 * Info validator
	 * @param array $Data
	 * @throws \Exception
	 * @return array
	 */
	public function validateData(array $Data)
	{
		foreach($this->getInfoCheckMask() as $Key=>$Val)
		{
			if ( empty($Data[$Key]) )
			{
				throw new \Exception("Undefined key `{$Key}`!");
			}
			$DataValue = $Data[$Key];
			switch($Val[0]) {
				case 'string':
					if ( !($DataValue = strip_tags(trim($DataValue))) )
					{
						throw new \Exception("Value of`{$Key}` must be a string!");
					}
					if ( mb_strlen($DataValue)>$Val[1] )
					{
						throw new \Exception("Size of `{$Key}` must be no longer {$Val[1]}!");
					}
					break;
			}
			$Data[$Key] = $DataValue;
		}
		return $Data;
	}

	/**
	 * Setter for Container info
	 * @param array $Data
	 * @return bool
	 */
	public function init(array $Data)
	{
		$Data = $this->validateData($Data);
		foreach($this->getInfoCheckMask() as $Key=>$Val)
		{
			$this->Info[$Key] = $Data[$Key];
		}
		if ( isset($Data['pk']) ) {
			$this->Info['Id'] = $Data['pk'];
		}
		$this->Info['GoodsGroup'] = $this->GoodsGroup;
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
