<?php

namespace Entities;

class TripContainer extends BaseContainer
{

	protected $GoodsGroup = "Trip";

	/**
	 * Getter for mask of validation
	 * @return array
	 */
	protected function getInfoCheckMask()
	{
		return [
			'Name' => ['string', 40],
		];
	}

	/**
	 * Getter for collection name
	 * @return string
	 */
	public function getCollectionForStore() {
		return 'Trips';
	}

}
