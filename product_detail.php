<?php
//共通処理
require_once('include/inc_php_start.php');

// GETで取得した商品IDをキーにして商品詳細を取得する
$product_detail = $obj->get_product_detail(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING));

// 未ログインの状態でカートに商品を追加した場合のメッセージ表示用
if (isset($_SESSION['add_cart']['message'])) {
  $message = $_SESSION['add_cart']['message'];
  $_SESSION['add_cart'] = array(); // 初期化
}

//カートに商品を追加する
if (isset($_POST['add'])) {
  $array = [
    'session_id'  => session_id(),
    'product_id'  => $product_detail['product_id'],
    'number'      => filter_input(INPUT_POST, 'number', FILTER_VALIDATE_INT),
    'member_id'   => $_SESSION['member_info']['member_id'],
    'add_time'    => date("Y/m/d H:i:s"),
    'price'       => $product_detail['price'],
    'subtotal'    => $product_detail['price'] * filter_input(INPUT_POST, 'number', FILTER_VALIDATE_INT)
  ];

  // 未ログインの状態でカートに商品を追加私用とした場合はセッション変数に一時退避
  // その後、ログイン画面に遷移してログインさせる
  if (!isset($_SESSION['member_info'])) {
    $_SESSION['add_cart'] = $array;
    header("Location: member_login.php");
  } else {
    // ログイン済みならそのままカートに追加
    $message = $obj->add_to_cart($array);
  }
}
?>

<?php
// MCコンバージョントラッキング（ランディングページ）
/*
if($_GET['j']) {
  $JobID = $_GET['j'];
  $SubscriberID = $_GET['sfmc_sub'];
  $ListID = $_GET['l'];
  $UrlID = $_GET['u'];
  $MemberID = $_GET['mid'];
  $BatchID = $_GET['jb'];

// 動作環境に応じてsetcookieの書き方を変えてください。
// 参考：PHPマニュアル：setcookie
// https://www.php.net/manual/ja/function.setcookie.php
// 例: setcookie('JobID', $JobID, time()+86400, "/", ".herokuapp.com", true, true);
  setcookie('JobID', $JobID);
  setcookie('SubscriberID', $SubscriberID);
  setcookie('ListID', $ListID);
  setcookie('BatchID', $BatchID);
  setcookie('UrlID', $UrlID);
  setcookie('MemberID', $MemberID);
}
*/
?> 

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 商品詳細：<?= $obj->h($product_detail['product_name']); ?></title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/product_detail.css" rel="stylesheet" type="text/css">

<script language="javascript">
//Set the number of days before your cookie should expire
var ExpireDays = 90;
//Do not change anything below this line
qstr = document.location.search;
qstr = qstr.substring(1,qstr.length)
function SetCookie(cookieName,cookieValue,nDays)
{
	var today = new Date();
	var expire = new Date();
	if (nDays==null || nDays==0) nDays=1;
	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue)+ ";expires="+expire.toGMTString();
   }
   
   thevars = qstr.split("&");
		for(i=0;i<thevars.length;i++) {
			var element = thevars[i].split('=');
alert("thevars: key=" + element[0] + "; val=" + element[0] + ";");
			switch(element[0]) {
				case "sfmc_sub":
					SetCookie("SubscriberID",element[1],ExpireDays);
					break;
				case "j":
					thevars[i] = thevars[i].replace("=","='")+"'";
					eval(thevars[i]);
					SetCookie("JobID",element[1],ExpireDays);
					break;
				case "l":
					thevars[i] = thevars[i].replace("=","='")+"'";
					eval(thevars[i]);
					SetCookie("ListID",element[1],ExpireDays);
					break;
				case "u":
					thevars[i] = thevars[i].replace("=","='")+"'";
					eval(thevars[i]);
					SetCookie("UrlID",element[1],ExpireDays);
					break;
				case "mid":
					thevars[i] = thevars[i].replace("=","='")+"'";
					eval(thevars[i]);
					SetCookie("MemberID",element[1],ExpireDays);
					break;
				default:
					eval(thevars[i]);
					break;
			}
}
</script>

</head><!-- メタ情報やCSSのリンク先を読み込み -->

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header><!-- ヘッダーの読み込み -->
  <nav><?php include('include/inc_tag_nav.php'); ?></nav><!-- ナビゲーションの読み込み -->
  <main>
    <h1 class="headline"><span>DETAIL</span></h1>
    <?php if (!empty($message)) : ?>
      <div class="msg"><span><?= $message; ?></span></div>
      <div class="container-btn-other">
        <button type="button" onclick="location.href='purchase_cart.php'" class="btn">カートを確認する</button>
      </div>
    <?php endif; ?>
    <div class="p-container">
      <div class="p-container-item">
        <div class="item-pid"><?= $obj->h($product_detail['product_id']); ?></div>
        <div class="item-img">
          <img src="image/<?= $obj->h($product_detail['image_url']); ?>" alt="商品画像">
        </div>
        <div class="item-name"><?= $obj->h($product_detail['product_name']); ?></div>
        <div class="item-price">&yen; <?= $obj->h($product_detail['price']); ?></div>
        <div class="item-txt"><?= $obj->h($product_detail['description']); ?></div>
        <div class="item-form">
          <form action="" method="post">
            <select id="cntnum" name="number" class="select">
              <?php for ($i = 1; $i <= 99; $i++) : ?>
                <option value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
            <div class="container-btn">
              <input type="submit" name="add" value="カートに追加する" class="btn" onclick="track_cart()">
              <input type="button" onclick="history.back()" value="戻る" class="btn">
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  </main>
  <footer><?php include('include/inc_tag_footer.php'); ?></footer><!-- フッターの読み込み -->

<!-- MC Collect Tracking Code -->
<script type="text/javascript">
_etmc.push(["setOrgId", "100019644"]);
// Insert/Update Product Catalog
_etmc.push(["updateItem",
  {
    "item_type": "product",
    "item": "<?= $product_detail['product_id']; ?>",
    "name": "<?= $product_detail['product_name']; ?>",
    "url": "https://<?= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>",
    "unique_id": "<?= $product_detail['product_id']; ?>",
    "available": "Y",
    "sale_price": "<?= $product_detail['price']; ?>",
    "RegularKakaku": "<?= $product_detail['price']; ?>",
    "RegularKakaku_dot": "Y",
    "SaleKakaku": "<?= $product_detail['price']; ?>",
    "SaleKakaku_dot": "Y",
    "description": "<?= $product_detail['description']; ?>"
  }
]);
// Insert/Update Content Catalog
_etmc.push(["updateItem",
  {
    "item_type": "content",
    "item": "PRODUCT_DETAIL_<?= $product_detail['product_id']; ?>",
    "url": "https://<?= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>",
    "available": "Y"
  }
]);
// Tracking Page
_etmc.push(["setUserInfo", {"email" : "<?= $_SESSION['member_info']['mail']; ?>"}]);
_etmc.push(["trackPageView", {"item" : "<?= $product_detail['product_id']; ?>"}]);
</script>

<!-- MC Collect Tracking Code (Button Action) -->
<script type="text/javascript">
var track_cart = function () {
//  _etmc.push(["trackPageView", {"item" : "<?= $product_detail['product_id']; ?>"}]);
  _etmc.push(["trackEvent", {"name" : "CartInsert",
    "details" : {
      "email" : "<?= $_SESSION['member_info']['mail']; ?>",
      "item" : "<?= $product_detail['product_id']; ?>",
      "name" : "<?= $product_detail['product_id']; ?>"
    }
  }]);
}
</script>

</body>

</html>
