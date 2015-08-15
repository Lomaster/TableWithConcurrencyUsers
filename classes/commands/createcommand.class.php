<?php

namespace Commands;

class CreateCommand extends BaseCommand
{
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

	public function execute()
	{
		$ResArr = $this->getModel()->create();
		return $ResArr;
	}


}
