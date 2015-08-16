<?php
namespace Utils;

class UtilFunctions {
	
	public static function getMessageFromException(\Exception $e, $All = false) {
		$ErrorMessage = $e->getMessage();
		$ErrCode = $e->getCode();
		if ( $ErrCode )
		{
			$ErrorMessage .= " (Code {$ErrCode})";
		}
		if ( _Debug )
		{
			$ErrorMessage .= "<br>".$e->getTraceAsString();
		}
		return $ErrorMessage;
	}
	
	/**
	 * Return processed variable $_POST['Name']
	 * @param string $Name
	 * @param boolean $Int Convert to Int
	 * @return boolean|numeric
	 */
	public static function getPreparedPostNumeric($Name, $Int = true) {
		$Res = false;
		if ( isset($_POST[$Name])
		&& is_numeric($_POST[$Name])
		) {
			$Res = 1*$_POST[$Name];
			if ( $Int ) {
				$Res = (int)$_POST[$Name];
			}
		}
		return $Res;
	}
	
	/**
	 * Return processed variable $_POST['Name']
	 * @param string $Name
	 * @return boolean|string
	 */
	public static function getPreparedPostString($Name) {
		$Res = false;
		if ( isset($_POST[$Name]) ) {
			$Res = strip_tags(trim($_POST[$Name]));
		}
		return $Res;
	}

}
