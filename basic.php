<?php
    $user = 'test_sky';
    $pass = 'gazokeijibanpass';
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Private Page"');
        header('HTTP/1.0 401 Unauthorized');
        die("ログインするためには正しい入力情報が必要です");
    } else {
        if ($_SERVER['PHP_AUTH_USER'] != $user || $_SERVER['PHP_AUTH_PW'] != $pass) {
            header('WWW-Authenticate: Basic realm="Private Page"');
            header('HTTP/1.0 401 Unauthorized');
            die("入力情報が一致しません");
        }
    }
?>