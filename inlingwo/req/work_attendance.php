<?php       // 只要访问即可
require("../main.php");
if(!$Lg->logout()){
    $ti = limitTime();  // 获取时间段
    if($ti){    
        worAtten($ti);  // 签到
    }
    else{
        cout("ERROR : It's Not Time Yet!");
    }
}
else{
    cout("Error : Already OUT !");
}

function limitTime(){       // 限定时间输入,并返回所在时间段,不在时间段内则为false
    $limit = $GLOBALS['limit_work_attendance_time'];
    $RDay = date('Y-m-d ', time());
        foreach($limit as $key => $t){
            $TBegin = strtotime($RDay. $t['begin']);  
            $TEnd   = strtotime($RDay. $t['end']);  
            if (time() >= $TBegin && time() <= $TEnd){
                return $key;
            }
        }
    return false;
}

function worAtten($ti){     // 数据插入
    $ti = $ti."_attendance";
    $sql = " select * from work_attendance where mem_id=".$GLOBALS['ID']." and date=date(now())";
    $ck  = $GLOBALS['DBobj']->sel($sql,'mem_id')[$GLOBALS['ID']];
    if(!$ck['date']){       // 如果当天没有签过则插入，否则更新
        $sql = "insert into work_attendance(mem_id,date,$ti) values('".$GLOBALS['ID']."',date(now()),time(now())) ";      
        $GLOBALS['DBobj']->querySql($sql);
    }
    elseif(!$ck[$ti]){
        $sql = "update work_attendance set $ti= time(now()) where mem_id=".$GLOBALS['ID']." and date=date(now()) ";
        $GLOBALS['DBobj']->querySql($sql);
    }
    else{
        $ago = date("i's''",time()-strtotime($ck[$ti]));
        $er = "ERROR : Check In <em class='time'>".$ago."</em> Ago";
        cout($er);
        return false;
    }
    cout("WORK ATTEND Success!!");
    return true;
} 