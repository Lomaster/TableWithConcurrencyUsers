<?php

namespace Entities;

use SebastianBergmann\Exporter\Exception;

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

	/**
	 * Info validator
	 * @param array $Data
	 * @throws \Exception
	 * @return array
	 */
	public function validateDataForSetting(array $Data)
	{
		foreach($this->getInfoCheckMask() as $Key=>$Val)
		{
			if ( empty($Data[$Key]) )
			{
				throw new \Exception("Undefined key `{$Key}`!");
			}
			$DataValue = $Data[$Key];
			switch($Val[0]) {
				case 'string':
					if ( !($DataValue = strip_tags(trim($DataValue))) )
					{
						throw new \Exception("Value of`{$Key}` must be a string!");
					}
					if ( mb_strlen($DataValue)>$Val[1] )
					{
						throw new \Exception("Size of `{$Key}` must be no longer {$Val[1]}!");
					}
					break;
			}
			$Data[$Key] = $DataValue;
		}
		return $Data;
	}

}
