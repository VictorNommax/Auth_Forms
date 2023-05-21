<?php 


function get_hash(string $pass){
    $salt = "test1234tset4321ghbdtn987654!@*";
    $salted = $salt.$pass;
    return hash('sha256',md5($salted));
}

function get_safety(string $val){
    return filter_var(trim($val), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

function pdo_connect(){  
    try{
      $conn = new PDO('mysql:host=db;dbname=code_test_db', 'root', '');
      return $conn;
    }catch(PDOException $e){
      return $e->getMessage();
    }
  }

function is_login_exist(string $login, mixed &$get_user = false){
    $conn = pdo_connect();
    $req = $conn->prepare("SELECT * FROM Users WHERE Login=?");
    $req->execute([$login]);
    $conn = null; 
    //if such login doesn't exist fetch gives bool
    if(is_array($users = $req->fetch())){
        if(count($users) > 0){
            //it decreases amount of total requests for login
            if($get_user !== false){
              $get_user = $users;
            }
            return true;
        }
    }
    return false;
}

function session_setup(array $user){
  
  $_SESSION['name'] = $user['Name'];
  $_SESSION['photo'] = $user['Photo'];
  $_SESSION['inst'] = $user['Inst_URL'];
  $_SESSION['login'] = $user['Login'];
  $_SESSION['birth_date'] = $user['Birth_Date'];
  $_SESSION['valid'] = true;

}



?>