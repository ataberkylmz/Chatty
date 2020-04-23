<?php
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control');

header_remove('X-Powered-By'); //remove default PHP header

include_once '../../../database/databaseClass.php';
include_once '../../../entities/chatlist.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Cannot make a GET request with a body. Technically, I can, but it would be against the http standards :(
if (!isset($_GET["sender"])) {
    echo ErrorMessages::getErrorMessage("user", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

if (strlen($_GET["sender"]) < 3 || strlen($_GET["sender"]) > 20) {
    echo ErrorMessages::getErrorMessage("user", "length");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

$chat = new ChatList($connection);
$chat->sender = $_GET["sender"];

$res = $chat->read();

$data = array();

while ($rest = $res->fetchArray())
{
    array_push($data, $rest['receiver']);
}

// TODO: FIX THIS SHIT???!?!?!?
if ($data) {
    echo json_encode(["data" => $data]);
    return http_response_code($HTTP_200_OK);
} else {
    echo ErrorMessages::getErrorMessage("chatlist", "read");
    return http_response_code($HTTP_500_SERVER_ERROR);
}