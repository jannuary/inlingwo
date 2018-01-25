<?php

$Arti = array(
    "CZK" => array(
        'url' => 'http://blog.csdn.net/Notzuonotdied/article/list/',
        'path' => 'http://blog.csdn.net/notzuonotdied/article/details/',
        'str' => '/\/notzuonotdied\/article\/details\/([\d]+)" target=\"_blank\">([\s]*)<h3 class=\"blog-title odd-overhidden bottom-dis-8\">(.+)<\/h3>/',
        'page_link' => '/Notzuonotdied\/article\/list\/([\d]*)">/',
    ),
    "ZWT" => array(
        'url' => 'https://wen-tao.com/page/',
        'path' => 'https://wen-tao.com/',
        'str' => '/<h2 class="title-post"><a href="https:\/\/wen-tao.com\/(.*)\/"(.*)="bookmark">(.*)<\/a><\/h2>/',
        'page_link' => '/https:\/\/wen-tao.com\/page\/(.*)/',
    ),
);
$Individual = array(
    'ZDS' => array(
        'url' => 'http://dingdingblog.com/',
        'path' => "http://dingdingblog.com/article/",
        'page_link' => 'http://dingdingblog.com/api/article/all?page=',
    ),
);

class Arti{
    var $page;
    var $pagei;
    var $match;
    var $num_articl;

    function __construct($who){
        $this->num_articl = $GLOBALS['num_artic'];
        $this->match = $GLOBALS['Arti'][$who];
        $this->pagei= 1;  
    }
    function get_title_and_link(){
        $links = array();
        $titles = array();
        while(Arti::get_page_link() && count($links)<$this->num_articl){
            preg_match_all($this->match['str'],$this->page,$str);
            $links = array_merge ($links,$str[1]);
            $titles = array_merge ($titles,$str[3]); 
            $this->pagei++;
        }
        if(isset($titles[0])){
            $titles[0]= preg_replace("/<(.*)>/","",$titles[0]);
            $strs = array_combine($titles,$links);
            return array_slice($strs,0,$this->num_articl);  // slice 按需选取指定个数返还
        }
        return NULL;
    }
    function get_page_link(){
        $url = $this->match['url'].$this->pagei;
            ob_start();
            $this->page = file_get_contents($url);
            ob_end_clean();
        preg_match_all($this->match['page_link'],$this->page,$link);     // failed to open stream: HTTP request failed! 
        return count($link[1]);    // 0 为空
    }
};


class ZDS{
    function get_title_and_link(){
        $pagei = 1;
        $data = array();
        $datas = array();
        $code = 200;
        while(1){
            $url = $GLOBALS['Individual']['ZDS']['page_link'].$pagei;
                ob_start();
                $page = file_get_contents($url);
                ob_end_clean();
            $page = json_decode($page,true);
            if($page['code']!=200 || $pagei>500){
                break;
            }
            else{
                $data = array_merge($data,$page['articles']['data']);
            $pagei++;
            }
        }
        foreach($data as $key ){
            $datas[$key['title']]=$key['id'];
        }
        return array_slice($datas,0,$GLOBALS['num_artic']);
    }
}

