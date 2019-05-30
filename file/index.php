<?php
require_once('_config.php');
require_once('class.CheckPasswordComplexity.php');
$pc = new CheckPasswordComplexity();

function no_hacker($inp){
    $baddays = ['select ', 'union', 'sleep'];
    foreach ($baddays as $badday) {
        if (stripos($inp, $badday) !== false) {
            print_json(['error'=>'are you hacker?']);
            return false;
        }
    }
    return true;
}

function login($inp){
    global $dbh,$pc;
    $sql = "SELECT passwd, name, isAdmin, email FROM users WHERE email=?;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$inp['email']]);
    $user = $stmt->fetch();
    if($stmt->rowCount() === 1 && $pc->checkPassword($inp['passwd']) === 'Excellent') {
        if(password_verify($inp['passwd'], $user['passwd'])){
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['isAdmin'] = $user['isAdmin'];
            $_SESSION['exp'] = time() + 180;
            print_json(['message'=>'logging in !']);
        }else{
            print_json(['error'=>'wrong password.']);
        }
    }else{
        print_json(['error'=>'user does not exist.']);
    }
}

function register($inp){
    global $dbh,$pc;
    // check dup email
    $sql = sprintf("SELECT email FROM users WHERE email ='%s';", $inp['email']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount()>0){
        print_json(['error'=>'email already exists.']);
    }else{
        // register
        $passwd = password_hash($inp['passwd'], PASSWORD_DEFAULT);
        $sql = sprintf("INSERT INTO users(isAdmin, name, email, passwd) VALUES (0,'%s','%s','%s');", $inp['name'],$inp['email'], $passwd);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        print_json(['message'=>'you have been successfully registered!']);
    }
}

switch(@$_POST['action']){
    case 'isLogin':
        if(isLogin()){
            print_json(['login'=>'true']);
        }else{
            print_json(['login'=>'false']);
        }
    break;
    
    case 'login':
        if(no_hacker($_POST['email']) && no_hacker($_POST['passwd'])){
            login($_POST);
        }
    break;
    
    case 'register':
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && no_hacker($_POST['name'])){
            register($_POST);
        }else{
            print_json(['error'=>'email or name is not in the correct format.']);
        }
        
    break;
}
include('index.html');
?>