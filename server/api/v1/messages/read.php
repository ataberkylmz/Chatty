<?php
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control');

header_remove('X-Powered-By'); //remove default PHP header

include_once '../../../database/databaseClass.php';
include_once '../../../entities/messages.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Cannot make a GET request with a body. Technically, I can, but it would be against the http standards :(
if (!isset($_GET["sender"]) || !isset($_GET["receiver"])) {
    echo ErrorMessages::getErrorMessage("messages", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

if (strlen($_GET["sender"]) < 3 || strlen($_GET["sender"]) > 20 || strlen($_GET["receiver"]) < 3 || strlen($_GET["receiver"]) > 20) {
    echo ErrorMessages::getErrorMessage("messages", "length");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

$chat = new Messages($connection);
$chat->sender = $_GET["sender"];
$chat->receiver = $_GET["receiver"];

$res = $chat->read();

// initialize an array to store all results.
$data = array();

// Iterate over fetchArray to collect all results.
while ($rest = $res->fetchArray(SQLITE3_ASSOC))
{
    array_push($data, $rest);
}

if ($data) {
    echo SuccessMessages::getSuccessMessage("messages", "read", $data);
    return http_response_code($HTTP_200_OK);
} else {
    echo ErrorMessages::getErrorMessage("messages", "read");
    return http_response_code($HTTP_500_SERVER_ERROR);
}