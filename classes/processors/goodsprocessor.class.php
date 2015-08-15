<?php

namespace Processors;

use Entities\UserContainer;

/**
 * Processing any operations for users
 */
class GoodsProcessor {
	
    protected static function getUserCollection() {
        return getMongoDB()->selectCollection(_UserCollection);
    }
	
    protected static function getInviteCollection() {
        return getMongoDB()->selectCollection(_InvitesCollection);
    }

    /**
     * @return array
     * @throws \MongoException
     */
    public function getAllUsers() {
		$Filter = [];
        $Fields = [];
        $ResArr = [];
		if ( ($Result = iterator_to_array($this->getUserCollection()->find($Filter, $Fields))) ) {
            foreach($Result as $Id=>$Info) {
                $Info['Id'] = $Id;
                $Info['FriendsAmount'] = count($Info['Friends']);
                unset($Info['_id']);
                unset($Info['Friends']);
                $ResArr[] = $Info;
            }
        }

		return $ResArr;
	}

    /**
     * @return array
     * @throws \MongoException
     */
    public function getFriendsOfFriends(UserContainer $oUserContainer) {
        $Filter = [
            '_id' => new \MongoId($oUserContainer->getId()),
        ];
        $Fields = ['Friends'=>true];
        if ( !($ResArr = $this->getUserCollection()->findOne($Filter, $Fields)) ) {
            $ResArr = [];
        } else {
            $FriendsId = [];
            foreach ($ResArr['Friends'] as $Friend) {
                $FriendsId[] =  new \MongoId($Friend['Id']);
            };

            $Filter = [
                '_id' => ['$in'=>$FriendsId],
            ];
            $Fields = ['Friends'=>true, '_id'=>false];
            $Result = iterator_to_array($this->getUserCollection()->find($Filter, $Fields));
            $ResArr = [];
            foreach($Result as $Row) {
                foreach($Row['Friends'] as $Friend) {
                    $ResArr[$Friend['Id']] = $Friend['Name'];
                }
            }
            $ResArr = array_values($ResArr);
        }
		return $ResArr;
	}

    /**
     * @return array
     * @throws \MongoException
     */
    public function getFriends(UserContainer $oUserContainer) {
        $Filter = [
            '_id' => new \MongoId($oUserContainer->getId()),
        ];
        $Fields = ['Friends'=>true];
        if ( !($ResArr = $this->getUserCollection()->findOne($Filter, $Fields)) ) {
            $ResArr = [];
        } else {
            $ResArr = $ResArr['Friends'];
        }
//        var_dump($Result, $Filter);
		return $ResArr;
	}

    /**
     * @return array
     * @throws \MongoException
     */
    public function getInvitesToFriends(UserContainer $oUserContainer) {
		$Filter = [
            'Reciever.Id' => $oUserContainer->getId()
        ];
        $Fields = ['Reciever'=>false];
        $ResArr = [];
        if ( ($Result = iterator_to_array($this->getInviteCollection()->find($Filter, $Fields))) ) {
            foreach($Result as $Id=>$Info) {
                $Info['Id'] = $Id;
                unset($Info['_id']);
                $ResArr[] = $Info;
            }
        }
//        var_dump($Result, $Filter);
		return $ResArr;
	}

    /**
     * @return array
     * @throws \MongoException
     */
    public function declineInviteToFriends(UserContainer $oUserContainer, UserContainer $oTargetContainer) {
        $Filter = [
            'Sender.Id' => $oTargetContainer->getId(),
            'Reciever.Id' => $oUserContainer->getId(),
        ];
        $this->getInviteCollection()->remove($Filter);
        $Message = "Invite declined!";
		return $Message;
	}

    /**
     * @return array
     * @throws \MongoException
     */
    public function acceptInviteToFriends(UserContainer $oUserContainer, UserContainer $oTargetContainer) {
		$Filter = [
            'Sender.Id' => $oTargetContainer->getId(),
            'Reciever.Id' => $oUserContainer->getId(),
        ];
        $this->getInviteCollection()->remove($Filter);
        $Filter = [
            '_id' => new \MongoId($oUserContainer->getId()),
        ];
        $Data = [
            '$push' => [
                'Friends' => [
                    'Id' => $oTargetContainer->getId(),
                    'Name' => $oTargetContainer->getBriefInfo()['Name'],
                ],
            ],
        ];
        $this->getUserCollection()->update($Filter, $Data);
        $Filter = [
            '_id' => new \MongoId($oTargetContainer->getId()),
        ];
        $Data = [
            '$push' => [
                'Friends' => [
                    'Id' => $oUserContainer->getId(),
                    'Name' => $oUserContainer->getBriefInfo()['Name'],
                ],
            ],
        ];
        $this->getUserCollection()->update($Filter, $Data);
        $Message = "Invite accepted!";
		return $Message;
	}

	/**
	 * @param UserContainer
	 * @param UserContainer
	 * @return string
	 * @throws \MongoException
	 */
	public function sendInviteToFriends(UserContainer $oUserContainer, UserContainer $oTargetContainer) {
// 		var_dump($Params['Data'], $Params['Data']['$set']);
//        $UserId = new \MongoId($oUserContainer->getId());
		$Data = [
            'Sender' => [
                'Id' => $oUserContainer->getId(),
                'Name' => $oUserContainer->getBriefInfo()['Name'],
            ],
            'Reciever' => [
                'Id' => $oTargetContainer->getId(),
                'Name' => $oTargetContainer->getBriefInfo()['Name'],
            ],
        ];
		$this->getInviteCollection()->insert($Data);
        $Message = "Invite sent!";
		return $Message;
	}

}