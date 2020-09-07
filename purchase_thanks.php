<?php
//共通処理
require_once('include/inc_php_start.php');

require_once('class/send_mail.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

// サンクスメール送信
$mailMsg = new Send_Mail();
$mailMsg->setMailAddress($_SESSION['member_info']['mail']);
$mailMsg->sendMail();
$result = $mailMsg->getResponses();

$order_id = $_SESSION['order_id'];
$order_header = $obj->get_order_header($order_id, $is_history = 'false');
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 商品購入</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/purchase_thanks.css" rel="stylesheet" type="text/css">
</head>

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header>
  <nav><?php include('include/inc_tag_nav.php'); ?></nav>
  <main>
    <h1 class="headline"><span>THANKS</span></h1>
    <div class="thanks-message">ご注文ありがとうございました。</div>
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
      <div class="container-btn">
        <input type="button" onclick="location.href='product_list.php'" value="買い物を続ける" class="btn">
      </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->

<!-- MC Collect Tracking Code -->
<script type="text/javascript">
_etmc.push(["setOrgId", "100019644"]);
_etmc.push(["setUserInfo", {"email" : "<?= $_SESSION['member_info']['mail']; ?>"}]);
_etmc.push(["trackPageView"]);
</script>
<!-- MC Tracking Tag (Track Purchase Details) -->
<?php
  $cartStr = "";
  foreach ($order_detail as $row) {
  	$details = $obj->get_product_detail($row['product_id']);
  	if (strlen($cartStr) > 0) $cartStr .= ",";
  	$cartStr .= "{";
  	$cartStr .= "\"item\" : \"" . $details['product_id'] . "\",";
  	$cartStr .= "\"quantity\" : \"" . $row['number'] . "\",";
  	$cartStr .= "\"price\" : \"" . $details['price'] . "\",";
  	$cartStr .= "\"unique_id\" : \"" . $details['product_id'] . "\"";
  	$cartStr .= "}";
  }
?>
<script type="text/javascript">
  var items = [];
      items.push({

      "item" : "EG026",
      "quantity": "1",
      "price" : "2457",
      "unique_id": "EG026"

    });
_etmc.push(["setOrgId", "100019644"]);
_etmc.push(["trackConversion", {"cart" : JSON.stringify(items)}]);
</script>

</body>

</html>
