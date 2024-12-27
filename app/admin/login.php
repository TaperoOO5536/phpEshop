<?php
Eshop::cleanUserDataToLogin();
$user = new User($_POST);

if(Eshop::userCheck($user)) {
  Eshop::login();
  header('Location: /admin');
}

echo USER_LOGIN_ERROR;
header('Refresh: 3, url=enter');
exit;