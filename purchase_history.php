<?php
//共通処理
require_once('include/inc_php_start.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

$member_id = $_SESSION['member_info']['member_id']; //会員ID取得
$message = '購入履歴がありません。';

//ログイン済みであれば受注ヘッダ情報を取得する
if (!empty($member_id)) {
  $order_header = $obj->get_order_header($member_id);
} else {
  header("Location: member_login.php");
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 購入履歴</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/purchase_history.css" rel="stylesheet" type="text/css">
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header>
  <nav><?php include('include/inc_tag_nav.php'); ?></nav>

  <main>
    <h1 class="headline"><span>HISTORY</span></h1>
    <div class="msg"><span><?php if (empty($order_header)) echo $message; ?></span></div>
    <div class="pc-container">
      <?php foreach ($order_header as $headers) : ?>
        <h4>受注ID：<?= $obj->h($headers['order_id']); ?></h4>
        <div class="order-date">日付 <?= date('Y/m/d', strtotime($obj->h($headers['purchase_time']))); ?></div>
        <div class="order-total">合計 &yen;<?= $obj->h($headers['total_price']); ?></div>
        <?php $order_detail = $obj->get_order_detail($headers['order_id']); ?>
        <?php foreach ($order_detail as $details) : ?>
          <?php $product_detail = $obj->get_product_detail($details['product_id']); ?>
          <div class="pc-container-elem">
            <div class="pc-container-elem-item">
              <div class="item-box-left">
                <img src="image/<?= $obj->h($product_detail['image_url']); ?>" alt="商品画像">
              </div>
              <div class="item-box-right">
                <div class="item-name"><?= $obj->h($product_detail['product_name']); ?></div>
                <div class="item-price">&yen;<?= $obj->h($details['price']); ?></div>
                <div class="item-num">個数 <?= $obj->h($details['number']); ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  </main>

  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->
</body>

</html>
