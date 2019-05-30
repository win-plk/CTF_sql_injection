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
// include('flag.html');
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
                        <div class="qmmc" id="qm0" style="z-index: 11;"><a href="logout.php">logout</a><span
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
                            <h3 class="announce"><?php echo $message; ?></h3>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div id="footer">
        </div>
    </div>
</body>
</html>
