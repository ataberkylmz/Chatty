<?php

class ErrorMessages {

    private static $restErrors = [
        "user" => [
            "invalid_key" => "{\n\t\"code\": 2,\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": 3,\n\t\"message\": \"Username must be longer than 3 and shorter than 20.\"\n}",
            "create" => "{\n\t\"code\": 4,\n\t\"message\": \"Error while creating user. Issue is not caused by client.\"\n}",
            "read" => "{\n\t\"code\": 5,\n\t\"message\": \"No match.\"\n}",
        ],
        "message" => [
            "invalid_key" => "{\n\t\"code\": 2,\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": 3,\n\t\"message\": \"Usernames must be longer than 3 and shorter than 20.\"\n}",
            "invalid_target" => "{\n\t\"code\": 6,\n\t\"message\": \"Sender and receiver cannot be the same.\"\n}",
            "create" => "{\n\t\"code\": 4,\n\t\"message\": \"Error while creating message. Issue is not caused by client.\"\n}",
            "read" => "{\n\t\"code\": 5,\n\t\"message\": \"No match.\"\n}",
            "negative_zero" => "{\n\t\"code\": 7,\n\t\"message\": \"Message ID cannot be negative or zero.\"\n}",
            "invalid_type" => "{\n\t\"code\": 8,\n\t\"message\": \"Wrong type provided for given key.\"\n}",
        ],
        "messages" => [
            "invalid_key" => "{\n\t\"code\": 2,\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": 3,\n\t\"message\": \"Username must be longer than 3 and shorter than 20.\"\n}",
            "read" => "{\n\t\"code\": 5,\n\t\"message\": \"No match.\"\n}",
        ],
        "chatlist" => [
            "invalid_key" => "{\n\t\"code\": 2,\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": 3,\n\t\"message\": \"Sender name must be longer than 3 and shorter than 20.\"\n}",
            "read" => "{\n\t\"code\": 5,\n\t\"message\": \"No match.\"\n}",
        ]
    ];

    public static function getErrorMessage($location, $type) {
        return self::$restErrors[$location][$type];
    }
}

class SuccessMessages {

    private static $successMessages = [
        "user" => [
            "create" => "{\n\t\"code\": 0,\n\t\"message\": \"User successfully created.\"\n}",
            "read" => ["code" => 1]
        ],
        "message" => [
            "create" => "{\n\t\"code\": 0,\n\t\"message\": \"Message successfully created.\"\n}",
            "read" => ["code" => 1]
        ],
        "messages" => [
            "read" => ["code" => 1]
        ],
        "chatlist" => [
            "read" => ["code" => 1]
        ]
    ];

    public static function getSuccessMessage($location, $type, $data = NULL) {

        if ($data != NULL) {
            return json_encode(self::$successMessages[$location][$type] + ["data" => $data]);
        }

        return self::$successMessages[$location][$type];
    }
}