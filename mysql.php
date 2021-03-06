<?php

  //SQLからデータ取得して表示させる
  function readFromSqlRecord($pdo){
    // SQL作成
    $stmt = $pdo->prepare("SELECT * FROM gazokeijiban ORDER BY savedate DESC;");

    // SQL実行
    $res = $stmt->execute();

    if(!$res){
        echo "データ取得失敗:";
        print_r($stmt -> errorInfo());
    }

    //取得したデータを表示
    $rowArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rowArray;
  }

  //SQLにデータを登録する
  function setPostDataToSql($pdo,$login_info_user, $title, $comment, $image_url, $savedate){
    // SQL作成
    $stmt = $pdo->prepare("INSERT INTO gazokeijiban (login_info_user, title, comment, image_url, savedate
    ) VALUES (:login_info_user, :title, :comment, :image_url, :savedate )");

    // 登録するデータをセット
    $stmt->bindParam( ':login_info_user', $login_info_user, PDO::PARAM_STR);
    $stmt->bindParam( ':title', $title, PDO::PARAM_STR);
    $stmt->bindParam( ':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam( ':image_url', $image_url, PDO::PARAM_STR);
    $stmt->bindParam( ':savedate', $savedate, PDO::PARAM_STR);

    // SQL実行
    $res = $stmt->execute();

    if(!$res){
        echo "SQL失敗:";
        print_r($stmt -> errorInfo());
    }
  }

  //user_infoの情報を参照
  function referUserInfo($pdo){
    // SQL作成
    $placeholder = $_SESSION["userName"];
    $stmt = $pdo->prepare("SELECT * FROM login_info WHERE user IN (:placeholder);");

    $stmt->bindValue(':placeholder', $placeholder, PDO::PARAM_STR);

    // SQL実行
    $res = $stmt->execute();

    if(!$res){
      echo "データ取得失敗:";
      print_r($stmt -> errorInfo());
    }

    //取得したデータを表示
    $recordArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $recordArray;
  }

  //SQLに投稿データを登録する
  function registerPostData(){

    global $pdo;

    $author = $_SESSION["userName"];

    if(empty($_POST['update'])) return;

    //投稿するボタンが押されたときの処理
    $title = $_POST['post_title'];
    $contents = $_POST['contents'];

    //補足：サーバサイドで登録情報の文字数制限を加えるなら、登録されなかったことを知らせるためのエラーページを作り、リダイレクトしてユーザーに知らせるのがよい（今回は未対応）

    $path = './tmp/';
    $temp = $_FILES['image']['tmp_name'];
    $dest = $path.$_FILES['image']['name'];
    move_uploaded_file( $temp, $dest );

    // 登録するデータを用意
    date_default_timezone_set('Asia/Tokyo');
    $comment = $contents;
    $image_url = $dest;
    $savedate = date('Y-m-d H:i:s');

    // SQLに登録
    setPostDataToSql($pdo, $author, $title, $comment, $image_url, $savedate);

    // ページの更新
    header("Location: " . $_SERVER['PHP_SELF']);
  }

  function getPostDataFromSql(){

    global $pdo;

    //データベースから情報を取得
    return readFromSqlRecord($pdo);
  }

  function getLoginDataFromSql(){

    global $pdo;

    //ログイン情報を参照
    return referUserInfo($pdo);
  }

  // データベースに接続
  try{
      $pdo = new PDO('mysql:charset=UTF8;dbname=skyweblife', "skyweblife", "skywebpass");
  }catch(PDOException $e){
      echo "データ接続に失敗しました" .$e->getMessage();
  }

?>