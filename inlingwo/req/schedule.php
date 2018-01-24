<?php
require('../main.php');

if(isset($_POST[$GLOBALS['json_post']])){
    if(!$Lg->logout()){
        cout("Schedule Success!");
        scheData();
    }
    else{
        cout("ERROR : Already OUT !");
    }
}
else{
    cout("ERROR : Inexplicable !");
}


// 数据录入
function scheData(){
    $data = $_POST[$GLOBALS['json_post']];     // 要修改的数据
  //  $data = file_get_contents('../schedule.json');
    $data = json_decode($data,true);
    $skey = array("mem_id");
    $sval = array($GLOBALS['ID']);
    foreach($data as $week=>$order){
        // 先排除干扰的数据
        if($week!='name'){
            foreach($order as $key=>$val){
                $key  =  $week.'_'.$key;
                array_push($skey,$key);
                array_push($sval,$val);
            }
        }
    }
    $skey = implode(",",$skey);
    $sval = implode("','",$sval);
    $sql  = " insert into schedule(".$skey.") values('".$sval."')";
    $GLOBALS['DBobj']->querySQL($sql); 
    $sql = " select * from schedule";
    var_dump($GLOBALS['DBobj']->sel($sql));
} 
