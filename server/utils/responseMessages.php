<?php

class ErrorMessages {

    private static $restErrors = [
        "user" => [
            "invalid_key" => "{\n\t\"code\": \"2\"\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": \"3\"\n\t\"message\": \"Username must be longer than 3 and shorter than 20.\"\n}",
            "create" => "{\n\t\"code\": \"4\"\n\t\"message\": \"Error while creating user. Issue is not caused by client.\"\n}",
            "read" => "{\n\t\"code\": \"5\"\n\t\"message\": \"No match.\"\n}",
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
            "create" => "{\n\t\"code\": \"0\"\n\t\"message\": \"User successfully created.\"\n}",
            "read" => "{\n\t\"code\": \"1\"\n\t\"data\": {\n\t\t\"id\": {ID}\n\t\t\"username\": \"{USERNAME}\"\n\t}\n}",
        ],
        "message" => []
    ];

    public static function getSuccessMessage($location, $type, $option = NULL) {
        if ($option["ID"] != NULL && $option["USERNAME"] != NULL) {
            $str = SuccessMessages::$successMessages[$location][$type];
            foreach($option as $key => $value){
                $str = str_replace('{'.strtoupper($key).'}', $value, $str);
            }
            return $str;
        }
        return self::$successMessages[$location][$type];
    }
}