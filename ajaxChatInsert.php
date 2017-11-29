<?php

require_once 'config.php';

$database -> insert('messages', array(
    'message' => $_POST['message'],
    'username' => $_POST['username']
));