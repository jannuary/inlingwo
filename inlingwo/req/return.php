<?php
require('../main.php');

if(isset($_POST['thing'])){
    if(!$Lg->logout()){
        retData();
    }
    else{
        cout("ERROR : Already OUT !");
    }
}
else{
    cout("Error : KEY MUST!");
}

function retData(){
    // 根据个人ID查找有无此物
    $device = addslashes($_POST['thing']);
    $sql = "select * from lend_device where mem_id=".$GLOBALS['ID']."  and device='".$device."' and return_status is NULL";
    $item = $GLOBALS['DBobj']->sel($sql);
    if($item){
        // 插入换的日期
        $sql = "update lend_device set return_status=time(now()) where id=".array_keys($item)[0];
        $GLOBALS['DBobj']->querySql($sql);
        cout("Return Success!");
    }
    else{
        cout("Error : NULL Item!");
        return false;
    }
}   