<?php

// 防止直接访问
function getRef()
{
    $url = @$_SERVER['HTTP_REFERER'];
    $localhost = $GLOBALS["index"];
    if( !empty( $url ) ){
        if( !strstr($url ,$localhost)){
            @header("http/1.1 404 not found");
            @header("status: 404 not found");
            include($GLOBALS["error"]);
            exit();
        }
    }
    else{
        @header("http/1.1 404 not found");
        @header("status: 404 not found");
        include($GLOBALS["error"]);
        exit();
    }
}

// 输出样式
function style(){
    echo "<style type=\"text/css\">
        @font-face
        {
        font-family: myFont;
        src: url('../static/style/paradise.ttf');
        }
        *{
            padding-top: 5vh;
            text-align: center;
            color: #aaa;
            // font-family: myFont;
            letter-spacing: 0.2em;
        }
        body {
            background-color: #363636;
            background-image:         linear-gradient(45deg, hsla(0,0%,0%,.25) 25%, transparent 25%, transparent 75%, hsla(0,0%,0%,.25) 75%, hsla(0,0%,0%,.25)), 
                                    linear-gradient(45deg, hsla(0,0%,0%,.25) 25%, transparent 25%, transparent 75%, hsla(0,0%,0%,.25) 75%, hsla(0,0%,0%,.25));
            background-position:0 0, 2px 2px;
            background-size:4px 4px;
            padding: 100px;
            }
        .e{
            color: red;
            padding: 0.2em;
        }
        .s{
            color: #98bf21;
            padding: 0.2em;
        }
        .time{
            color: red;
            padding: 0.3em;
        }
      </style><h1>";
}


// 输出内容
function cout($str=NULL){
    $str = str_ireplace("error","ERROR ",$str);
    $str = str_ireplace("success","Success",$str);
    echo $str;
}

// 是否对数据处理
function ckVal(){
    $str = $_SERVER['SCRIPT_FILENAME'];
    foreach($GLOBALS['ckurl'] as $val){
        if(strstr($str,$val)){
            return true;
        }
    }
    return false;
}
