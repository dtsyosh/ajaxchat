<?php

require_once 'config.php';

if ($_POST['lastMessageID'] > 0)
    $messages = $database -> select ('SELECT * from messages where id > '. $_POST['lastMessageID']);
else {
    $messages = $database -> select ("SELECT * from messages order by id desc limit 10");
    $messages = array_reverse($messages);
}



$users_online = $database -> select('SELECT * from users_online order by username');

$json = array(
    'messages' => $messages,
    'users_online' => $users_online
);
header('Content-Type: application/json');
echo json_encode($json);