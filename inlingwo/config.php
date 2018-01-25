<?php
// Database 
$servername = "localhost";
$username = "";         // mysql 用户名
$password = "";         // mysql 密码
$dbname = "inlingwo";

// FILE_PATH
$index = "lingwo.html";     // 主文件
$error = "../static/html/404.html";

// 可以post空值的url
$ckurl = array("schedule.php","lend.php");

// cookie 的时间期限
$cookie_destroy = time()+60*60*24*30;

// 假设接受schedule的json 文件名为 json_post
$json_post = 'schedule';

// Time set
$limit_work_attendance_time = array(
    'mon' => array(
        'begin'=> "7:30",
        'end'=> "12:00",
    ),
    'aft' => array(
        'begin' => "14:30",
        'end' => "17:00",
    ),
    'eve' => array(
        'begin' => "19:30",
        'end' => "22:00",
    ),
);
