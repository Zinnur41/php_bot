<?php
const PROJECT_ROOT = __DIR__."/../";
require PROJECT_ROOT."/vendor/autoload.php";
include('bot_menu.php');
include_once(PROJECT_ROOT. "/db/db_operations.php");
include_once(PROJECT_ROOT. "/db/db_settings.php");
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

const TOKEN = "6308581761:AAGNflu0ltSDEr91YqRD7bv6kX7ZlMJcSjk";

$messageFromForm = $_POST['usermsg'];


$telegram = new Api(TOKEN);
$result = $telegram->getWebhookUpdates();

$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$username = $result["message"]["from"]["username"];
$first_name = $result["message"]["from"]["first_name"];

//Вытаскиваем из бд юзеров по chat_id
$get_user = get_users_by_chat_id($chat_id);
$old_id = $get_user['chat_id'];


//Список сообщений
$array_of_messages = get_all_messages();
$messages = $array_of_messages;

//Последний id пользователя, который отправил сообщение
$last_message = get_last_message();
$last_chat_id = $last_message['chat_id'];

//Последнее имя пользователя, который написал
$last_username = $last_message['username'];



//Статические ответы бота
switch ($text) {
    case "/start":
        $reply = "<b>Добро пожаловать! Вас приветсвует робот техподдержки, чем могу помочь?</b>";
        $reply_markup = Keyboard::make(['keyboard' => MENU, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup, "parse_mode" => "html"]);
        break;
    case MENU[0][0]:
        $list_of_commands = "/getRandomImg - Получите рандомную картинку\n/'что-то' - в разработке";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $list_of_commands]);
        break;
    case MENU[1][0]:
        $info = "<b>Ваш username: </b>" . $username
            . "\n" . "<b>Ваше имя: </b>" . $first_name
            . "\n" . "<b>ID в Telegram: </b>" . $chat_id;
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $info, "parse_mode" => "html"]);
        break;
    case MENU[2][0]:
        $message = "<b>Оставьте ваше сообщение, скоро вам ответят</b>";
        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $message, "parse_mode" => "html"]);
        break;
}

//Отправка сообщения последнему написавшему пользователю
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $getQuery = array(
        "chat_id" => $last_chat_id,
        "text" => $messageFromForm,
    );
    $ch1 = curl_init("https://api.telegram.org/bot" . TOKEN . "/sendMessage?" . http_build_query($getQuery));
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch1, CURLOPT_HEADER, false);
    $resultQuery = curl_exec($ch1);
    curl_close($ch1);
}

    add_user($username, $chat_id, $first_name, $old_id);
    add_message($username, $chat_id, $text);


/*

$photoFromForm = $_POST['photo'];


$result = file_get_contents("https://api.telegram.org/bot". TOKEN ."/getUpdates");
$result_json = json_decode($result);

$user_id = $result_json->result[count($result_json->result) - 1]->message->chat->id;
$user_name = $result_json->result[count($result_json->result) - 1]->message->from->first_name;

$message_array = [];
for ($i = 0; $i < count($result_json->result); $i++) {
    $message_array[] = ($result_json->result[$i]->message->text);
}

//642017515

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submitmsg'])) {
        $getQuery = array(
            "chat_id" 	=> $user_id,
            "text"  	=> $messageFromForm,
            "parse_mode" => "html",
        );
        $ch1 = curl_init("https://api.telegram.org/bot" . TOKEN . "/sendMessage?" . http_build_query($getQuery));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        $resultQuery = curl_exec($ch1);
        curl_close($ch1);
    }
    else if (isset($_POST['submitjpg'])) {
        $arrayQuery = array(
            'chat_id' => $user_id,
            'caption' => 'Проверка работы',
            'photo' => curl_file_create(__DIR__ . "/img.png", 'image/png' , 'img.png')
        );
        $ch2 = curl_init('https://api.telegram.org/bot'. TOKEN .'/sendPhoto');
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $arrayQuery);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_HEADER, false);
        $res = curl_exec($ch2);
        curl_close($ch2);
    }
}

*/
