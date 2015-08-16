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

	public function getCollectionForStore() {
		return _TripsCollection;
	}

}
