<?php

namespace Commands;

use Entities\BaseContainer;

class ReadCommand extends BaseCommand
{
	/**
	 * Getter for mask of validation
	 * @return array
	 */
	protected function getInfoCheckMask()
	{
		return [
			'Goods' => ['string', 40],
			'LastModified' => ['int', 0, 0],
		];
	}

	public function execute()
	{
		$ResArr = $this->getModel()->read();
		return $ResArr;
	}


}
