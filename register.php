<?php
  //セッション管理開始
  session_start();
  $_SESSION["isRegistered"] = false;
  $_SESSION["userName"] = "";

  header('Content-Type: text/html; charset=UTF-8');

  $errors = [];

  //POST通信がされたらバリデーションチェックを実行する
  if($_SERVER["REQUEST_METHOD"] == "POST"){

    require_once('validation.php');

    $errors = checkValidation();

    if (empty($errors)){
      require_once('registersql.php');

      $userCheckFlg = registerPreUserCheck();
      var_dump($userCheckFlg);
      if(empty($userCheckFlg)){
        // login_infoと照合し、登録されているユーザ名であれば「ユーザ名は既に使われています」と画面に表示
        $alert = "<script type='text/javascript'>alert('ユーザ名は既に使われています。');</script>";
      }

      $mailCheckFlg = registerPreMailCheck();
      var_dump($mailCheckFlg);
      if(empty($mailCheckFlg)){
        //login_infoと照合し、登録されているメールアドレスであれば「既にメールアドレスが登録されています」と画面に表示
        $alert = "<script type='text/javascript'>alert('既にメールアドレスが登録されています。');</script>";
      }

      if($userCheckFlg && $mailCheckFlg){
        //ユーザ名・メールアドレスともに未登録の場合、データをlogin_infoに新規追加
        $setReturn = userInfoDataSet();
        if($setReturn){
          //セッションにデータ登録成功のフラグを保存
          $_SESSION["isRegistered"] = true;
          // リダイレクト
          header( "Location: index.php");
          exit;
        }
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
  <title>新規登録画面</title>
  <style>
    ul {
      list-style: none;
    }
  </style>
</head>
<body>
  <h2>新規登録画面</h2>
  <form action="register.php" method="POST">
    <div>
      <label for="loginuser">ユーザ名(半角英数)</label><br>
      <input type="text" name="loginuser" id="loginuser">
    </div>
    <div>
      <label for="loginmail">メールアドレス(半角英数)</label><br>
      <input type="text" name="loginmail" id="loginmail">
    </div>
    <div>
      <label for="loginpass">パスワード(半角英数)</label><br>
      <input type="password" name="loginpass" id="loginpass">
    </div>
    <button type="submit">新規登録</button>
  </form>
  <a href="login.php">既に登録されている方はこちら</a>
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