<?php
require('../main.php');

if(isset($_POST['name'])){
    if($Lg->login()){
        cout("Login Success!");
    }
}