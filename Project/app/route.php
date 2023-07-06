<?php
switch ($_SERVER["REQUEST_URI"]) {
    case '':
    case '/':
        require_once __DIR__ . DIRECTORY_SEPARATOR . '../web/view/main.html';
        break;
    case '/chat':
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
        require_once __DIR__ . DIRECTORY_SEPARATOR . '../web/view/chat.html';
        break;
    case '/app/index.php':
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
        break;
    default:
        http_response_code(404);
}

