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
			'Goods' => ['string', 40],
		];
	}

	/**
	 * @param array $Data
	 * @return \Entities\BaseContainer
	 */
	public function init(array $Data) {
		$oContainer = parent::init($Data);
		$this->initCommodity($oContainer);
		return $oContainer;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function execute()
	{
		$ResArr = $this->getModel()->create($this->Commodity);
		return $ResArr;
	}


}
