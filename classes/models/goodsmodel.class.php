<?php

namespace Models;

use Entities\BaseContainer;

class GoodsModel {

	protected static $oMongoDB = null; //We will use 1 connection
	protected $CollectionName = '';

	public function __construct(BaseContainer $oCommodity)
	{
		$this->CollectionName = $oCommodity->getCollectionForStore();
	}

	protected static function getMongoDB()
	{
		if ( is_null(self::$oMongoDB) ) {
			$Mongo = new \MongoClient();
			self::$oMongoDB = $Mongo->selectDB(_GoodsDB);
		}
		return self::$oMongoDB;
	}

	protected function getCollection()
	{
		return self::getMongoDB()->selectCollection($this->CollectionName);
	}

	/**
	 * @return array
	 */
	public function create(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$InsertInfo = $oCommodity->getInfo();
		$oCollection->insert($InsertInfo);
		$Result = [
			"".$InsertInfo['_id'] => ['Name' => $InsertInfo['Name']],
		];
		return $Result;
	}

	/**
	 * @return array
	 */
	public function read() {
		$oCollection = $this->getCollection();
		$Filter = [];
		$Fields = [
			'Name'=>1,
		];
		$Arr = $oCollection->find($Filter, $Fields);
		$ResArr = [];
		foreach($Arr as $Row) {
			$Id = "".$Row['_id'];
			$ResArr[$Id] = [];
			$ResArr[$Id]['Name'] = $Row['Name'];
		}
		return $ResArr;
	}

	/**
	 * @return array
	 */
	public function update(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$CommodityInfo = $oCommodity->getInfo();
		$InsertInfo = [
			'Name' => $CommodityInfo['Name'],
		];
		$MongoId = new \MongoId($CommodityInfo['Id']);
		$Filter = [
			'_id' => $MongoId,
		];
		$Result = $oCollection->findAndModify($Filter, $InsertInfo);
		return true;
	}

	/**
	 * @return array
	 */
	public function delete(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$MongoId = new \MongoId($oCommodity->getInfo()['Id']);
		$Filter = ['_id'=>$MongoId];
		$Result = $oCollection->remove($Filter);
		if ( !$Result || !$Result['n'] ) {
			throw new \LogicException("This row is already deleted!");
		}
		return true;
	}

}
