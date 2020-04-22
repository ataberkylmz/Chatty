<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../../database/databaseClass.php';
include_once '../../../entities/user.php';
include_once '../../../utils/responseMessages.php';

$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Get raw data instead of only form/multi part form data.
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username)) {
    echo ErrorMessages::getErrorMessage("user", "invalid_key");
    return http_response_code(400);
}

if (strlen($data->username) < 3 || strlen($data->username) > 20) {
    echo ErrorMessages::getErrorMessage("user", "length");
    return http_response_code(400);
}

$user = new User($connection);
$user->username = $data->username;

if ($user->create()) {
    echo SuccessMessages::getSuccessMessage("user", "create");
    return http_response_code(201);
} else {
    echo ErrorMessages::getErrorMessage("user", "create");
    return http_response_code(500);
}