<?php
require_once("../classes/message.class.php");

$account_id = $_GET['account_id'];
$user_role = ($_GET['user_role'] == 3) ? 1 : 3;
$search = htmlentities($_GET['search']);

$message = new Message();
$chatArray = $message->get_chats($account_id, $user_role, $search);
foreach ($chatArray as $item) {
?>
    <!-- display chat list using php -->
    <a href="javascript:void(0);" class="d-flex align-items-center text-dark text-decoration-none p-2" onclick="loadChatBox(<?= $item['account_id'] ?>)">
        <img src="<?php if (isset($item['account_image'])) {
                        echo "../assets/images/" . $item['account_image'];
                    } else {
                        echo "../assets/images/default_profile.png";
                    } ?>" alt="Profile" class="rounded-circle me-3" height="40" width="40">
        <div>
            <strong><?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])) ?></strong>
        </div>
    </a>
    <!-- display chat list using php -->
<?php
}
?>