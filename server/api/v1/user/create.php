<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control');
header("Access-Control-Allow-Origin: *");

header_remove('X-Powered-By'); //remove default PHP header

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

if ($user->create()) {
    echo SuccessMessages::getSuccessMessage("user", "create");
    return http_response_code($HTTP_201_CREATED);
} else {
    echo ErrorMessages::getErrorMessage("user", "create");
    return http_response_code($HTTP_500_SERVER_ERROR);
}