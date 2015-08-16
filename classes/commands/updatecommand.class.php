<?php

namespace Commands;

use Entities\BaseContainer;

class UpdateCommand extends BaseCommand
{
	/**
	 * Getter for mask of validation
	 * @return array
	 */
	protected function getInfoCheckMask()
	{
		return [
			'name' => ['string', 40],
			'pk' => ['string', 24],
			'value' => ['string', 40],
			'Goods' => ['string', 40],
		];
	}

	public function init(array $Data) {
		$oContainer = parent::init($Data);
		$this->initCommodity($oContainer);
		return $oContainer;
	}

	public function execute()
	{
		$ResArr = $this->getModel()->update($this->Commodity);
		return $ResArr;
	}

}
