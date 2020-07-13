<?php
//共通処理
require_once('include/inc_php_start.php');

if (empty($_SESSION['member_info'])) {
  header("Location: member_login.php");
} else {
  $name        = $_SESSION['member_info']['name'];
  $postal_code = $_SESSION['member_info']['postal_code'];
  $address     = $_SESSION['member_info']['address'];

  if (!empty($_SESSION['member_info']['payment'])) {
    $payment = $_SESSION['member_info']['payment'];
  } else {
    $payment = '代金引換';
  }
}
?>

<!Doctype html>
<html lang="ja">

<head>
  <title>Heroku_ecサイト | 商品購入</title>
  <?php include('include/inc_tag_head.php'); ?>
  <link href="css/member_common.css" rel="stylesheet">
</head>

<body>
  <header><?php include('include/inc_tag_header.php'); ?></header>
  <nav><?php include('include/inc_tag_nav.php'); ?></nav>

  <main>
    <h1 class="headline"><span>PURCHASE</span></h1>
    <div class="m-container">
      <h4>お届け先住所</h4>
      <div class="m-container-elem">
        <form action="purchase_confrim.php" method="post">
          <dl>
            <dt>名前</dt>
            <dd><input type="text" name="name" maxlength="50" value="<?= $obj->h($name); ?>" required></dd>
            <dt>郵便番号</dt>
            <dd><input type="text" name="postal_code" maxlength="7" pattern="^[0-9]+$" value="<?= $obj->h($postal_code); ?>" required></dd>
            <dt>住所</dt>
            <dd><input type="text" name="address" maxlength="255" value="<?= $obj->h($address); ?>" required></dd>
            <dt>支払方法</dt>
            <dd>
              <select name="payment">
                <option value="代金引換" <?php if ($payment == '代金引換') echo 'selected'; ?>>代金引換</option>
                <option value="クレジットカード" <?php if ($payment == 'クレジットカード') echo 'selected'; ?>>クレジットカード</option>
                <option value="電子マネー" <?php if ($payment == '電子マネー') echo 'selected'; ?>>電子マネー</option>
                <option value="コンビニ払い" <?php if ($payment == 'コンビニ払い') echo 'selected'; ?>>コンビニ払い</option>
              </select>
            </dd>
          </dl>
          <div class="container-btn">
            <input type="submit" name="check" value="確認画面へ" class="btn">
            <input type="button" onclick="history.back()" value="戻る" class="btn">
          </div>
        </form>
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

</body>

</html>
