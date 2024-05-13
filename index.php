<?php 
    declare(strict_types=1);

    spl_autoload_register(function ($class) {
        require __DIR__ . "/src/$class.php";
    });

    set_error_handler("ErrorHandler::handleError");
    set_exception_handler("ErrorHandler::handleException");

    header("Content-type: application/json; charset=UTF-8");

    $parts = explode("/", $_SERVER["REQUEST_URI"]);
    //print_r($parts);

    if ($parts[2] != "index.php") {
        http_response_code(404);
        exit;
    }

    $id = $parts[3] ?? null;

    $database = new Database("localhost", "php_api_db", "root", "");

    $gateway = new UserGateway($database);

    $controller = new UserController($gateway);

    $controller->processRequest($_SERVER["REQUEST_METHOD"], $id); 
?>