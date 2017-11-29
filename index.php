<?php 

require 'config.php';

session_start();

if (isset($_POST['username'])) 
    $_SESSION['username'] = $_POST['username'];
else 
    $_SESSION['username'] = '';


?>

<!DOCTYPE html>
<html lang="br">
    <head>
        <title>Chat</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="css/materialize.min.css">

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/materialize.min.js"></script>

    </head>
    
    <body>
        <script>
            
            $(function () {

                var username = "<?= $_SESSION['username'] ?>";
                var lastMessageID = 0;
                var lastUserID = 0;

                if (!username)
                    $(".login").prop('hidden', false);
                else {
                    $(".index").prop('hidden', false);
                }

                // Update the chat body with a predefined interval
                window.setInterval(function () {
                    console.log(lastMessageID);
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxUpdate.php',
                        data: { lastMessageID: lastMessageID },

                        success: function (data) {
                            console.log(data);
                            fill_messages(data.messages);
                            fill_users(data.users_online);
                        }
                    });

                }, 1500);



                // Store the message in database
                $('#message').keydown(function (event) {
                    if (event.which === 13) {
                        // Salvar a mensagem no banco

                        $.ajax({
                            type: 'post',
                            url: 'ajaxChatInsert.php',
                            data: {
                                message: $('#message').val(),
                                username: username
                            },

                            success: function (data) {
                                $('#message').val('');
                            }
                        });
                    }
                });

                // Prints the messages
                function fill_messages(data) {
                    
                    $.each(data, function (i, item) {
                        $('.chat-body').append('<p>[' + item.created_at + ']<b> ' + item.username + '</b>: ' + item.message + '</p>');
                        lastMessageID = item.id;
                    });
                    //console.log(lastMessageID);

                    $('.chat-body').animate({
                        scrollTop: $('.card').get(0).scrollHeight
                    }, 1000);
                }

                function fill_users(data) {
                    console.log(data);

                    $.each(data, function(i, item) {
                        if(item.id > lastUserID) {
                            $('.users-online').append('<p>' + item.username + '</p>');
                            lastUserID = item.id;
                        }
                        
                    });
                }

                function insert_user(username) {
                    $.ajax({
                        type: 'post',
                        url: 'ajaxOnlineInsert.php',
                        data: {username: "<?= $_SESSION['username'] ?>"},
                        success: function() {
                        
                        }
                    });
                }

                function delete_user(name) {
                    $.ajax({
                        type: 'post',
                        url: 'ajaxOnlineDelete.php',
                        data: {username: "<?= $_SESSION['username'] ?>"},
                        success: function() {
                            alert('Até mais ^.^');
                        }
                    });
                }
            });
        </script>
        <style>
            .chat-body {

                height:500px;
                overflow:scroll;
                overflow-x:hidden;
            }

            .users-online {
                height: 500px;
                overflow: scroll;
            }

        </style>


        <div class="row container">

            <div class="login" hidden>
            
                <h3>Bem vindo</h3>
            
                <form action="index.php" method="post">
                    <div class="input-field">
                        <input id="username" type="text" name="username" required>
                        <label for="username">Nome de Usuário</label>
                    </div>
                    <input type="submit" class="btn waves" id="enter-chat" value="Entrar">
                </form>
            </div>

            <div class="index" hidden>
                <div class="chat col s8">

                    Bem vindo <strong><i><?= $_SESSION['username'] ?></i></strong> 
                    <!-- Display messages -->
                    <div class="card chat-body">

                    </div>

                    <!-- Create message -->
                    <div class="card-action">
                        <div class="message input-field ">
                            <input placeholder="Press enter to send a message" id="message" type="text" name="message" maxlength="255">
                            <label for="message">Mensagem</label>
                        </div>
                    </div>
                </div>

                <div class="col s4">
                    <strong>Usuários online</strong>
                    <div class="users-online card">
                    
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>