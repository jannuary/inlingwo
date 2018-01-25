<?php       // 增加博主要修改$name & object.php的变量
require('../config.php');
require('../repe/func.php');
require('../repe/obj.php');
require('object.php');

$num_artic = 5;            // 爬取的文章篇数
$len = 10;                   // 输出文章的篇数
$name = array(               // 变量映射
    '陈泽坤' => 'CZK',
    '招文桃' => 'ZWT',
    '张丁胜' => 'ZDS',
);

function background($size){
    // send headers to tell the browser to close the connection
    header("Content-Length: $size");
    header('Connection: close');
    ob_end_flush();
    ob_flush();
    flush();
    ignore_user_abort(true);            //在关闭连接后，继续运行php脚本
    set_time_limit(60*30);              //no time limit，不设置超时时间（根据实际情况使用）
}

function blogMsg(){         // 博主数据
    $sql = "select * from blog_msg"; 
    $data = $GLOBALS['DBobj']->sel($sql,'name');
    $rname = array();
    if(!empty($data)){
        foreach($data as $name => $arti){
            $sql = "select * from blog_article where blog_id=".$arti['blog_id']." and date(created) = date(now())";
            if($GLOBALS['DBobj']->sel($sql,'blog_id') != 0)  continue;
            if(!array_key_exists($name,$GLOBALS['name']) || in_array($name,$rname)){
                continue;
            }
            else{
                $name = $GLOBALS['name'][$name];
                array_push($rname,$name);
                if(array_key_exists($name,$GLOBALS['Arti'])){
                    $d = (new Arti($name))->get_title_and_link();
                    if($d) insertArticle($d,$arti['blog_id']);
                }
                else{
                    $d = (new $name())->get_title_and_link();
                    insertArticle($d,$arti['blog_id']);
                }
            }
        }
    }
}

function insertArticle($data,$blog_id){   // DB输入文章数据 
    $var = "";
    foreach($data as $article => $id){
        $var = $var."(".$blog_id.",'".$article."','".$id."'),";
    }
    $var = rtrim($var,",");
    $sql = "insert into blog_article(blog_id,title,article_id) values".$var;
    $GLOBALS['DBobj']->querySql($sql);
    $sql = "delete from blog_article where blog_id=$blog_id and date(created) < date(now())";
    $GLOBALS['DBobj']->querySql($sql);
}

function getArticle(){              // 获取文章，返回datas
    $len = $GLOBALS['len'];      // 返回前端数据的数量
    $sql = "select * from blog_msg"; 
    $data = $GLOBALS['DBobj']->sel($sql,'name');
    $datas = array();
    if(!empty($data)){
        $i=0;
        foreach($data as $name => $arti){
            $sql = "select * from blog_article where blog_id=".$arti['blog_id']." order by id limit ".$len;
            $articles = $GLOBALS['DBobj']->sel($sql,'id');
            if(!empty($articles)){
                $d = array();
                foreach($articles as $id => $article){
                   $d[$article['title']]=$article['article_id'];
                }
                $url = array_key_exists($GLOBALS['name'][$name],$GLOBALS['Arti']) ? $GLOBALS['Arti'][$GLOBALS['name'][$name]]['url'] : $GLOBALS['Individual'][$GLOBALS['name'][$name]]['url'];
                $path = array_key_exists($GLOBALS['name'][$name],$GLOBALS['Arti']) ? $GLOBALS['Arti'][$GLOBALS['name'][$name]]['path'] : $GLOBALS['Individual'][$GLOBALS['name'][$name]]['path'];  
                $mem =  array(
                    "name" => $name,
                    "length" => count($d),
                    "url" => $url,
                    "path" => $path,
                    "data" => $d,
                );
                $datas[$i++]=$mem;
            }
        }
    }
    return $datas;
}

function spend()
{
    $stime=microtime(true);     // 计时
        /* code */
    $etime=microtime(true);     // 结束的计时
    $totalT=$etime-$stime;      //计算花费时间
    return $totalT;
}


