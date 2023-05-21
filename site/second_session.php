<?php
session_name('SESSID2');
session_start();

$_SESSION['special'] = 'Variable from Session with ID:'.session_id();
$_SESSION['name'] = 'Second name';
$_SESSION['login'] = 'Another login';
?>
<!DOCTYPE html;>
<html>
    <head>
        <link rel="stylesheet" href="public/css/styles.css">
        <title>Second Session</title>
    </head>
    <body>
        <div class="column content_wrap c_center">
                <div class="column">
                    <div id="main_container" class="form_wrap" style='width:400px;max-width:355px;'>
                        <?php 
                            foreach($_SESSION as $key => $value){
                                echo "<span class='straight' style='color:#222;'>".$key." : ".$value."</span><br>";
                            }
                        ?>
                    </div>
                </div>
        </div>           
    </body>
</html>