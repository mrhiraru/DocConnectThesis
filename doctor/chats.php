<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/message.class.php');

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Chats';
$chat = 'active';
include '../includes/head.php';
?>

<body>
    <?php require_once('../includes/header-doctor.php'); ?>

    <div class="container-fluid">
        <div class="row" style="height: 89vh;">
            <?php require_once('../includes/sidepanel-doctor.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 p-0" style="height: 100%;">
                <section id="chat" class="padding-medium mt-0">
                    <div class="d-flex h-100 m-0">
                        <!-- Left Sidebar (Chats List) -->
                        <div id="chat_sidepanel" class="d-flex flex-column bg-light border-end p-3 m-0" style="min-width: 25%;">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fs-5 fw-bold">Chats</span>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control border-2" id="searchChat" placeholder="Search" oninput="update_chatlist(<?= $_SESSION['account_id'] ?>)">
                            </div>
                            <ul id="chatList" class="list-unstyled mb-0">
                                <?php
                                $message = new Message();
                                $chatArray = $message->get_chats($_SESSION['account_id'], ($_SESSION['user_role'] == 3) ? 1 : 3, "");
                                foreach ($chatArray as $item) {
                                ?>
                                    <!-- display chat list using php -->
                                    <a href="javascript:void(0);" class="d-flex align-items-center text-dark text-decoration-none p-2 border-bottom" onclick="loadChatBox(<?= $_SESSION['account_id'] ?>,<?= $item['account_id'] ?>)">
                                        <img src="<?php if (isset($item['account_image'])) {
                                                        echo "../assets/images/" . $item['account_image'];
                                                    } else {
                                                        echo "../assets/images/default_profile.png";
                                                    } ?>" alt="Profile" class="rounded-circle me-3 border" height="40" width="40">
                                        <div class="w-100">
                                            <strong><?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])) ?></strong>
                                        </div>
                                        <?php
                                        if ($item['unread_count'] > 0) {
                                        ?>
                                            <span id="unread<?= $item['account_id'] ?>" class="top-50 start-100 translate-middle-x badge rounded-pill bg-danger">
                                                <?= $item['unread_count'] ?>
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        <?php
                                        }
                                        ?>
                                    </a>
                                    <!-- display chat list using php -->
                                <?php
                                }
                                ?>
                            </ul>
                        </div>


                        <!-- Chat Box -->
                        <div id="chat_box" class="flex-grow-1 d-flex flex-column m-0">
                            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                <p class="text-muted text-center">Select a user to start messaging.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>


</body>

</html>

<script>
    var currentOpenedChat = null;
    var currentChatRequest = null;
    var currentMessagesRequest = null;
    var last_message_id = 0;

    function loadChatBox(account_id, chatwith_account_id) {
        if (currentOpenedChat === chatwith_account_id) {
            return;
        } else {
            currentOpenedChat = chatwith_account_id;
        }

        if (currentChatRequest) {
            currentChatRequest.abort();
        }

        if (currentMessagesRequest) {
            currentMessagesRequest.abort();
        }

        currentChatRequest = $.ajax({
            url: '../handlers/chat.load_chatbox.php',
            type: 'GET',
            data: {
                account_id: account_id,
                chatwith_account_id: chatwith_account_id
            },
            success: function(response) {
                $('#chat_box').html(response);
                last_message_id = 0;
                scrollToBottom();
                $('#unread' + chatwith_account_id).remove();
                loadMessages(account_id, chatwith_account_id);
            },
            error: function(xhr, status, error) {
                console.error('Error loading chatbox:', error);
            },
            complete: function() {
                currentChatRequest = null;
            }
        });
    }

    function update_chatlist(account_id, user_role) {
        var search = $('#searchChat').val();
        $.ajax({
            url: '../handlers/chat.load_chatlist.php?search=' + search + '&account_id=' + account_id + '&user_role=' + user_role,
            type: 'GET',
            success: function(response) {
                $('#chatList').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error updating chatlist:', error);
            }
        });
    }

    function loadMessages(account_id, chatwith_account_id) {
        currentMessagesRequest = $.ajax({
            url: '../handlers/chat.load_messages.php',
            type: 'GET',
            data: {
                account_id: account_id,
                chatwith_account_id: chatwith_account_id,
                last_message_id: last_message_id
            },
            success: function(response) {
                $('#chatMessages').append(response);
                scrollToBottom();
                updateLastMessageId();
                console.log('last_message_id:', last_message_id);
                console.log('account_id:', account_id);
                console.log('chatwith_account_id:', chatwith_account_id);
                currentMessagesRequest = null;
                loadMessages(account_id, chatwith_account_id);
            },
            error: function(xhr, status, error) {
                console.error('Error loading messages:', error);
                if (status !== 'abort') {
                    console.error('Error loading messages:', error);
                    setTimeout(function() {
                        loadMessages(account_id, chatwith_account_id);
                    }, 5000);
                }
            }
        });
    }

    function updateLastMessageId() {
        let messages = $('#chatMessages').children();
        if (messages.length > 0) {
            last_message_id = messages.last().data('message-id');
        }
    }

    function scrollToBottom() {
        var chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // $('#chatForm').on('submit', function(e) {
    //   e.preventDefault();

    //   const formData = {
    //     send: $('#send').val(),
    //     sender_id: sender_id,
    //     receiver_id: receiver_id,
    //     message: $("#message").val()
    //   }

    //   $.ajax({
    //     url: '../handlers/chat.send_message.php',
    //     type: 'POST',
    //     data: formData,
    //     success: function(response) {
    //       $('#message').val('');
    //       //loadMessages(account_id, chatwith_account_id);
    //     },
    //     error: function(xhr, status, error) {
    //       console.error('Error sending message:', error);
    //     }
    //   });
    // });
</script>