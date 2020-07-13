<?php
// セッションを開始
session_start();

// セッション変数を削除
$_SESSION = array();

//セッションクッキーを削除
if (isset($_COOKIE["PHPSESSID"])) {
  setcookie("PHPSESSID", '', time() - 1800, '/');
}

//セッションを破棄してTOPページに戻る
session_destroy();
header("Location: index.php");
