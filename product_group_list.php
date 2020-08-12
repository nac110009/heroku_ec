<?php
//共通処理
require_once('include/inc_php_start.php');

//商品グループ一覧の取得 & 検索
$group_char = filter_input(INPUT_GET, 'group', FILTER_SANITIZE_STRING);
$product_list = $obj->get_group_list($group_char);
if (empty($product_list)) {
  $message = '該当する商品がありません。';
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | "カテゴリ検索：" . $group_char; ?></title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/product_list.css" rel="stylesheet" type="text/css">
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->
  <main>
    <h1 class="headline"><span>PRODUCT</span></h1>
    <div class="msg"><span><?php if (!empty($group_char)) echo 'カテゴリ 【 ' . $group_char . ' 】'; ?></span></div>
    <div class="err-msg"><span><?php if (!empty($message)) echo $message; ?></span></div>
    <div class="p-container">
      <div class="p-container-elem">
        <?php foreach ($product_list as $products) : ?>
          <div class="p-container-elem-item">
            <a class="item-img" href="product_detail.php?id=<?= $obj->h($products['product_id']); ?>">
              <img src="image/<?= $obj->h($products['image_url']); ?>" alt="商品画像">
            </a>
            <div class="item-name"><?= $obj->h($products['product_name']); ?></div>
            <div class="item-price"><?= '&yen;' . $obj->h($products['price']); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->

</body>

</html>
