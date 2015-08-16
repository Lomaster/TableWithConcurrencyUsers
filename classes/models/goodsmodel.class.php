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
	 * Add commodity to DB
	 * @param BaseContainer $oCommodity
	 * @return array
	 */
	public function create(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$InsertInfo = $oCommodity->getInfo();
		$oCollection->insert($InsertInfo);
		$Result = [
			"".$InsertInfo['_id'] => ['Name' => $InsertInfo['Name']],
		];
		$this->setLastModified();
		return $Result;
	}

	/**
	 * Get goods data from DB
	 * @return array
	 */
	public function read() {
		$oCollection = $this->getCollection();
		$Filter = [
			'Name' => [
				'$exists' => true,
			],
		];
		$Fields = [
			'Name' => 1,
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
	 * Update commodity in DB
	 * @return array
	 */
	public function update(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$CommodityInfo = $oCommodity->getInfo();
		$InsertInfo = [
			'Name' => $CommodityInfo['Name'],
		];
		$MongoId = new \MongoId($CommodityInfo['Id']);
		//Need additional parameter like Name
		$Filter = [
			'_id' => $MongoId,
		];
		$Result = $oCollection->findAndModify($Filter, $InsertInfo);
		$this->setLastModified(); //Need check $Result
		return true;
	}

	/**
	 * Delete commodity
	 * @param BaseContainer $oCommodity
	 * @return bool
	 */
	public function delete(\Entities\BaseContainer $oCommodity) {
		$oCollection = $this->getCollection();
		$MongoId = new \MongoId($oCommodity->getInfo()['Id']);
		$Filter = ['_id'=>$MongoId];
		$Result = $oCollection->remove($Filter);
		if ( !$Result || !$Result['n'] ) {
			throw new \LogicException("This row is already deleted!");
		}
		$this->setLastModified();
		return true;
	}

	/**
	 * Get last modified TimeStamp
	 * @return int TimeStamp
	 */
	public function getLastModified()
	{
		$oCollection = $this->getCollection();
		$Filter = [
			'LastModified'=>[
				'$exists' => true,
			]
		];
		$Fields = [
			'LastModified'=>1,
			'_id'=>0,
		];
		$Result = $oCollection->findOne($Filter, $Fields);
		if ( is_null($Result) ) {
			$Result = $this->setLastModified();
		} else {
			$Result = $Result['LastModified'];
		}
		return $Result;
	}

	/**
	 * Update last modified TimeStamp
	 * @return int
	 */
	public function setLastModified()
	{
		$Now = time();
		$oCollection = $this->getCollection();
		$Filter = [
			'LastModified'=>[
				'$gt'=>0
			]
		];
		$Data = [
			'LastModified'=> $Now,
		];
		$Result = $oCollection->update($Filter, $Data, ['upsert'=>true]);
		return $Now;
	}

}
