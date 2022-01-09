<?php

  /**
   * 半角のみかどうかの判定
   *
   * @param String $str チェック文字列
   * @return boolean true：エラー無し false：validationエラーあり
   */
  function isSingleByteChar($str, $maxLength = null, $minLength = null) {
    # 半角以外が含まれていた場合、false
    return !(strlen($str) != mb_strlen($str,'UTF-8'));
  }

  /**
   * バリデーションチェック
   *
   * @param なし
   * @return array $errors(バリデーションNGなし：空配列、バリデーションNGあり：エラー内容)
   */
  function checkValidation(){
    // エラー内容
    $errors = [];

    // ・全角文字で入力されていたら「半角英数で入力してください」と画面に表示
    // ・空白未入力だったら「入力されていない項目があります」と画面に表示
    // ・メールアドレスに@が入っていなかったら「不正なメールアドレスです」と画面に表示

    foreach( $_POST as $postValue ){
      if( !isSingleByteChar( $postValue ) ) $errors[] = '半角英数で入力してください';
      if( empty( $postValue ) ) $errors[] = '入力されていない項目があります';
    }

    if(!filter_var($_POST['loginmail'], FILTER_VALIDATE_EMAIL))  $errors[] = '不正なメールアドレスです';

    var_dump($_POST);

    return $errors;
  }
?>