<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 24.01.17
 * Time: 15:34
 */

include "autoloader.php";

$result = [
    "error" => [
        "isError" => true,
        "message" => ""
    ],
    "result" => [

    ]
];

if (isset($_POST)):
    switch ($_POST['action']):
        case "load_page":
            $permission = new \Assay\Permission\InterfacePermission\Permission();
            $greetings_role = $permission->getGreetingsRole();
            $result['error']['isError'] = false;
            $result['result'] = [
                "greetings_role" => $greetings_role
            ];
            break;
        default:
            $result['error']['message'] = "Видимо что-то случилось...";
    endswitch;
else:
    $result['error']['message'] = "Видимо что-то случилось...";
endif;

print json_encode($result);