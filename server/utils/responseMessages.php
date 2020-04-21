<?php

class ErrorMessages {

    private static $restErrors = [
        "user" => [
            "invalid_key" => "{\n\t\"code\":\"1\"\n\t\"message\":\"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\":\"2\"\n\t\"message\":\"Username must be longer than 3 and shorter than 20.\"\n}"
        ],
        "message" => []
    ];

    public static function getErrorMessage($location, $type) {
        return self::$restErrors[$location][$type];
    }
}

class SuccessMessages {

    private static $successMessages = [
        "user" => [
            "create" => "{\n\t\"code\":\"0\"\n\t\"message\":\"User successfully created.\"\n}"
        ],
        "message" => []
    ];

    public static function getSuccessMessage($location, $type) {
        return self::$successMessages[$location][$type];
    }
}