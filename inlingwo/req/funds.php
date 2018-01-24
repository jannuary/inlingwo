<?php
require('../main.php');
// 传入purpose / money
if(!$Lg->logout()){ 
    $val = implode("','",array($GLOBALS['ID'],addslashes($_POST['purpose']),addslashes($_POST['money'])));
    $sql = "insert into funds(mem_id,purpose,cost) values('".$val."')";
    $DBobj->querySql($sql);
    cout("Funds Success!"); 
}
else{
    cout("Error : Already OUT !");
}




