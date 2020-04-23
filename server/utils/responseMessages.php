<?php

class ErrorMessages {

    private static $restErrors = [
        "user" => [
            "invalid_key" => "{\n\t\"code\": \"2\"\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": \"3\"\n\t\"message\": \"Username must be longer than 3 and shorter than 20.\"\n}",
            "create" => "{\n\t\"code\": \"4\"\n\t\"message\": \"Error while creating user. Issue is not caused by client.\"\n}",
            "read" => "{\n\t\"code\": \"5\"\n\t\"message\": \"No match.\"\n}",
        ],
        "message" => [
            "invalid_key" => "{\n\t\"code\": \"2\"\n\t\"message\": \"Invalid/Unsupported key provided\"\n}",
            "length" => "{\n\t\"code\": \"3\"\n\t\"message\": \"Usernames must be longer than 3 and shorter than 20.\"\n}",
            "invalid_target" => "{\n\t\"code\": \"6\"\n\t\"message\": \"Sender and receiver cannot be the same.\"\n}",
            "create" => "{\n\t\"code\": \"4\"\n\t\"message\": \"Error while creating message. Issue is not caused by client.\"\n}",
            "read" => "{\n\t\"code\": \"5\"\n\t\"message\": \"No match.\"\n}",
            "negative_zero" => "{\n\t\"code\": \"7\"\n\t\"message\": \"Message ID cannot be negative or zero.\"\n}",
            "invalid_type" => "{\n\t\"code\": \"8\"\n\t\"message\": \"Wrong type provided for given key.\"\n}",
        ]
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
        "message" => [
            "create" => "{\n\t\"code\": \"0\"\n\t\"message\": \"Message successfully created.\"\n}",
            "readWithID" => "{\n\t\"code\": \"1\"\n\t\"data\": {\n\t\t\"id\": {ID}\n\t\t\"sender\": \"{SENDER}\"\n\t\t\"receiver\": \"{RECEIVER}\"\n\t\t\"body\": \"{BODY}\"\n\t}\n}",
        ]
    ];

    public static function getSuccessMessage($location, $type, $option = NULL) {
        if ($option["ID"] != NULL && $option["USERNAME"] != NULL) {
            $str = SuccessMessages::$successMessages[$location][$type];
            foreach($option as $key => $value){
                $str = str_replace('{'.strtoupper($key).'}', $value, $str);
            }
            return $str;
        } else if ($option["ID"] != NULL && $option["SENDER"] != NULL && $option["RECEIVER"] != NULL && $option["BODY"] != NULL) {
            $str = SuccessMessages::$successMessages[$location][$type];
            foreach($option as $key => $value){
                $str = str_replace('{'.strtoupper($key).'}', $value, $str);
            }
            return $str;
        }
        return self::$successMessages[$location][$type];
    }
}