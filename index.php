<?php
//共通処理
require_once('include/inc_php_start.php');
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | トップページ</title>
  <?php include('include/inc_tag_head.php'); ?>
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->

  <main>
    <div class="top-img">
      <img src="image/e_sale_300_1.png" alt="商品画像">
    </div>
    <div id="igdrec_1"></div>
  </main>

  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->
</body>

</html>
