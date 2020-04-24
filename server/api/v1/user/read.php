<?php
/**
 * Set headers
 */
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Origin, Authorization, X-Requested-With, Content-Type, Accept, Cache-Control');
header_remove('X-Powered-By'); //remove default PHP header

/**
 * Include necessary components.
 */
include_once '../../../database/databaseClass.php';
include_once '../../../entities/user.php';
include_once '../../../utils/responseMessages.php';
include_once '../../../utils/httpResponses.php';

/** Create a new database connection, then store it in @var $connection*/
$dbClass = new databaseClass();
$connection = $dbClass->getSQLiteConnection();

// Cannot make a GET request with a body. Technically, I can, but it would be against the http standards :(
if (!isset($_GET["username"])) {
    echo ErrorMessages::getErrorMessage("user", "invalid_key");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

// Validate input, just like above.
if (strlen($_GET["username"]) < 3 || strlen($_GET["username"]) > 20) {
    echo ErrorMessages::getErrorMessage("user", "length");
    return http_response_code($HTTP_400_BAD_REQUEST);
}

// If data is valid, create a new entity/object, then fill details.
$user = new User($connection);
$user->username = $_GET["username"];

// Run query.
$res = $user->read();
// Normally fetchArray will be an iterable, yet we are fetching a single item here.
$rows = $res->fetchArray(SQLITE3_ASSOC);

// fetchArray method will return bool(false) if no match found.
if ($rows) {
    echo SuccessMessages::getSuccessMessage("user", "read", $rows);
    return http_response_code($HTTP_200_OK);
} else {
    echo ErrorMessages::getErrorMessage("user", "read");
    return http_response_code($HTTP_500_SERVER_ERROR);
}