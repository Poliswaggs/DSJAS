<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

require("../AdminBootstrap.php");

require(ABSPATH . INC . "Users.php");
require(ABSPATH . INC . "Administration.php");
require(ABSPATH . INC . "Banking.php");
require(ABSPATH . INC . "csrf.php");

if (!isset($_GET["id"])) {
    header("Location: /admin/bank/transactions.php");
}

$csrf = getCSRFSubmission("GET");
if (!verifyCSRFToken($csrf)) {
    die(getCSRFFailedError());
}

reverseTransaction($_GET["id"]);

header("Location: /admin/bank/transactions.php?transactionReversed");