<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ctf";

// SECRET KEY
$emailOfTheRealAdmin = 'admin.win@ctf.com';
$flag = 'flaf{Here_Is_Flag}';

// Create connection
$dbh = new PDO("mysql:dbname=$dbname;host=$servername", $username, $password);

$message = '';

function print_json($inp){
    global $message;
    $message = current($inp)."<br>";
    // var_dump($inp);
    // echo $message;
    // switch(key($inp)){
    //     case 'error':
    //         echo "<br>".current($inp)."<br>";
    //     break;
    //     case 'message':
    //         echo "<script type='text/javascript'>alert('$message');</script>";
    //     break;

    //     case 'login':
    //         echo current($inp);
    //     break;
    // }
}

function isLogin(){
    if(isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['isAdmin']) && $_SESSION['exp']){
        if(time() < $_SESSION['exp']){
            return true;
        }else{
            logout();
            return false;
        }        
    }
    return false;
}

function logout(){
    session_destroy();
}

?>