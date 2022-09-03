<?php
include_once "../base.php";
$user=$User->find(['email'=>$_GET['email']]);


echo (!empty($user))?"您的密碼為:".$user['pw']:"查無此資料";
?>