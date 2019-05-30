<?php
require_once('_config.php');

if(isLogin()){
    if($_SESSION['isAdmin'] && $_SESSION['email'] !== $emailOfTheRealAdmin){
        print_json(['message'=>'flag is....wait. you\'re not the real admin user (email is not matched). I don\'t give u flag!']);
    }else if($_SESSION['isAdmin'] && $_SESSION['email'] === $emailOfTheRealAdmin){
        print_json(['message'=>'flag is... '.$flag]);
    }else{
        print_json(['message'=>'you\'re logged in as non-admin user: '.$_SESSION['name']]);
    }
}else{
    Header("Location: index.php");
}
include('flag.html');
?>