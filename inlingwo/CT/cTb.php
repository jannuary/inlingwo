<?php
require("sql.php");
styleTb();

// 创建数据库 ===========================>
try{
    echo "<tr>";
    echo '<td>Database</td><td>'.$dbname." </td>";
    $conn = new mysqli($servername, $username, $password);
    // 检测连接
    if ($conn->connect_error) {
        die("<td> connect fail => " . $conn->connect_error."</td></tr>");
    } 
    $sql = 'CREATE DATABASE IF NOT EXISTS '.$dbname.
            ' DEFAULT CHARSET utf8 COLLATE utf8_general_ci';
    if ($conn->query($sql) === TRUE) {
        echo "<td><b style=\"color:green\">success!</b></td></tr>";
    } else {
        throw new Exception("<td><b style=\"color:red\">" . $conn->error."</b></td></tr>");  
    }
    $conn->close();
}
catch(Exception $e){
    echo $e->getMessage();
}

// 创建表 ===============================>
try{
    $conn = new mysqli($servername, $username, $password,$dbname);
    // 检测连接
    if ($conn->connect_error) {
        die("<td> connect fail => " . $conn->connect_error."</td></tr>");
    }

    foreach($sqls as $Fkey => $Ikey){
        echo '<tr><td rowspan="'.
                count($Ikey).'" style="background:#fff;">'.
                $Fkey.'</td>';
                
        foreach($Ikey as $key => $val){
            
            echo '<td>'.$key.'</td><td>';
            if ($conn->query($val) === TRUE) {
                echo '<b style="color:green">success!</b></td></tr><tr>';
            } else {
                throw new Exception( '<b style="color:red">'. $conn->error."</b></td>");  
            }
        } 
    }
    $conn->close();
    echo '</table>';
}
catch(Exception $e){
    echo $e->getMessage();
    //   exit();   // 要用throw 否则继续往下执行，不会执行{}里的
}    

function styleTb(){ 
    $eStyle='
    <style type="text/css">
    #customers
    {
    font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
    width:80%;
    border-collapse:collapse;
    }

    #customers td, #customers th 
    {
    font-size:1.2em;
    border:1px solid #98bf21;
    padding:3px 7px 2px 7px;
    }

    #customers th 
    {
    font-size:1.1em;
    text-align:left;
    padding-top:5px;
    padding-bottom:4px;
    background-color:#A7C942;
    color:#ffffff;
    }

    </style>
    ';
    echo $eStyle;
    echo '<table align="center" id="customers";>
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Status</th>
        </tr>';
}