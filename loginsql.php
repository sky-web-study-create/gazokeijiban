<?php
  require_once("mysql.php");

  //既に登録されているメールアドレスかどうか確認
  function mailRegisterCheck(){

    global $pdo;
    $loginArray = [];

    if(empty($_POST["loginmail"])) return $loginArray;

    //メールアドレスの入力情報がある場合
    $placeholder = $_POST["loginmail"];

    // SQL作成(SQLインジェクション対策のため、変数名はプレースホルダを使用)
    $stmt = $pdo->prepare("SELECT * FROM login_info WHERE email IN (:placeholder);");

    $stmt->bindValue(':placeholder', $placeholder, PDO::PARAM_STR);

    // SQL実行
    $res = $stmt->execute();

    if(!$res){
      print_r($stmt -> errorInfo());
    }

    $loginArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $loginArray;
  }

  function matchLoginInfo($loginArray) {

    foreach($loginArray as $login){
      $loginPass = $login["password"];
      $loginUser = $login["user"];
      var_dump($loginPass);
    }

    //登録されている「メールアドレス」と「パスワード」が一致するかチェック
    if (password_verify($_POST["loginpass"], $loginPass)) {
      $_SESSION["userName"] = $loginUser;
      return true;
    }
    return false;
  }
?>