<?php
//共通処理
require_once('include/inc_php_start.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
}

$member_id = $_SESSION['member_info']['member_id']; //会員ID
$result = $obj->get_cart_list($member_id); //カートの中身取得
$cart_total_price = $obj->get_cart_total_price($member_id);; //合計金額

// purchase.phpからpostで渡された値
$name                 = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); //注文者名
$delivery_postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_NUMBER_INT); //配送先郵便番号
$delivery_address     = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING); //配送先住所
$payment              = filter_input(INPUT_POST, 'payment', FILTER_SANITIZE_STRING); //決済情報

if (isset($_POST['confrim'])) {
  $array = [
    'order_id'             => 'oid' . date("YmdHis"), //受注ID
    'member_id'            => $member_id, //会員ID
    'name'                 => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), //注文者名
    'sex'                  => $_SESSION['member_info']['sex'], //性別
    'birthday'             => $_SESSION['member_info']['birthday'], //誕生日
    'postal_code'          => $_SESSION['member_info']['postal_code'], //郵便番号
    'address'              => $_SESSION['member_info']['address'], //住所
    'mail'                 => $_SESSION['member_info']['mail'], //メールアドレス
    'delivery_postal_code' => filter_input(INPUT_POST, 'delivery_postal_code', FILTER_SANITIZE_NUMBER_INT), //配送先郵便番号
    'delivery_address'     => filter_input(INPUT_POST, 'delivery_address', FILTER_SANITIZE_STRING), //配送先住所
    'payment'              => filter_input(INPUT_POST, 'payment', FILTER_SANITIZE_STRING), //決済情報
    'purchase_time'        => date("Y/m/d H:i:s"), //購入日時
    'total_price'          => $cart_total_price['sum'] //合計金額
  ];
  $obj->register_order($array); //注文情報登録
  header('Location: purchase_thanks.php'); //注文完了ページに移動
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 商品購入</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/purchase_confrim.css" rel="stylesheet" type="text/css">
</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header>
  <nav><?php include('include/inc_tag_nav.php'); ?></nav>
  <main>
    <h1 class="headline"><span>PURCHASE</span></h1>
    <div class="m-container">
      <h4>注文内容の確定</h4>
      <div class="m-container-elem">
        <form action="purchase_confrim.php" method="post">
          <div class="pc-container-elem">
            <div class="pc-container-elem-item">
              <div class="item-box-left">
                <div class="item-total">合計 &yen;<?= $cart_total_price['sum']; ?></div>
              </div>
              <div class="item-box-right">
                <div class="container-btn">
                  <input type="submit" name="confrim" value="注文確定" class="btn" onclick="track_purchase()">
                </div>
              </div>
            </div>
          </div>
          <dl>
            <dt>名前</dt>
            <dd><input type="text" name="name" value="<?= $obj->h($name); ?>" readonly></dd>
            <dt>郵便番号</dt>
            <dd><input type="text" name="delivery_postal_code" maxlength="7" pattern="^[0-9]+$" value="<?= $obj->h($delivery_postal_code); ?>" readonly></dd>
            <dt>住所</dt>
            <dd><input type="text" name="delivery_address" value="<?= $obj->h($delivery_address); ?>" readonly></dd>
            <dt>支払方法</dt>
            <dd><input type="text" name="payment" value="<?= $obj->h($payment); ?>" readonly></dd>
          </dl>
        </form>
      </div>
      <div class="pc-container">
        <?php foreach ($result as $row) : ?>
          <?php $details = $obj->get_product_detail($row['product_id']); ?>
          <div class="pc-container-elem">
            <a class="pc-container-elem-item" href="product_detail.php?id=<?= $obj->h($row['product_id']); ?>">
              <div class="item-box-left">
                <img src="image/<?= $obj->h($details['image_url']); ?>" alt="商品画像">
              </div>
              <div class="item-box-right">
                <div class="item-name"><?= $obj->h($details['product_name']); ?></div>
                <div class="item-price">&yen;<?= $obj->h($details['price']); ?></div>
                <div class="item-num">個数 <?= $obj->h($row['number']); ?></div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
        <div class="pc-container-elem">
          <div class="pc-container-elem-item" href="#">
            <div class="item-box-left"></div>
            <div class="item-box-right">
              <div class="container-btn">
                <input type="button" onclick="history.back()" value="戻る" class="btn">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->

<!-- MC Collect Tracking Code -->
<script type="text/javascript">
_etmc.push(["setOrgId", "100019644"]);
_etmc.push(["setUserInfo", {"email" : "<?= $_SESSION['member_info']['mail']; ?>"}]);
//_etmc.push(["trackPageView"]);
</script>

<!-- MC Tracking Tag (Track Purchase Details) -->
<?php
  $cartStr = "";
  foreach ($result as $row) {
  	$details = $obj->get_product_detail($row['product_id']);
  	if (strlen($cartStr) > 0) $cartStr .= ",";
  	$cartStr .= "{ ";
  	$cartStr .= "\"item\" : \"" . $details['product_id'] . "\",";
  	$cartStr .= "\"quantity\" : \"" . $row['number'] . "\",";
  	$cartStr .= "\"price\" : \"" . $details['price'] . "\"";
  	$cartStr .= "}";
  }
?>
<script type="text/javascript">
var track_purchase = function () {
//  _etmc.push(["setOrgId", "100019644"]);
//  _etmc.push(["trackConversion", {"cart" : [<?= $cartStr; ?>]}]);
}
</script>

<!-- MCコンバージョントラッキング（コンバージョンページ） -->
<?php 
print_r($_COOKIE);
//This code should be on any confirmation page after the conversion is made. 
//Refer to the Conversion Tracking Guide for customization. 
$debug = 'off'; 
$JobID = $_COOKIE['JobID']; 
$SubscriberID = $_COOKIE['SubscriberID']; 
$ListID = $_COOKIE['ListID']; 
$BatchID = $_COOKIE['BatchID']; 
$UrlID = $_COOKIE['UrlID']; 
$MemberID = $_COOKIE['MemberID']; 

//Refer to the Conversion Guide on customizing the data within this XML to display the proper conversion 
//information within 
$strTP  = "<img src='http://click.exacttarget.com/conversion.aspx?xml=";
$strTP .= "<system><system_name>tracking</system_name><action>conversion</action>";
$strTP .= "<member_id>".$MemberID."</member_id>";
$strTP .= "<job_id>".$JobID."</job_id>";
$strTP .= "<email></email>";
$strTP .= "<sub_id>".$SubscriberID."</sub_id>"; 
$strTP .= "<list>".$ListID."</list>";
$strTP .= "<BatchID>".$BatchID."</BatchID>";
$strTP .= "<original_link_id>".$UrlID."</original_link_id>";
$strTP .= "<conversion_link_id>1</conversion_link_id>";
$strTP .= "<link_alias>Alias</link_alias><display_order>1</display_order>";
$strTP .= "<data_set>";
$strTP .= '<data amt="1" unit="Confirm" accumulate="true" />';
$strTP .= "</data_set></system>'";
$strTP .= " width='1' height='1'>";

if ($debug=='on'){ 
    $strTP = '<textarea rows=10 cols=50>'.$strTP.'<textarea>'; 
} 
?> 
<?= $strTP; ?> 

</body>

</html>
