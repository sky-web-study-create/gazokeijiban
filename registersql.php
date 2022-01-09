<?php
  require_once("mysql.php");

  function stmtExecute($pdo, $columnName,  $placeholder){

    // SQL作成(SQLインジェクション対策のため、変数名はプレースホルダを使用)
    $stmt = $pdo->prepare("SELECT * FROM login_info WHERE $columnName IN (:placeholder);");

    $stmt->bindValue(':placeholder', $placeholder, PDO::PARAM_STR);

    // SQL実行
    $res = $stmt->execute();
    if(!$res){
      print_r($stmt -> errorInfo());
    }

    $resultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($resultArray);

    return $resultArray;

  }

  //既に登録されているユーザ名かどうか確認
  function userNameDoubleCheck($pdo){

    $searchName = $_POST["loginuser"];

    $array = stmtExecute($pdo, 'user', $searchName);

    return $array;
  }

  //既に登録されているメールアドレスかどうか確認
  function mailDoubleCheck($pdo){

    $searchMail = $_POST["loginmail"];

    $array = stmtExecute($pdo, 'email', $searchMail);

    return $array;
  }

  function registerPreUserCheck(){

    global $pdo;

    if($_POST["loginuser"]){

      //login_infoと照合
      $array = userNameDoubleCheck($pdo);
      // login_infoと照合し、登録されているユーザ名であれば「ユーザ名は既に使われています」と画面に表示
      if(!empty($array)) return false;

      return true;
    }
  }

  function registerPreMailCheck(){

    global $pdo;

    if($_POST["loginuser"]){

      //login_infoと照合
      $array = mailDoubleCheck($pdo);

      if(!empty($array)) return false;
      
      return true;
    }
  }

  //SQLにデータを登録する
  function userInfoDataSet(){

    global $pdo;

    // SQL作成
    $stmt = $pdo->prepare("INSERT INTO login_info (user, email, password
    ) VALUES (:user, :email, :password )");

    //パスワードの暗号化
    $hash_password = password_hash($_POST['loginpass'], PASSWORD_DEFAULT);

    // 登録するデータをセット
    $stmt->bindParam( ':user', $_POST['loginuser'], PDO::PARAM_STR);
    $stmt->bindParam( ':email', $_POST['loginmail'], PDO::PARAM_STR);
    $stmt->bindParam( ':password', $hash_password, PDO::PARAM_STR);

    // SQL実行
    $res = $stmt->execute();

    if(!$res){
      echo "SQL失敗:";
      print_r($stmt -> errorInfo());
      return false;
    }

    $_SESSION["userName"] = $_POST['loginuser'];

    return true;
  }
?>