<?php

namespace Commands;

use Entities\BaseContainer;

class DeleteCommand extends BaseCommand
{
	/**
	 * Getter for mask of validation
	 * @return array
	 */
	protected function getInfoCheckMask()
	{
		return [
			'pk' => ['string', 24],
			'Goods' => ['string', 40],
			'Name' => ['string', 40, 'default'], //3rd param = default value if undefined
		];
	}

	/**
	 * @param array $Data
	 * @return BaseContainer
	 */
	public function init(array $Data) {
		$oContainer = parent::init($Data);
		$this->initCommodity($oContainer);
		return $oContainer;
	}

	/**
	 * @return boolean
	 * @throws \Exception
	 */
	public function execute()
	{
		$Result = $this->getModel()->delete($this->Commodity);
		return $Result;
	}

}
