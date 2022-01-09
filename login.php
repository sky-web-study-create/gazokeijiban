<?php
  //セッション管理開始
  session_start();
  $_SESSION["userName"] = "";

  header('Content-Type: text/html; charset=UTF-8');

  $errors = [];

  //POST通信がされたらバリデーションチェックを実行する
  if($_SERVER["REQUEST_METHOD"] == "POST"){

    require_once('validation.php');

    $errors = checkValidation();

    if (empty($errors)){

      require_once('loginsql.php');

      $loginInfoArray = mailRegisterCheck();

      if(empty($loginInfoArray)){
        //メールアドレスが登録されていなければ、「メールアドレスとパスワードの組み合わせが誤っています。」と画面に表示
        $alert = "<script type='text/javascript'>alert('メールアドレスとパスワードの組み合わせが誤っています。');</script>";
      }

      if($loginInfoArray){
        //登録されているメールアドレスとパスワードが一致するかチェック
        $matchPassFlg = matchLoginInfo($loginInfoArray);

        if($matchPassFlg){
          // ログイン情報が一致したらトップページへ
          header( "Location: index.php");
          exit;
        }
        //ログイン情報が一致しない場合「メールアドレスとパスワードの組み合わせが誤っています。」と画面に表示
        $alert = "<script type='text/javascript'>alert('メールアドレスとパスワードの組み合わせが誤っています。');</script>";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <style>
    ul {
      list-style: none;
    }
  </style>
</head>
<body>
  <h2>ログイン画面</h2>
  <form action="login.php" method="POST">
    <div>
      <label for="loginmail">メールアドレス(半角英数)</label><br>
      <input type="text" name="loginmail" id="loginmail">
    </div>
    <div>
      <label for="loginpass">パスワード(半角英数)</label><br>
      <input type="password" name="loginpass" id="loginpass">
    </div>
    <button type="submit">ログイン</button>
  </form>
  <a href="register.php">初めて登録する方はこちら</a>
  <ul>
    <?php foreach ($errors as $msg): ?>
      <li><?= $msg ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php
    echo $alert;
  ?>
</body>
</html>