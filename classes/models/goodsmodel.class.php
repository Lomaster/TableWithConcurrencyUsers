<?php

namespace Entities;

class GoodsModel {

	protected $BriefInfo = [];
	protected $Id = null;

	protected static function getCollection() {
		return getMongoDB()->selectCollection(_UserCollection);
	}

	/**
	 * Factory method
	 * @param $UserId
	 * @return UserContainer
	 * @throws \Exception
	 */
	public static function getInstance($UserId) {
		$UserId = new \MongoId("".$UserId);
		$Filter = ['_id' => $UserId];
		if ( !($Result = self::getUserCollection()->findOne($Filter)) ) {
			throw new \Exception("Incorrect UserId `{$UserId}`", 20);
		}
		return new UserContainer($Result);
	}
	
	/**
	 * @param array $BriefInfo
	 */
	protected function __construct(array $BriefInfo) {
		$BriefInfo['Id'] = "".$BriefInfo['_id'];
		$this->BriefInfo = $BriefInfo;
		$this->Id = $BriefInfo['Id'];
	}
	
	/**
	 * @return string
	 */
	public function getId() {
		return $this->Id;
	}

	/**
	 * User data
	 * @return array
	 */
	public function getBriefInfo() {
		return $this->BriefInfo;
	}

}
