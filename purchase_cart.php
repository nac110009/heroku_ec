<?php
//共通処理
require_once('include/inc_php_start.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

$member_id = $_SESSION['member_info']['member_id'];
$message = '商品が追加されていません。';

$cart_list = $obj->get_cart_list($member_id);
$cart_total_price = $obj->get_cart_total_price($member_id);
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | カート</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/purchase_cart.css" rel="stylesheet">
</head>

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header>
  <nav><?php include('include/inc_tag_nav.php'); ?></nav>
  <main>
    <h1 class="headline"><span>CART</span></h1>
    <div class="msg"><span><?php if (empty($cart_total_price['sum'])) echo $message; ?></span></div>
    <div class="pc-container">

      <?php if (!empty($cart_total_price['sum'])) : ?>
        <div class="pc-container-elem">
          <div class="pc-container-elem-item">
            <div class="item-box-left">
              <div class="item-total">合計 &yen;<?= $cart_total_price['sum']; ?></div>
            </div>
            <div class="item-box-right">
              <div class="container-btn">
                <input type="button" onclick="location.href='purchase.php'" value="購入画面へ" class="btn">
              </div>
            </div>
          </div>
        </div>

        <?php foreach ($cart_list as $row) : ?>
          <?php $product_detail = $obj->get_product_detail($row['product_id']); ?>
          <div class="pc-container-elem">
            <div class="pc-container-elem-item">
              <div class="item-box-left">
                <a href="product_detail.php?id=<?= $obj->h($row['product_id']); ?>">
                  <img src="image/<?= $obj->h($product_detail['image_url']); ?>" alt="商品画像">
                </a>
              </div>
              <div class="item-box-right">
                <div class="item-pid"><?= $obj->h($row['product_id']); ?></div>
                <div class="item-name"><?= $obj->h($product_detail['product_name']); ?></div>
                <div class="item-price">&yen;<?= $obj->h($product_detail['price']); ?></div>
                <div class="item-num">個数 <?= $obj->h($row['number']); ?></div>
                <div class="item-num"><input type="button" onclick="location.href='purchase_cart_delete.php?id=<?= $obj->h($row['product_id']); ?>'" value="削除"></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="pc-container-elem">
          <div class="pc-container-elem-item">
            <div class="item-box-left"></div>
            <div class="item-box-right">
              <div class="container-btn">
                <input type="button" onclick="delete_cart(); location.href='purchase_cart_delete.php'" value="全削除" class="btn">
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->

<!-- MC Tracking Tag (Track Shopping Cart) -->
<?php
  $cartStr = "";
  foreach ($cart_list as $row) {
  	$details = $obj->get_product_detail($row['product_id']);
  	if (strlen($cartStr) > 0) $cartStr .= ",";
  	$cartStr .= "{";
  	$cartStr .= "\"item\" : \"" . $details['product_id'] . "\",";
  	$cartStr .= "\"quantity\" : \"" . $row['number'] . "\",";
  	$cartStr .= "\"price\" : \"" . $details['price'] . "\"";
  	$cartStr .= "}";
  }
?>
<script type="text/javascript">
_etmc.push(["setOrgId", "100019644"]);
_etmc.push(["setUserInfo", {"email" : "<?= $_SESSION['member_info']['mail']; ?>"}]);
_etmc.push(["trackCart", {"cart" : [<?= $cartStr; ?>]}]);
</script>

<!-- MC Tracking Tag (Delete Shopping Cart) -->
<script type="text/javascript">
var delete_cart = function () {
  _etmc.push(["setOrgId", "100019644"]);
  _etmc.push(["trackCart", {"clear_cart": true}]);
}
</script>

</body>

</html>
