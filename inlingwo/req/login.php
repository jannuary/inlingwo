<?php
require('../main.php');

if(isset($_POST['username'])){
    if($Lg->login()){
        cout("Login Success!");
    }
}