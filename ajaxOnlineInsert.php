<?php

require_once 'config.php';

$database -> insert('users_online', array(
    'username' => $_POST['username']
));