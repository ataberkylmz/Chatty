<?php
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../../database/databaseClass.php';
include_once '../../../entities/user.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Get raw data instead of only form/multi part form data.
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username)) {
    echo ErrorMessages::getErrorMessage("user", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

if (strlen($data->username) < 3 || strlen($data->username) > 20) {
    echo ErrorMessages::getErrorMessage("user", "length");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

$user = new User($connection);
$user->username = $data->username;

$res = $user->read();
$rows = $res->fetchArray();

// fetchArray method will return bool(false) if no match found.
if ($rows) {
    $options = [
        "ID" => $rows[0],
        "USERNAME" => $rows[1]
    ];
    echo SuccessMessages::getSuccessMessage("user", "read", $options);
    return http_response_code($HTTP_200_OK);
} else {
    echo ErrorMessages::getErrorMessage("user", "read");
    return http_response_code($HTTP_500_SERVER_ERROR);
}