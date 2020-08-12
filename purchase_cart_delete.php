<?php
//共通処理
require_once('include/inc_php_start.php');

//require_once('class/send_line_message.php');
//require_once('class/data_extension.php');

require_once('class/journey_entry.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

$member_id = $_SESSION['member_info']['member_id'];

$redirect = "purchase_cart.php";
if (empty($member_id)) {
  // 未ログインの場合はログイン画面へ遷移
  $redirect = "member_login.php";
} else {
  if (!empty($_GET)) {
    // GETパラメタが存在する場合は商品を個別削除
    $product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    $obj->delete_cart_one($member_id, $product_id);
  } else {
    // 商品を全削除
    $obj->delete_cart($member_id);
  }
}

// リダイレクト処理
header("Location: " . $redirect);

