<?php

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
  <h2>新規投稿</h2>
  <form method="POST" enctype="multipart/form-data">
    <p>タイトル</p>
    <input type="text" name="post_title"><br>
    <p>お名前</p>
    <input type="text" name="author_name"><br>
    <p>内容(1000文字以下)</p>
    <textarea name="contents" rows="20" cols="50" maxlength="1000"></textarea><br>
    <p>画像(png/jpg/jpegのみ)</p>
    <input type="file" name="image" accept=".png, .jpg, .jpeg"><br><br>
    <input type="submit" value="投稿する" name="update"><br><br>
  </form>

  <?php foreach ($dispArray as $data) : ?>
    <p><?php echo strWrapper($data["savedate"]);?></p>
    <p>タイトル:<?php echo strWrapper($data["title"]);?></p>
    <p>投稿者:<?php echo strWrapper($data["author"]);?></p>
    <p>本文:<?php echo strWrapper($data["comment"]);?></p>

    <?php if($data["image_url"] !== './tmp/'): ?>
      <img src="<?php echo strWrapper($data["image_url"]); ?>" width="250" height="250"><br><br>
    <?php else :?>
          <p>画像:データなし</p><br>
    <?php endif;endforeach; ?>
</body>
</html>