<?php
require('../main.php');

if(isset($_POST['name'])){
    if($Lg->register()){
        cout("Register Success!");
    }
}
