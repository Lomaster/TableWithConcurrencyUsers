<?php

namespace Commands;

use Entities, Entities\BaseContainer;

abstract class BaseCommand {

	private $oModel = null;
	protected $Info = [];

	public abstract function execute();
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
		$ClassName = "{$Type}Command";
		$FilePath = '\\commands\\'.$ClassName;
		if ( $ClassName!==__CLASS__ && class_exists($FilePath) ) {
			$Object = new $FilePath();
		} else {
			throw new \HttpException("Unknown command!", 400);
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
		//Now we have only 1 commodity `trip`, but can init any from specified parameter
		$oContainer = BaseContainer::getInstance($this->Info['Goods']);
//		var_dump(__FUNCTION__, $this->Info['Goods'], $oContainer);
		$this->initModel($oContainer);
		return true;
	}

	/**
	 * Init for Model
	 * @param BaseContainer $oContainer
	 */
	public function initModel(\Entities\BaseContainer $oContainer) {
		$this->oModel = new \Models\GoodsModel($oContainer);
	}

	/**
	 * Getter for Model
	 * @return \Models\GoodsModel
	 * @throws \Exception
	 */
	public function getModel() {
		if ( is_null($this->oModel) ) {
			throw new \Exception('Model is undefined!');
		}
		return $this->oModel;
	}

}
