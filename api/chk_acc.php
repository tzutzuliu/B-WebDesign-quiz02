<?php
include_once "../base.php";

$acc=$_POST['acc']??$_GET['acc'];
echo $User->math('count','id',['acc'=>$acc]);

?>