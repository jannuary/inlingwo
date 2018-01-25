<?php
require('../main.php');

if(isset($_POST['thing']) && isset($_POST['promise_date_of_return'])){
    if(!$Lg->logout()){
        if(isRrturn()){
            if(lendData()){
                cout("Lend Success!");
            }
            else{
                cout("Error : KEY MAUST !");
            }
        }
        else{
            $str = "Error : ".$_POST['thing']." Is Not Returned !";
            cout($str); 
        }
    }
    else{
        cout("ERROR : Already OUT !");
    }
}
else{
    cout("error : Lend!");
}

function lendData(){        // 数据处理并插入
    // 先判断必要的数据
    $thing   = addslashes($_POST['thing']);
    $pmsdate = $_POST['promise_date_of_return'];
    $remark  = addslashes($_POST['remark']);
    if(!$thing || !$pmsdate){
        return false;
    }
    $key = "mem_id,device,remark,promise_date_of_return,lend_day";
    $var = implode("','",array($GLOBALS['ID'],$thing,$remark,$pmsdate))."',time(now())";
    $sql = "insert into lend_device(".$key.") values('".$var.")";
    $GLOBALS['DBobj']->querySql($sql);
    return true;
}

function isRrturn(){        // 借同名的设备是否已经归还，如果没有归还则返回错误
    $sql = "select * from lend_device where mem_id=".$GLOBALS['ID']."  and device='".addslashes($_POST['thing'])."' and return_status is NULL";
    $item = $GLOBALS['DBobj']->sel($sql);
    if($item){
        return false;
    }
    return true;
}