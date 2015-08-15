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
	public function read() {
		$oCollection = $this->getCollection();
		$ResArr = iterator_to_array($oCollection->find());
		$ResArr = [
	555 => ['Name' => 'ololo2'],
	888777 => ['Name' => 'd df sd2'],
	55888 => ['Name' => 'sd fsd fs'],
];
		return $ResArr;
	}

	/**
	 * @return array
	 */
	public function create() {
		$oCollection = $this->getCollection();
		$ResArr = iterator_to_array($oCollection->find());

		$ResArr = [
			5486 => ['Name' => 'BANG!! PPP'],
		];
		return $ResArr;
	}

}
