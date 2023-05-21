<?php

include "common.php";

$login = get_safety($_GET['login']);
$pass = get_safety($_GET['pass']);

$response = [];

if(!empty($login) && !empty($pass)){
    $hashed_pass = get_hash($pass);
    $response['user'] = get_user($login, $hashed_pass, $response['err']);
}else{
    $response['err'] = 'Please make sure all fields are filled.';
}

echo json_encode($response);
die();


function get_user(string $login, string $hashed_pass, &$response_err){
    $err_add = '';//about how much tries left
    
    if(is_login_exist($login, $user)){

        $attempts = $user['Attempts'];
        if($attempts > 0){
            decrement_attempts($login);
            $err_add = '('.($attempts-1).' attempts left)';
        }else{
            $response_err = 'No attempts left. Please contact to administrator.';
            return false;
        }
        
        if($user['Password'] == $hashed_pass){
           $needed=['login' => $user['Login'], 
                    'name' => $user['Name'],
                    'birth_date' => $user['Birth_Date'],
                    'inst_url' => $user['Inst_URL'],
                    'photo' => $user['Photo']];

            return_attempts($login);
            session_setup($user);
            return $needed;
        }
    } 

    $response_err = 'Incorrect login or password. '.$err_add;
    return false;
}


function decrement_attempts(string $login){
    $conn = pdo_connect();
    $sql = "UPDATE Users SET Attempts=Attempts-1 WHERE Login=?";
    $res= $conn->prepare($sql);
    $res->execute([$login]);
    $conn = null;
}

function return_attempts($login){
    $conn = pdo_connect();
    $sql = "UPDATE Users SET Attempts=10 WHERE Login=?";
    $res= $conn->prepare($sql);
    $res->execute([$login]);
    $conn = null;
}

?>