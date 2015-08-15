<?php
require_once('../config.php');
if ( (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') )
{
	die( 'Request Error!' );
}

$ResArr = [
	555 => ['Name' => 'ololo2'],
	888777 => ['Name' => 'd df sd2'],
	55888 => ['Name' => 'sd fsd fs'],
];
echo json_encode($ResArr,  JSON_UNESCAPED_UNICODE);
exit;
try {
	if ( ($Actions = UtilFunctions::getPreparedPostString('Actions')) ) {
		$oAuth = new Auth\Auth();
		// 	$oPageController = new UI\PageController();
		if ( !($AccountId = $oAuth->checkAccountAuth()) ) { // аккаунт не авторизирован
			throw new MessageException("Вы не авторизованы!", 0);
		}
		switch ( $Actions ) {
			case "CheckName":
				if ( ($Name = UtilFunctions::getPreparedPostString('Name')) ) {
					$oUsers = new Processors\Users();
					$ResArr['message'] = $oUsers->checkUsername($_POST['Name']);
				} else {
// 					throw new MessageException("Введите ник персонажа!", 0);
				}
				break;
			case "CreateChar":
				if ( ($Name = UtilFunctions::getPreparedPostString('Name')) && ($Sex = UtilFunctions::getPreparedPostString('Sex')) ) {
					$oAccountContainer = Entities\AccountContainer::getInstance($AccountId);
					$oUsers = new Processors\Users();
					$ResArr['ok'] = $oUsers->createNewCharacter($oAccountContainer, $Name, $Sex);
					$ResArr['message'] = "Персонаж `<b>$Name</b>` успешно создан.";
				} else {
					throw new MessageException("Ошибка в данных при создании персонажа!", 0);
				}
				break;
			case "ChooseCharacter":
				if ( ($CharacterId = UtilFunctions::getPreparedPostString('Id')) ) {
					$oAccountContainer = Entities\AccountContainer::getInstance($AccountId);
					$ResArr['ok'] = $oAccountContainer->activateAccountCharacter($CharacterId);
					$ResArr['message'] = "Персонаж успешно выбран!";
				} else {
					throw new MessageException("Ошибка в данных при выборе персонажа!", 0);
				}
				break;
		}
	} 

} catch (Exception $e) {
	$ResArr['ok'] =  false;
	$ResArr['message'] = UtilFunctions::getMessageFromException($e);
}
echo json_encode($ResArr,  JSON_UNESCAPED_UNICODE);
