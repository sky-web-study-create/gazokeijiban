<?php
  session_start ();

  header('Content-Type: text/html; charset=UTF-8');

  //1つ前のページを取得
  $motourl = $_SERVER['HTTP_REFERER'];

  function compWithReferrer($url){
    if(($url == 'http://www.skyweblife.shop/register.php') || ($url == 'http://www.skyweblife.shop/login.php') || ($url == 'http://www.skyweblife.shop/index.php')){
      return true;
    }
    return false;
  }

  if(compWithReferrer($motourl)){

    //ログアウトボタンが押されたらログイン画面に戻る
    if($_POST['logout']){
      header( "Location: login.php");
      exit;
    }

    //Basic認証
    require_once("basic.php");

    //ラッパー関数
    function strWrapper($str){
      //エスケープ処理
      $escaped = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
      return nl2br($escaped);
    }

    //データベース処理
    require_once("mysql.php");

    registerPostData();

    $dispArray = getPostDataFromSql();

    $loginInfoArray = getLoginDataFromSql();

    var_dump($loginInfoArray);

    //新規登録画面からの画面遷移でセッションにデータ登録成功のフラグが登録されていれば登録完了のアラート表示
    if(($motourl == 'http://www.skyweblife.shop/register.php') && $_SESSION["isRegistered"]){
      $alert = "<script type='text/javascript'>alert('新規会員登録が完了しました');</script>";
      $_SESSION["isRegistered"] = false;
    }
  }else {
    //直接index.phpにアクセスされたらログイン画面にリダイレクトする
    header( "Location: login.php");
    exit;
  }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>画像掲示板</title>
</head>
<body>
  <?php foreach ($loginInfoArray as $loginName) : ?>
    <p>ようこそ！<?php echo $loginName["user"];?>さん</p>
  <?php endforeach; ?>
  <h2>新規投稿</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="submit" value="ログアウト" name="logout">
    <p>タイトル</p>
    <input type="text" name="post_title"><br>
    <p>お名前</p>
    <?php foreach ($loginInfoArray as $loginName) : ?>
      <p><?php echo $loginName["user"];?></p>
    <?php endforeach; ?>
    <p>内容(1000文字以下)</p>
    <textarea name="contents" rows="20" cols="50" maxlength="1000"></textarea><br>
    <p>画像(png/jpg/jpegのみ)</p>
    <input type="file" name="image" accept=".png, .jpg, .jpeg"><br><br>
    <input type="submit" value="投稿する" name="update"><br><br>
  </form>

  <?php foreach ($dispArray as $postData) : ?>
    <p><?php echo strWrapper($postData["savedate"]);?></p>
    <p>タイトル:<?php echo strWrapper($postData["title"]);?></p>
    <p>投稿者:<?php echo strWrapper($postData["login_info_user"]);?></p>
    <p>本文:<?php echo strWrapper($postData["comment"]);?></p>

    <?php if($postData["image_url"] !== './tmp/'): ?>
      <img src="<?php echo strWrapper($postData["image_url"]); ?>" width="250" height="250"><br><br>
    <?php else :?>
          <p>画像:データなし</p><br>
    <?php endif;endforeach; ?>

  <?php
    echo $alert;
  ?>
</body>
</html>