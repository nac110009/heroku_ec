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

<!-- MC Collect Tracking Code -->
<script type="text/javascript">
_etmc.push(["setOrgId", "100019644"]);
// Insert/Update Content Catalog
_etmc.push(["updateItem",
  {
    "item_type": "content",
    "item": "TOPPAGE",
    "url": "https://<?= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>",
    "available": "Y"
  }
]);
// Tracking Page
_etmc.push(["setUserInfo", {"email" : "<?= $_SESSION['member_info']['mail']; ?>"}]);
_etmc.push(["trackPageView"]);
</script>

<!-- MC Conversion Tag -->
<script src="https://100019644.recs.igodigital.com/a/v2/100019644/home/recommend.js" type="text/javascript"></script>
</body>

</html>
