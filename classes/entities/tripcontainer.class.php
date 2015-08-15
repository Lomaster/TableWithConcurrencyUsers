<?php

namespace Entities;

class TripContainer extends BaseContainer
{

	/**
	 * Getter for mask of validation
	 * @return array
	 */
	protected function getInfoCheckMask()
	{
		return [
			'Name' => ['string', 10],
		];
	}



}
