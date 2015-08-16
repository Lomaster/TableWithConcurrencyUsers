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
		$Result = true;
		if ( ($LastModified = $this->checkDataExpired()) ) {
			$Result = $this->getModel()->read();
			$Result['LastModified'] = $LastModified;
		}
		return $Result;
	}

	protected function checkDataExpired()
	{
		$CurrentLM = $this->getModel()->getLastModified();
		$Result = false;
		if ( $CurrentLM != $this->Info['LastModified'] ) {
			$Result = $CurrentLM;
		}
		return $Result;
	}

}
