<?php

if(!function_exists('repoToControllerResponse')) {
    function repoToControllerResponse($success = 1, $message = "Success", $data = [], $status = 200) {
        return ["success" => $success, "message" => $message, "data" => $data, "status" => $status];
    }
}