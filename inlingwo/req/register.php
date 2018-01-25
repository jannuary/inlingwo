<?php
require('../main.php');

if(isset($_POST['username'])){
    if($Lg->register()){
        cout("Register Success!");
    }
}
