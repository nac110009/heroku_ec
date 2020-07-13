<?php
// セッションスタート
session_start();

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// DB操作用クラスのインスタンス化
require_once('class/db_operation.php');
$obj = new DB_Operation();

// バリデーション用クラスのインスタンス化
require_once('class/validation.php');
$vld = new Validation();
