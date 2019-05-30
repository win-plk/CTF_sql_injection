<?php
// finctf 2018: sqli101 - sqli102 (200)
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
if(isLogin()){
    Header("Location: flag.php");
}
    // include('index.html');
?>

<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body>
    <div id="page">
        <div id="header-home" class="clearfix">
        </div>
        <div>
            <!-- Add Slide Here -->
        </div>
        <div class="menubar">
            <table style="width:100%;" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <div style="font-size:1px;width:6px;height:20px;"></div>
                    </td>
                    <td style="width:100%;">
                        <div class="qmmc" id="qm0" style="z-index: 11;"><a href="#">Home</a><span
                                class="qmclear">&nbsp;</span></div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="content">
            <table id="layout-table" summary="layout">
                <tr>
                    <td style="width: 210px;" id="left-column"></td>
                    <td id="middle-column">
                        <?php if($message != ''){ ?>
                        <div>
                            <h2 class="headingblock header ">SYSTEM MESSAGE</h2>
                            <h3 class="announce"><?php echo $message; $message='';?></h3>
                        </div>
                        <?php } ?>
                        <div>
                            <h2 class="headingblock header ">Useful Resources</h2>
                            <table class="categorylist">
                                <tr>
                                    <td class="category name"><a href="file/index.txt" download>index.php (Partial Source Code)</a></td>
                                    <td class="category number">available</td>
                                </tr>
                                <tr>
                                    <td class="category name"><a href="file/flag.txt" download>flag.php (Partial Source Code)</a></td>
                                    <td class="category number">available</td>
                                </tr>
                                <tr>
                                    <td class="category name"><a href="file/instruction.pdf" download>Problem & Instruction</a></td>
                                    <td class="category number">available</td>
                                </tr>
                                <tr>
                                    <td class="category name"><a href="#">HINT</a></td>
                                    <td class="category number">NOT available</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td style="width: 210px;" id="right-column" class="">
                        <div class="blocklogin">
                            <div class="header">
                                <h2>Login</h2>
                            </div>
                            <div class="content">
                                <form name="form1" method="POST" action="index.php">
                                    <table>
                                        <tr><td>Email</td></tr>
                                        <tr><td><input type="text" name="email" id="email"></td></tr>
                                        <tr><td>Password</td></tr>
                                        <tr><td><input type="password" name="passwd" id="passwd"></td></tr>
                                    </table>
                                    <input type="submit" name="action" value="login">
                                </form>
                            </div>
                        </div>
                        <div class="blockregister">
                            <div class="header">
                                <h2>Register</h2>
                            </div>
                            <div class="content">
                                <form name="form2" method="POST" action="index.php">
                                    <table>
                                        <tr><td>Name</td></tr>
                                        <tr><td><input type="text" name="name" id="name"></td></tr>
                                        <tr><td>Email</td></tr>
                                        <tr><td><input type="text" name="email" id="email"></td></tr>
                                        <tr><td>Password</td></tr>
                                        <tr><td><input type="password" name="passwd" id="passwd"></td></tr>
                                    </table>
                                    <input type="submit" name="action" value="register">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="footer">
        </div>
    </div>
</body>

</html>

