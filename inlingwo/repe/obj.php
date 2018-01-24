<?php

$DBobj = new ConnDB;
$Lg = new Login;

class ConnDB{
    var $conn;
    // 传入config.php参数
    function __construct() {
        $DB = $GLOBALS;
        $this->conn = new mysqli($DB['servername'], $DB['username'], 
                                 $DB['password'], $DB['dbname']);
        if (!$this->conn) {
            die(cout("error connection failed"));
        }
    }

    function __destruct(){
        $this->conn->close();
    }

    function querySql($sql){
        if (@$this->conn->query($sql)) {
            cout("Query Success!");
            return true;
        }
        else{
            die(cout("Query Error!"));
        }
    }

    function sel($sql,$FKeyType='id'){
        $result = $this->conn->query($sql);
        $data = array();
        if (!empty($result)) {
            if ($result->num_rows > 0) {
                // 返回数据
                while($row = $result->fetch_assoc()) {
                         $data[$row[$FKeyType]] =$row;
                }
                return $data;
            }else{
                return 0;
            }
        }
        else{
            die(cout("Select Error!"));
        }
    }

    function sql($Type,$Tb,$key='*',$val=NULL,$wkey=NULL,$wval=NULL){
        $data = array(
            'i'=>'insert into '.$Tb.'('.$key.") values('".$val."')",
            'u'=>'update '.$Tb.' set '.$key.'='.$val,
            'd'=>'delete from '.$Tb.' where '.$key.'='.$val,
            's'=>'select '.$key.' from '.$Tb,
        );
        $sql = $data[$Type];
        if($wval){
            $sql = $sql.' where '.$wkey.'='.$wval;
        }
        if($Type!='s'){
            return ConnDB::querySql($sql);
        }elseif($Type=='s'){
            return ConnDB::sel($sql);
        }
    }
};


class Login{
    var $data;
    var $cookie=NULL;
    var $mem_id;

    function __construct() {
        // 检查post数据是否有为空
        foreach($_POST as $key => $var){   
            if((!$key || !$var) && !ckVal()){
                    die(cout('KEY NULL ERROR!'));                   
            }
            $var = preg_replace('/\s/', '', $var);
            $this->data[addslashes($key)] = addslashes($var);
        }
        if(isset($_COOKIE['LS'])){
            $this->cookie = $_COOKIE['LS'];
        }
    }

    // 检测注销  $OUT 检测还是注销，默认是登陆状态 $OUT=true 为注销
    function logout($OUT=FALSE){
        if(!$this->cookie){
            return true;
        }
        $sql = 'select * from login_check where cookie="'.$this->cookie.'"';
        $result = $GLOBALS['DBobj']->sel($sql,'cookie');
        if(!$OUT && !empty($result)){
            $GLOBALS['ID'] = $result[$this->cookie]['mem_id'];
            return false;
        }elseif($OUT){
            setcookie('LS','404',time()-3600,'/');
            $sql = 'delete from login_check where cookie="'.$this->cookie.'"';
            $GLOBALS['DBobj']->querySql($sql,'mem_id');
            return true;
        }
        else{
            return true;
        }
    }

    // 注册
    function register(){
        // 先同名处理
        $sql = 'select name from member where name="'.$this->data['name'].'"';
        $result = $GLOBALS['DBobj']->sel($sql,'name');
        if(!empty($result)){
            // $login = $GLOBALS['index'];
            // header("Location:$login");
            $er = "ERROR : [ ".stripslashes($this->data['name'])." ] Already Exists!";
            cout($er);
            return false;
        }else{
            $cookie = md5(time()+$this->data['passwd']);
        //    setcookie('LS',$cookie,$GLOBALS['cookie_destroy'],'/','localhost');
            $passwd = md5($this->data['passwd']);
            $name = $this->data['name'];
            $sql = "insert into member(name,passwd) 
                    values('$name','$passwd')";
            $GLOBALS['DBobj']->querySql($sql);
            Login::login();
            return true;
        }
    }

    // 登录
    function login(){
        // 名字转id
        $sql = 'select * from member where name="'.$this->data['name'].'"';
        $this->mem_id = $GLOBALS['DBobj']->sel($sql,'name');
        if(!empty($this->mem_id)){
            $this->mem_id = $this->mem_id[stripslashes($this->data['name'])]['mem_id'];
        }else{
            die(cout("Error : Name Not Exists!"));
        }

        // 判断 name => passwd
        $passwd = md5($this->data['passwd']);
        // $name = $this->data['name'];
        $sql = "select * from member 
                where mem_id='$this->mem_id'";
        $login = $GLOBALS['DBobj']->sel($sql,'mem_id');
        if($login[$this->mem_id]['passwd'] === $passwd){
            // 判断之前是否登陆过      别人拿了你的cookie就可以登录了？
            $cookie = md5(time().$this->data['passwd']);
            $sql    = "select * from login_check where mem_id='$this->mem_id'";
            $result = $GLOBALS['DBobj']->sel($sql,'mem_id');
            if(empty($result)){
                setcookie('LS',$cookie,$GLOBALS['cookie_destroy'],'/');
                $sql = "insert into login_check(mem_id,cookie) values('$this->mem_id','$cookie')";
                $GLOBALS['DBobj']->querySql($sql);
            }
            else{
                setcookie('LS',$cookie,$GLOBALS['cookie_destroy'],'/');
                $sql = "update login_check set cookie='$cookie' where mem_id='$this->mem_id'";
                $GLOBALS['DBobj']->querySql($sql);
            }
            return true;
        }
        else{
            die(cout('Password Error!'));
        }
    }

};

