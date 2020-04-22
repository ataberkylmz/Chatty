<?php
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../../database/databaseClass.php';
include_once '../../../entities/user.php';
include_once '../../../utils/responseMessages.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Get raw data instead of only form/multi part form data.
$data = json_decode(file_get_contents("php://input"));

if (strlen($data->username) < 3 || strlen($data->username) > 20) {
    echo ErrorMessages::getErrorMessage("user", "length");
    return http_response_code(400);
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
    return http_response_code(200);
} else {
    echo ErrorMessages::getErrorMessage("user", "read");
    return http_response_code(500);
}