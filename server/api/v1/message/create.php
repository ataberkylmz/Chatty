<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../../database/databaseClass.php';
include_once '../../../entities/message.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Get raw data instead of only form/multi part form data.
$data = json_decode(file_get_contents("php://input"));

// JSON Object must include sender, receiver and body keys.
if (!isset($data->sender) || !isset($data->receiver) || !isset($data->body)) {
    echo ErrorMessages::getErrorMessage("message", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

// Sender and receiver cannot be same.
if ($data->sender == $data->receiver) {
    echo ErrorMessages::getErrorMessage("message", "invalid_target");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

// Sender and receiver usernames must be in valid range.
if ((strlen($data->sender) < 3 || strlen($data->sender) > 20) || (strlen($data->receiver) < 3 || strlen($data->receiver) > 20)) {
    echo ErrorMessages::getErrorMessage("message", "length");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

$message = new Message($connection);
$message->sender = $data->sender;
$message->receiver = $data->receiver;
$message->body = $data->body;

if ($message->create()) {
    echo SuccessMessages::getSuccessMessage("message", "create");
    return http_response_code($HTTP_201_CREATED);
} else {
    echo ErrorMessages::getErrorMessage("message", "create");
    return http_response_code($HTTP_500_SERVER_ERROR);
}