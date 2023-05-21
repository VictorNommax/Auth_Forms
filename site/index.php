<?php
    session_name('SESSID1');
    session_start();
    
    
    if(isset($_GET['login']) && isset($_GET['pass']) && !isset($_SESSION['name'])){
        include 'app/login.php';
    }

    if(isset($_GET['logout']) && $_GET['logout'] && isset($_SESSION['name'])){
        include 'app/logout.php';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="public/css/styles.css">
        <script src="public/js/script.js" type="text/javascript"></script>
        <title>Log In</title>
    </head>
    <body>
        <div class="column content_wrap c_center">
            <div id="message_box" class="row c_center">
                <?php 
                if(isset($_COOKIE['message']) && $_COOKIE['message'] == 'new'){
                    echo (isset($_GET['msg']) && !empty($_GET['msg'])) ? "<div class='message'><span>".$_GET['msg']."</span></div>" : ""; 
                } 
                ?>
            </div>
            <div class="column">
                <div id="main_container" class="form_wrap">
                    <?php 
                        if(!isset($_SESSION['name']))
                        {
                echo '<h3 id="header_txt">Please Log-In</h3>
                    <form id="main_form" method="POST" enctype="multipart/form-data">';
                  echo '<input id="login" type="text" name="login" placeholder="Input login.." ';
                  echo (isset($_GET['login'])) ? "value=".$_GET['login'] : "";
                  echo'/><br>
                        <input id="pass" type="password" name="pass" placeholder="Input password.." /><br>
                        <!-- hidden -->
                        <input id="repeat_field" type="password" name="pass_repeat" placeholder="Repeat password.."  class="hidden"/><br>
                        <input id="name" type="text" name="name" placeholder="Input your name.." class="hidden" /><br>     
                        <input id="date" type="date" name="birth_date" class="hidden"/><br>
                        <input id="inst_url" type="text" name="inst_url" placeholder="Share your inst profile URL.."  class="hidden"/><br>
                        <div id="or_block" class="row c_center hidden">
                            <span>or share your photo:</span>
                        </div>
                        <input id="photo_field" type="file" name="photo" class="hidden"/>
                        <!-- -->
                        <div id="error_log" class="errors_log">
                        </div>
                        <div class="row c_center">
                            <input type="button" id="login_btn" class="login_ready btn" type="button" value="Log In" />
                            <input type="button" id="register_prepare" class="register_prepare btn" value="Not registered yet?">
                            <input type="button" id="register_btn" class="register_ready btn hidden" value="Register">
                        </div>
                    </form>';
                        }else{
                            
                            echo '<div class="avatar column" ';
                            if($_SESSION['inst'] != null){
                                echo '><div class="inst_name"><span>Name in Instagram: <span id="inst_name_span" class="straight_name">'.$_SESSION['inst'].'</span></span></div>';
                            }else{
                                echo 'style="background-image:url(\'photos/'.$_SESSION['photo'].'\')">';
                            }
                            echo '<div class="user_info column">
                                    <div class="user_name row">
                                        <span>'.$_SESSION['name'].'</span>
                                    </div>
                                    <div class="user_data row">
                                        <span>Login: <span class="straight">'.$_SESSION['login'].'</span></span>
                                        <span>Birth date: <span class="straight">'.$_SESSION['birth_date'].'</span></span>
                                    </div>
                                </div>
                            </div>';
                        }
                    ?>
                </div>
                <input type='button' id='logout_btn' <?php echo (!isset($_SESSION['name'])) ? "class='logout btn hidden'" : "class='logout btn'"; ?> value='Logout' />
                <br>
                <div id='link' <?php echo (!isset($_SESSION['name'])) ? "class='hidden'" : ""; ?>>
                    <a href='second_session.php'>Go to 2nd Session Page</a>
                </div>
            </div>
        </div>
    </body>
</html>