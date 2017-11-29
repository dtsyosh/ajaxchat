<?php

require_once 'config.php';

$database -> exec ("DELETE FROM users_online WHERE username LIKE ". $_POST['username']);
