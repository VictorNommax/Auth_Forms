<?php

include "common.php";


    $login = get_safety($_POST['login']);
    $pass = get_safety($_POST['pass']);
    $name = get_safety($_POST['name']);
    $date = get_safety($_POST['birth_date']);
    $inst = null;
    $photo = $_FILES['photo'];
    if(isset($_POST['inst_url']) && !empty($_POST['inst_url'])){
        $inst = $_POST['inst_url'];
        $photo = null;
    }

    $response = [];

    $response['login'] = login_check($login);
    $response['pass'] = pass_check($pass);
    $response['name'] = name_check($name);
    $response['date'] = 'OK';
    
    if(isset($inst) && $inst != null){
        $response['inst_url'] = check_inst_url($inst);
    }else{
        if($photo != null){
            $response['photo_field'] = check_photo($photo);
        }
    }

    if(all_is_OK($response)){
        $photo_name = null;//photo_name field in table is nullable
        if(!isset($inst)){
            if(($photo_name = upload_photo($photo)) === false){
                $response['photo_field'] = 'Oops! Something went wrong..';
            }  
        }
        $user = ['Login' => $login,
                'Password' => get_hash($pass),
                'Photo' => $photo_name,
                'Birth_Date' => $date,
                'Name' => $name,
                'Inst_URL' => $inst];
       
        save($user);

        echo 'GOOD';    
    }else{
        echo json_encode($response); 
    }


    
    function login_check(string $login){

        $login_len = strlen($login);
        if( $login_len >= 4 && $login_len <= 32){
            if(preg_match('#^[A-Za-z0-9,\_,\-]{4,32}$#', $login)){
                if(!is_login_exist($login)){
                    return 'OK';
                }
                return 'Such login already exist.';
            }
            return 'Login may consist of latin characters, digits and _ or - .';
        }
        return 'Login must be atleast 4 characters long.';
    }

    
    function pass_check(string $pass){

        if(strlen($pass) >= 8){

            $mes = 'Password must contain: ';
            if(!preg_match('/[a-z]/', $pass)){
                $mes.= 'lowcase ';
            }
            if(!preg_match('/[A-Z]/', $pass)){
                $mes.= 'upcase ';
            }
            if(!preg_match('/[0-9]/', $pass)){
                $mes.= 'numbers.';
            }

            if($mes == 'Password must contain: '){
                return 'OK';
            }
            return $mes;
        }
        return 'Password must be atleast 8 symbols.';
    }

    function name_check(string $name){

        if(strlen($name) > 2 && strlen($name) <= 30){
            if(preg_match('#[A-Za-z]{2,30}$#', $name)){
                return 'OK';
            }else{
                return 'Name must contain only letters.';
            }
        }
        return 'Name must be atleast 2 characters long.';
    }

    function check_inst_url(string $inst){

        if(preg_match('/^https?:\\/\\/(?:www\\.)?instagram\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/', $inst)){
            //router of insta will redirect u with code 200
            /*$headers = @get_headers($inst);

            if($headers[0] == 'HTTP/1.1 404 Not Found'){
                return 'Such profile doesn\'t exist.';
            }*/
            return 'OK';
        }
        return 'Incorrect Instagram profile URL.';
    }


    function check_photo(array $photo){

        $allowed_types = ['image/jpg', 'image/jpeg', 'image/png',
                      'image/webp', 'image/bmp'];

        if($photo['error'] !== UPLOAD_ERR_NO_FILE){
            if(in_array($photo['type'], $allowed_types)){
                return 'OK';
            }
            return 'Incorrect photo file type.';
        }
        return 'Upload error! Please try again.';
    }
    
    function upload_photo(array $photo){
        $upload_path = $_SERVER['DOCUMENT_ROOT'].'/photos/';

        $type = substr(strrchr(stristr($photo['type'], ''), '/'), 1);
        $name = md5(time().''.random_int(0,100000)).'.'.$type;

        if(move_uploaded_file($photo['tmp_name'], $upload_path.$name)){
            return $name;
        }
        return false;
    }

    function save(array $user){
        $sql = 'INSERT INTO Users (Login, Password, Photo, Birth_Date, Name, Inst_URL)'; 
        $sql.= 'VALUES (:Login, :Password, :Photo, DATE(:Birth_Date), :Name, :Inst_URL)';
        $conn = pdo_connect();
        $conn->prepare($sql)->execute($user);
        $conn = null;
        //session_setup($user);     
    }

    //checks on errors
    function all_is_OK(array $response){
        $i = 0;
        foreach($response as $field => $mes){
            if($mes != 'OK'){
                return false;
            }
            $i++;
        }
        if($i < 5){//5 neccessary fields
            return false;
        }
        return true;
    }



?>