<?php
include('db_settings.php');
function add_user($username, $chat_id, $name, $old_id){
    if ($username === null || $chat_id === null || $name === null) {
        return false;
    }
    global $connection;
    $username = trim($username);
    $chat_id = trim($chat_id);
    $name = trim($name);

    if($chat_id == $old_id) {
        return false;
    }
    $query = "INSERT INTO users (username, chat_id, name) VALUES (:username, :chat_id, :name)";
    $statement = $connection->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':chat_id', $chat_id);
    $statement->bindParam(':name', $name);
    $statement->execute();
    return true;
}



function get_users_by_chat_id($chat_id){
    global $connection;
    $query = "SELECT * FROM users WHERE chat_id = :chat_id";
    $statement = $connection->prepare($query);
    $statement->bindParam(':chat_id', $chat_id);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}
function get_last_user() {
    global $connection;
    $query = "SELECT * FROM users order by id desc LIMIT 1";
    $statement = $connection->prepare($query);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}
function add_message($username, $chat_id, $text) {
    global $connection;
    if ($chat_id === null || $text === null || $username === null) {
        return false;
    }
    $username = trim($username);
    $chat_id = trim($chat_id);
    $text = trim($text);
    $query = "INSERT INTO messages(username, chat_id, text) VALUES (:username ,:chat_id, :text)";
    $statement = $connection->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':chat_id', $chat_id);
    $statement->bindParam(':text', $text);
    $statement->execute();
    return true;
}
function get_all_messages() {
    global $connection;
    $query = "SELECT * FROM messages";
    $statement = $connection->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
function get_last_message() {
    global $connection;
    $query = "SELECT * FROM messages order by id desc LIMIT 1";
    $statement = $connection->prepare($query);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}