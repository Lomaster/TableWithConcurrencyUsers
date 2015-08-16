<?php

namespace Commands;

use Entities, Entities\BaseContainer;

abstract class BaseCommand {

	private $oModel = null;
	protected $Info = [];
	protected $Commodity = null;

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
	 * @return BaseContainer $Object
	 * @throws \HttpException
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
				if ( empty($Val[2]) ) {
					throw new \Exception("Undefined key `{$Key}`!");
				} else {
					$Data[$Key] = $Val[2];
				}
			}
			$DataValue = $Data[$Key];
			switch($Val[0]) {
				case 'int':
					if ( !is_numeric($DataValue) )
					{
						throw new \Exception("Value of`{$Key}` must be an integer!");
					}
					$DataValue = (int)$DataValue;
					break;
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
	 * Setter for command info
	 * @param array $Data
	 * @return BaseContainer $oContainer
	 */
	public function init(array $Data)
	{
		$Data = $this->validateData($Data);
		foreach($this->getInfoCheckMask() as $Key=>$Val)
		{
			$this->Info[$Key] = $Data[$Key];
		}
		if ( !empty($Data['value']) && !empty($Data['name']) ) {
			$this->Info[$Data['name']] = $Data['value'];
		}
		$oContainer = BaseContainer::getInstance($this->Info['Goods']);
		$this->initModel($oContainer);
		return $oContainer;
	}

	public function initCommodity(\Entities\BaseContainer $oContainer)
	{
		$oContainer->init($this->Info);
		$this->Commodity = $oContainer;
	}

	/**
	 * Init for Model
	 * @param BaseContainer $oContainer
	 * @return null
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
