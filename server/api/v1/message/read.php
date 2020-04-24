<?php
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control');

header_remove('X-Powered-By'); //remove default PHP header

include_once '../../../database/databaseClass.php';
include_once '../../../entities/message.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

if (!isset($_GET["id"])) {
    echo ErrorMessages::getErrorMessage("message", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

if ($_GET["id"] < 1) {
    echo ErrorMessages::getErrorMessage("message", "negative_zero");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

$message = new Message($connection);
$message->id = $_GET["id"];

$res = $message->read();
$rows = $res->fetchArray(SQLITE3_ASSOC);

if ($rows) {
    echo SuccessMessages::getSuccessMessage("message", "read", $rows);
    return http_response_code($HTTP_200_OK);
} else {
    echo ErrorMessages::getErrorMessage("message", "read");
    return http_response_code($HTTP_500_SERVER_ERROR);
}