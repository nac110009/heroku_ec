<?php
//共通処理
require_once('include/inc_php_start.php');

//商品一覧の取得 & 検索
if (isset($_GET['search'])) {
  //検索ボタンが押されたら検索を実行し、結果を表示する
  $search_char = filter_input(INPUT_GET, 'search_char', FILTER_SANITIZE_STRING);
  $product_list = $obj->search_product($search_char);
  if (empty($product_list)) {
    $message = '該当する商品がありません。';
  }
} else {
  $product_list = $obj->get_product_list(); //そうでないときは全商品一覧を表示する
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | <?= (empty($search_char)) ? "商品一覧" : "商品検索：" . $search_char; ?></title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/product_list.css" rel="stylesheet" type="text/css">
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->
  <main>
    <h1 class="headline"><span>PRODUCT</span></h1>
    <div class="msg"><span><?php if (!empty($search_char)) echo '検索文字 【 ' . $search_char . ' 】'; ?></span></div>
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
