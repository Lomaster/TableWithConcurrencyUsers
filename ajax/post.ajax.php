<?php
use Commands\BaseCommand, Entities\BaseContainer, Utils\UtilFunctions;

require_once('../config.php');

if ( empty($_POST['Action']) ) {
	$_POST = $_GET;
}
try {
	if ( (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') )
	{
//		throw new \HttpException("Request Error!", 400);
	}
	$Command = BaseCommand::getInstance(UtilFunctions::getPreparedPostString('Action'));
	$Command->init($_POST);
	$Result = $Command->execute();

//	$Command = BaseCommand::getInstance('Read');
//	$Command->init($_POST);
//	$Result = $Command->execute();
//	var_dump($Result);
	echo json_encode($Result, JSON_UNESCAPED_UNICODE);
//} catch (HTTPException $e) {
//	header($e->getMessage(), false, $e->getCode());
} catch (LogicException $e) {
	echo "Data error: ".UtilFunctions::getMessageFromException($e);
} catch (Exception $e) {
	echo "Error: ".UtilFunctions::getMessageFromException($e);
}

