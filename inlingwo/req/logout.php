<?php
require('../main.php');

if($Lg->logout(TRUE)){
    cout("Already OUT !");
}
else{
    cout("OUT error!");
}
