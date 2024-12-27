<?php
Eshop::cleanUserData();
$user = new User($_POST);
$result = Eshop::userAdd($user);

if ($result) {
    echo USER_ADD_OK;
    header("Refresh: 3, url=create_user");
} else {
    echo USER_ADD_ERROR;
    header("Refresh: 3, url=create_user");
    exit();
}